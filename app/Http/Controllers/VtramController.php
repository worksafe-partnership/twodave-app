<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Vtram;
use App\Hazard;
use App\Project;
use App\Company;
use App\Template;
use App\TableRow;
use App\Instruction;
use App\Icon;
use App\VtramUser;
use App\Methodology;
use App\UserProject;
use App\ProjectSubcontractor;
use App\Http\Classes\VTLogic;
use App\Http\Requests\VtramRequest;

class VtramController extends CompanyVtramController
{
    protected $identifierPath = 'project.vtram';

    public function createHook()
    {
        $company = Company::findOrFail($this->user->company_id);
        if (isset($_GET['template'])) {
            $template = Template::findOrFail($_GET['template']);
            $this->customValues['createdFromEntity'] = "TEMPLATE";
            $this->customValues['createdFromId'] = $_GET['template'];
            $project = Project::findOrFail($this->parentId);
            if (!in_array($project->id, $this->user->projectCompanyIds())) {
                abort(404);
            }
            if ($template->status != "CURRENT") {
                abort(404);
            }

            if (isset($template['key_points'])) {
                $this->customValues['key_points'] = $template['key_points'];
            }

            $this->customValues['name'] = $template['name'];
            $this->customValues['reference'] = $template['reference'];
            $this->customValues['logo'] = $template['logo'];

            $this->customValues['main_description'] = $template['main_description'];
            $this->customValues['post_risk_assessment_text'] = $template['post_risk_assessment_text'];
        } else {
            $this->customValues['main_description'] = $company['main_description'];
            $this->customValues['post_risk_assessment_text'] = $company['post_risk_assessment_text'];
        }
    }

    public function postIndexHook()
    {
        if (isset($this->actionButtons['create']['class'])) {
            $this->actionButtons['create']['class'] .= " create_vtram";
        }

        if (!strpos($this->identifierPath, "previous")) {
            $project = Project::findOrFail($this->parentId);
            $this->customValues['company'] = $this->user->company;
            $templates = Template::whereIn('company_id', [$project->company_id, $this->user->company_id])
                                                       ->join('companies', 'templates.company_id', '=', 'companies.id')
                                                       ->where('status', 'CURRENT')
                                                       ->get([
                                                            'companies.name as company_name', 'templates.name', 'templates.id'
                                                        ]);
            $this->customValues['templates'] = [];
            foreach ($templates as $template) {
                $this->customValues['templates'][$template->id] = $template->name . " (" . $template->company_name .")";
            }
            $this->customValues['templates'] = collect($this->customValues['templates']);
        }

        $this->customValues['path'] = 'vtram/create';
    }

