<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Vtram;
use App\Hazard;
use App\Project;
use App\Company;
use App\Template;
use App\Methodology;
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
            if ($template->company_id != $this->user->company_id) {
                abort(404);
            }
            if ($template->status != "CURRENT") {
                abort(404);
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
        $this->customValues['templates'] = Template::where('company_id', $this->user->company_id)
                                                   ->where('status', 'CURRENT')
                                                   ->pluck('name', 'id');
        $this->customValues['path'] = 'vtram/create';
    }

    public function bladeHook()
    {
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->project->company_id) {
                abort(404);
            }
        }
        if ($this->user->inRole('supervisor') && $this->record !== null) {
            if (!$this->record->project->userOnProject($this->user->id)) {
                abort(404);
            }
        }
        $this->customValues['templates'] = Template::where('company_id', $this->user->company_id)
                                                   ->where('status', 'CURRENT')
                                                   ->pluck('name', 'id');
        $this->customValues['path'] = 'create';
        $this->customValues['company'] = $company = $this->user->company;
        $this->config['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->config['plural'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['plural'] = $company->vtrams_name ?? 'VTRAMS';

        $this->customValues['compAndContractors'] = ProjectSubContractor::where('project_id', $company->id)
                                                                        ->join('companies', 'companies.id', '=', 'project_subcontractors.company_id')
                                                                        ->pluck('companies.name', 'companies.id')
                                                                        ->toArray();
        $this->customValues['compAndContractors'][$company->id] = $company->name;

        $this->customValues['companyId'] = $company->id;
    }

    public function indexHook()
    {
        if ($this->user->company_id !== null) {
            $project = Project::findOrFail($this->parentId);
            if ($this->user->company_id !== $project->company_id) {
                abort(404);
            }
        }
        $company = $this->user->company;
        $this->config['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->config['plural'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['singular'] = $company->vtrams_name ?? 'VTRAMS';
        $this->structure['config']['plural'] = $company->vtrams_name ?? 'VTRAMS';
    }

    public function viewHook()
    {
        if (can('edit', $this->identifierPath) && in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED','AMEND','EXTERNAL_AMEND'])  && is_null($this->record['deleted_at'])) {
            $this->actionButtons['methodologies'] = [
                'label' => 'Method Statements & Risk Assessment',
                'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/methodology',
                'icon' => 'receipt',
                'order' => '500',
                'id' => 'methodologyEdit',
            ];
        }
        if (can('edit', $this->identifierPath)) {
            $prevConfig = config('structure.project.vtram.previous.config');
            $this->actionButtons['previous'] = [
                'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
                'path' => '/project/'.$this->parentId.'/vtram/'.$this->id.'/previous',
                'icon' => $prevConfig['icon'],
                'order' => '600',
                'id' => 'previousList'
            ];
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
    }

    public function editHook()
    {
        if (in_array($this->record->status, ['REJECTED','EXTERNAL_REJECT','NEW','AMEND','EXTERNAL_AMEND'])) {
            $this->formButtons['save_and_submit'] = [
                'class' => [
                    'submitbutton',
                    'button',
                    'is-primary',
                ],
                'name' => 'send_for_approval',
                'label' => 'Update and Submit for Approval',
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
            'label' => 'Save',
            'order' => 150,
            'value' => true,
        ];
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
        $request->merge([
            'project_id' => $projectId,
            'company_id' => $user->company_id,
            'main_description' => $company->main_description,
            'post_risk_assessment_text' => $company->post_risk_assessment_text,
            'created_by' => $user->id,
        ]);
        return parent::_store([
            $request,
            $projectId
        ]);
    }

    public function update(VtramRequest $request)
    {
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
    }
}