    public function bladeHook()
    {
        $permittedVTrams = Auth::User()->vtramsCompanyIds();
        if (!is_null($this->record) && !in_array($this->record->id, $permittedVTrams)) {
            abort(404);
        }

        $project = Project::findOrFail($this->parentId);
        $this->customValues['company'] = $company = $this->user->company;

        $companies = [$project->company_id, $this->user->company_id];
        if ($this->user->company_id != $project->company_id) {
            $companiesWithAccess = ProjectSubcontractor::where('project_id', $this->id)->get();
            $myAccess = $companiesWithAccess->where('company_id', $this->user->company_id)->first();
            if ($myAccess) {
                if ($myAccess->contractor_or_sub = "SUBCONTRACTOR") {
                    $contractorIds = $companiesWithAccess->where('contractor_or_sub', 'CONTRACTOR')->pluck('company_id')->toArray();
                    foreach ($contractorIds as $id) {
                        $companies[] = $id;
                    }
                }
            }
        }
        $companies = array_unique($companies);

        $templates = Template::whereIn('company_id', $companies)
                                                   ->join('companies', 'templates.company_id', '=', 'companies.id')
                                                   ->where('status', 'CURRENT')
                                                   ->get([
                                                        'companies.name as company_name', 'templates.name', 'templates.id'
                                                    ]);
        $this->customValues['templates'] = [];
        foreach ($templates as $template) {
            $this->customValues['templates'][$template->id] = $template->name . " (" . $template->company_name .")";
        }
        $this->customValues['templates'] = collect($this->customValues['templates']);

        if ($this->pageType == "create") {
            $templateSelector = $this->customValues['templates']->toArray();
            $this->customValues['templateSelector'] = $templateSelector;
        }
        $this->customValues['path'] = 'create';
        $this->config['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->config['plural'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['plural'] = $company->vtrams_name ?? 'VTRAMS';

        $translation = ["CONTRACTOR" => " (C)", "SUBCONTRACTOR" => " (S)"];
        $compAndContractors = ProjectSubContractor::where('project_id', $this->args[0])
                                                    ->join('companies', 'companies.id', '=', 'project_subcontractors.company_id')
                                                    ->get(['companies.name', 'companies.id', 'project_subcontractors.contractor_or_sub']);
        foreach ($compAndContractors as $c) {
            if ($company->is_principal_contractor) {
                $this->customValues['compAndContractors'][$c->id] = $c->name . $translation[$c->contractor_or_sub];
            } else {
                $this->customValues['compAndContractors'][$c->id] = $c->name;
            }
        }
        $projCompany = $project->company;
        $this->customValues['compAndContractors'][$projCompany->id] = $projCompany->name;

        $this->customValues['companyId'] = $company->id;

        $companiesOnProject = ProjectSubcontractor::where('project_id', $this->parentId)->pluck('contractor_or_sub', 'company_id');
        $projectUsers = UserProject::where('project_id', $this->parentId)
                              ->join('users', 'user_projects.user_id', '=', 'users.id')
                              ->join('companies', 'users.company_id', '=', 'companies.id')
                              ->get([
                                'users.id',
                                'users.name',
                                'companies.id as company_id',
                                'companies.name as c_name'
                              ]);

        $this->customValues['projectUsers'] = [];
        foreach ($projectUsers as $user) {
            if ($projCompany->is_principal_contractor && $this->user->company_id == $projCompany->id && isset($companiesOnProject[$user->company_id])) {
                $this->customValues['projectUsers'][$user->id] = $user->name . " (" . $user->c_name . ")" . $translation[$companiesOnProject[$user->company_id]];
            } else {
                $this->customValues['projectUsers'][$user->id] = $user->name . " (" . $user->c_name . ")";
            }
        }
        $this->customValues['projectUsers'] = collect($this->customValues['projectUsers']);

        $this->customValues['associatedUsers'] = [];
        if ($this->pageType != "create") {
            $assoc = VtramUser::where('vtrams_id', $this->record->id)->pluck('user_id')->toArray();
            foreach ($assoc as $userId) {
                $this->customValues['associatedUsers'][$userId] = 1;
            }
            if (count($assoc) > 0) {
                if (!in_array($this->user->id, $assoc)) {
                    abort(404);
                }
            }
        }

        $this->customValues['is_file_vtram'] = 0;
        if ($_GET && isset($_GET['file_upload'])) {
            $this->customValues['is_file_vtram'] = 1;
        } else if ($this->pageType != "create" && $this->record->vtram_is_file) {
            $this->customValues['is_file_vtram'] = 1;
        }

    }

    public function indexHook()
    {
        $permittedProjects = Auth::User()->projectCompanyIds();
        if (!is_null($this->record) && !in_array($this->record->project_id, $permittedProjects)) {
            abort(404);
        }

        $company = $this->user->company;
        $this->config['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->config['plural'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['plural'] = $company->vtrams_name ?? 'VTRAMS';
    }

    public function viewHook()
    {
        if (can('edit', $this->identifierPath)) {
            if ($this->record->company_id == $this->user->company_id) {
                $prevConfig = config('structure.project.vtram.previous.config');
                $this->actionButtons['previous'] = [
                    'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
                    'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/previous',
                    'icon' => $prevConfig['icon'],
                    'order' => '600',
                    'id' => 'previousList'
                ];
            }
            // moving this into /edit also to stop supervisors seeing the approve button
            $approvalConfig = config('structure.project.vtram.approval.config');
            $this->actionButtons['approval'] = [
                'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
                'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/approval',
                'icon' => $approvalConfig['icon'],
                'order' => '650',
                'id' => 'approvalList'
            ];
        }
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED','AMEND','EXTERNAL_AMEND'])) {
            $this->disableEdit = true;
        }
        if ($this->record->company_id != $this->user->company_id) {
            $this->disableDelete = true;
        }
    }

    public function editHook()
    {
        $this->formButtons['save_method_statement'] = [
            'class' => [
                'button',
                'is-primary',
                'submit-meth-form',
            ],
            'name' => 'save_method_statement',
            'label' => 'Save Method Statement',
            'order' => 1,
            'value' => true,
        ];
        $this->formButtons['cancel_method_statement'] = [
            'class' => [
                'button',
            ],
            'name' => 'cancel_method_statement',
            'label' => 'Cancel Method Statement',
            'order' => 2,
            'value' => true,
            'onclick' => "cancelForm('methodology');",
        ];
        $this->formButtons['save_hazard'] = [
            'class' => [
                'button',
                'is-primary',
                'submit-hazard-form',
            ],
            'name' => 'save_hazard',
            'label' => 'Save Risk Assessment',
            'order' => 3,
            'value' => true,
        ];
        $this->formButtons['cancel_hazard'] = [
            'class' => [
                'button',
            ],
            'name' => 'cancel_hazard',
            'label' => 'Cancel Risk Assessment',
            'order' => 4,
            'value' => true,
            'onclick' => "cancelForm('hazard');",
        ];
        if (in_array($this->record->status, ['REJECTED','EXTERNAL_REJECT','NEW','AMEND','EXTERNAL_AMEND'])) {
            $this->formButtons['save_and_submit'] = [
                'class' => [
                    'submitbutton',
                    'button',
                    'is-primary',
                ],
                'name' => 'send_for_approval',
                'label' => 'Save & Exit & Submit for Review',
                'order' => 150,
                'value' => true,
            ];
        }
        $this->formButtons['back_to_edit'] = [
            'class' => [
                'submitbutton',
                'button',
                'is-primary',
            ],
            'name' => 'back_to_edit',
            'label' => 'Save & Continue',
            'order' => 150,
            'value' => true,
        ];
        $this->submitButtonText = 'Save & Exit';
        $project = $this->args[0];
        $this->customValues['path'] = '/project/'.$project.'/vtram/create';

        $company = Company::find($this->record->company_id);
        if ($company != null) {
            $this->customValues['riskList'] = [
                0 => $company->no_risk_character,
                1 => $company->low_risk_character,
                2 => $company->med_risk_character,
                3 => $company->high_risk_character,
            ];
        } else {
            $this->customValues['riskList'] = [
                0 => '#',
                1 => 'L',
                2 => 'M',
                3 => 'H',
            ];
            $company = collect([]); // blade requires a company for the TEXT methodology company defaults
        }
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $this->customValues['methTypeList'] = config('egc.methodology_list');
        $this->customValues['hazards'] = Hazard::where('entity', '=', 'VTRAM')
            ->where('entity_id', '=', $this->id)
            ->orderBy('list_order')
            ->get();
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'VTRAM')
            ->where('entity_id', '=', $this->id)
            ->orderBy('list_order')
            ->get();

        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, 'VTRAM');
        $this->customValues['entityType'] = 'VTRAM';

        // Start of Methodology Specific Items //
        $this->customValues['iconSelect'] = config('egc.icons');
        $this->customValues['iconImages'] = json_encode(config('egc.icon_images'));
        $this->customValues['company'] = $company;

        $methodologyIds = $this->customValues['methodologies']->pluck('id');

        $this->customValues['tableRows'] = [];
        if (!$this->record->vtram_is_file) {
            $tableRows = TableRow::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
            foreach ($tableRows as $row) {
                $this->customValues['tableRows'][$row->methodology_id][] = $row;
            }
        }

        $this->customValues['processes'] = [];
        if (!$this->record->vtram_is_file) {
            $instructions = Instruction::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
            foreach ($instructions as $instruction) {
                $this->customValues['processes'][$instruction->methodology_id][] = $instruction;
            }
        }


        $this->customValues['icons'] = [];
        if (!$this->record->vtram_is_file) {
            $icons = Icon::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
            foreach ($icons as $icon) {
                $this->customValues['icons'][$icon->methodology_id][$icon->type][] = $icon;
            }
        }
        // End of Methodology Specific Items //

        $this->customValues['hazard_methodologies'] = [];
        $hms = \DB::table('hazards_methodologies')->whereIn('hazard_id', $this->customValues['hazards']->pluck('id'))->get();
        foreach ($hms as $hm) {
            $this->customValues['hazard_methodologies'][$hm->hazard_id][] = $hm->methodology_id;
        }
    }

    public function postEditHook()
    {
        $project = $this->args[0];
        $this->customValues['path'] = '/project/'.$project.'/vtram/create';
    }

    public function submitForApproval($projectId, $vtramId, $otherId = null)
    {
        return parent::submitForApproval(null, $projectId, $vtramId);
    }

    public function viewA3($projectId, $vtramId, $otherId = null, $otherId2 = null)
    {
        return parent::viewA3($otherId, $projectId, $vtramId);
    }

    public function viewA4($projectId, $vtramId, $otherId = null, $otherId2 = null)
    {
        return parent::viewA4($otherId, $projectId, $vtramId);
    }

    public function store(VtramRequest $request, $projectId, $otherId = null)
    {
        $user = Auth::user();
        $company = Company::findOrFail($user->company_id);
        if (isset($request->template)) {
            $request->merge([
                'created_from_id' => $request->template,
                'created_from_entity' => 'TEMPLATE'
            ]);
        } 
        $request->merge([
            'project_id' => $projectId,
            'company_id' => $user->company_id,
            'main_description' => $company->main_description,
            'post_risk_assessment_text' => $company->post_risk_assessment_text,
            'created_by' => $user->id,
        ]);

        if ($request->vtram_file) {
            $request->merge(['vtram_is_file' => 1]);
        }

        return parent::_store([
            $request,
            $projectId
        ]);
    }

    public function update(VtramRequest $request)
    {
        $args = func_get_args();
        VtramUser::where('vtrams_id', end($args))->delete();
        if (isset($request['associated_users'])) {
            $toInsert = [];
            foreach ($request['associated_users'] as $userId) {
                $toInsert[] = [
                    'vtrams_id' => end($args),
                    'user_id' => $userId
                ];
            }
            VtramUser::insert($toInsert);
        }

        $request->merge([
            'updated_by' => Auth::id(),
        ]);
        return parent::_update(func_get_args());
    }

    public function updated($record, $orig, $request, $args)
    {
        if (isset($request['back_to_edit'])) {
            return $this->fullPath.'/edit';
        }

        return parent::updated($record, $orig, $request, $args);
    }
}
