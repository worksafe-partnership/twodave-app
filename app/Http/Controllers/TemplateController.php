<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use EGFiles;
use Storage;
use App\Icon;
use Controller;
use App\Hazard;
use App\Company;
use App\TableRow;
use App\Approval;
use App\Template;
use App\Project;
use App\Instruction;
use App\Methodology;
use Illuminate\Http\Request;
use App\Http\Classes\VTLogic;
use App\Http\Requests\TemplateRequest;
use App\Http\Requests\EditVtramRequest;

class TemplateController extends Controller
{
    protected $identifierPath = 'template';

    public function createHook()
    {
        if (strpos($this->identifierPath, 'company') === false) {
            $company = Company::find($this->user->company_id);
        } else {
            $company = Company::findOrFail($this->parentId);
        }

        if ($company != null) {
            $this->customValues['main_description'] = $company['main_description'];
            $this->customValues['post_risk_assessment_text'] = $company['post_risk_assessment_text'];
        }
    }

    public function companyPresets($companyId)
    {
        $company = Company::findOrFail($companyId);
        return json_encode([
            'status' => 'success',
            'text' => [
                'main_description' => $company->main_description,
                'post_risk_assessment_text' => $company->post_risk_assessment_text,
            ]
        ]);
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
        if (in_array($this->record->status, ['REJECTED','EXTERNAL_REJECT','NEW','AMEND','EXTERNAL_AMEND']) && is_null($this->record['deleted_at'])) {
            $this->formButtons['save_and_submit'] = [
                'class' => [
                    'submitbutton',
                    'button',
                    'is-primary',
                ],
                'name' => 'send_for_approval',
                'label' => 'Save & Submit for Review',
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

        $company = Company::find($this->record->company_id);
        if ($company != null) {
            $this->customValues['riskList'] = [
                0 => $company->low_risk_character,
                1 => $company->med_risk_character,
                2 => $company->high_risk_character,
            ];
        } else {
            $this->customValues['riskList'] = [
                0 => 'L',
                1 => 'M',
                2 => 'H',
            ];
            $company = collect([]); // blade requires a company for the TEXT methodology company defaults
        }
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $this->customValues['methTypeList'] = config('egc.methodology_list');
        $this->customValues['hazards'] = Hazard::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $this->id)
            ->orderBy('list_order')
            ->get();
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $this->id)
            ->orderBy('list_order')
            ->get();

        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, 'TEMPLATE');
        $this->customValues['entityType'] = 'TEMPLATE';

        // Start of Methodology Specific Items //
        $this->customValues['iconSelect'] = config('egc.icons');
        $this->customValues['iconImages'] = json_encode(config('egc.icon_images'));
        $this->customValues['company'] = $company;

        $methodologyIds = $this->customValues['methodologies']->pluck('id');

        $this->customValues['tableRows'] = [];
        $tableRows = TableRow::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
        foreach ($tableRows as $row) {
            $this->customValues['tableRows'][$row->methodology_id][] = $row;
        }

        $this->customValues['processes'] = [];
        $instructions = Instruction::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
        foreach ($instructions as $instruction) {
            $this->customValues['processes'][$instruction->methodology_id][] = $instruction;
        }

        $this->customValues['icons'] = [];
        $icons = Icon::whereIn('methodology_id', $methodologyIds)->orderBy('list_order')->get();
        foreach ($icons as $icon) {
            $this->customValues['icons'][$icon->methodology_id][$icon->type][] = $icon;
        }
        // End of Methodology Specific Items //

        $this->customValues['hazard_methodologies'] = [];
        $hms = DB::table('hazards_methodologies')->whereIn('hazard_id', $this->customValues['hazards']->pluck('id'))->get();
        foreach ($hms as $hm) {
            $this->customValues['hazard_methodologies'][$hm->hazard_id][] = $hm->methodology_id;
        }
    }

    public function postEditHook()
    {
        $this->formButtons['cancel']['onclick'] = 'return confirm("Are you sure you want to cancel? Any unsaved changes will be lost")';
    }

    public function viewHook()
    {
        $prevConfig = config('structure.template.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/template/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '560',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.template.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/template/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '570',
            'id' => 'approvalList'
        ];
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED','AMEND','EXTERNAL_AMEND'])) {
            $this->disableEdit = true;
        }
        if ($this->user->company_id == null) {
            $this->customValues['templatePath'] = '/company/'.$this->record->company_id.'/project/';
        } else {
            $this->customValues['templatePath'] = '/project/';
        }
    }

    public function viewEditHook()
    {
        // don't let company admins see templates that don't belong to them.
        if (!is_null($this->user->company_id) && !in_array($this->user->company_id, $this->user->getAccessCompanies())) {
            abort(404);
        }
    }

    public function bladeHook()
    {
        $this->customValues['companies'] = Company::when(!is_null($this->user->company_id), function ($sub) {
            $sub->where('id', $this->user->company_id);
        })->orderBy('name', 'ASC')
        ->withTrashed()
        ->pluck('name', 'id');
    }

    public function store(TemplateRequest $request)
    {
        $company = Company::findOrFail($request->company_id);
        $request->merge([
            'main_description' => $company->main_description,
            'post_risk_assessment_text' => $company->post_risk_assessment_text,
        ]);
        return parent::_store(func_get_args());
    }

    public function update(TemplateRequest $request, $companyId)
    {
        return parent::_update(func_get_args());
    }

    public function updateFromMethodology(EditVtramRequest $request)
    {
        $request->merge([
            'updated_by' => Auth::id(),
            'from_methodology' => true, // used for override in update function.
            'return_path' => str_replace("edit_extra", "methodology", $request->path())
        ]);

        return parent::_update(func_get_args());
    }

    public function createRevision()
    {
        $this->args = func_get_args();
        $templateId = end($this->args);
        $this->record = Template::findOrFail($templateId);
        if ($this->record->status != 'CURRENT') {
            abort(404);
        }
        $this->parentId = $this->record->project_id;
        $this->id = $this->record->id;
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->company_id) {
                abort(404);
            }
        }

        // Check there are no existing revisions
        $template = Template::where('created_from', '=', $this->record->id)
            ->get();
        if ($template->count() > 0) {
            abort(404);
        }

        $result = VTLogic::copyEntity($this->record);

        if ($result instanceof Template) {
            $this->_buildProperties($this->args);
            toast()->success("Revision Created, you're now viewing the new Template");
            return redirect($this->parentPath.'/'.$result->id);
        }
        toast()->error('Failed to create new Revision');
        return back();
    }

    public function postViewHook()
    {
        // Setup Actions
        if (in_array($this->record->pages_in_pdf, [2,3,4])) {
            $this->pillButtons['view_pdf_a3'] = [
                'label' => 'View PDF A3',
                'path' => $this->record->id.'/view_a3',
                'icon' => 'file-pdf',
                'order' => 100,
                'id' => 'view_pdf_a3',
                'target' => '_blank',
            ];
        }

        $this->pillButtons['view_pdf'] = [
            'label' => 'View PDF A4',
            'path' => $this->record->id.'/view_a4',
            'icon' => 'file-pdf',
            'order' => 100,
            'id' => 'view_pdf',
            'target' => '_blank',
        ];
        if (in_array($this->record->status, ['NEW','REJECTED','EXTERNAL_REJECT','AMEND','EXTERNAL_AMEND']) && is_null($this->record['deleted_at'])) {
            $this->pillButtons['submit_for_approval'] = [
                'label' => 'Submit for Review',
                'path' => $this->record->id.'/submit',
                'icon' => 'tick',
                'order' => 100,
                'id' => 'submit_for_approval',
            ];
        }

        if (strpos($this->identifierPath, 'previous') === false && $this->record->created_from != null) {
            $this->pillButtons['view_created_from'] = [
                'label' => 'View Template',
                'path' => $this->record->id.'/previous/'.$this->record->created_from,
                'icon' => 'call_missed',
                'order' => 100,
                'id' => 'view_created_from',
            ];
        }

        if ($this->record->status != "NEW" && $this->record->deleted_at == null) {
            $this->pillButtons['view_comments'] = [
                'label' => 'View All Comments',
                'path' => $this->record->id.'/comment',
                'icon' => 'comment',
                'order' => 600,
                'id' => 'view_comments',
            ];
        }

        if (VTLogic::canReview($this->record) && $this->record->deleted_at == null) {
            if ($this->record->pages_in_pdf == 4) {
                $path = 'javascript: window.open("'.$this->record->id.'/view_a3", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
            } else {
                $path = 'javascript: window.open("'.$this->record->id.'/view_a4", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
            }
            if (is_null($this->record['deleted_at'])) {
                $this->pillButtons['approve_template'] = [
                    'label' => 'Review Template',
                    'path' => $path,
                    'icon' => 'playlist_add_check',
                    'id' => 'approve_template',
                ];
            }
        }

        $template = Template::where('created_from', '=', $this->record->id)
            ->get();
        if ($this->record->status == 'CURRENT' && $template->count() == 0 && is_null($this->record['deleted_at']) && can('edit', $this->identifierPath)) {
            $this->actionButtons[] = [
                'label' => 'Create New Revision',
                'path' => $this->record->id.'/revision',
                'icon' => 'new-tab',
                'order' => '200',
                "onclick" => "return confirm('Are you sure you want to make a new revision of this template?')",
                'id' => 'create_new_revision',
            ];
        }


        $projects = Project::whereIn('id', $this->user->projectCompanyIds())
            ->get();
        $this->customValues['projects'] = [];
        foreach ($projects as $project) {
            $this->customValues['projects'][$project->id] = $project->name." (".$project->company->name.")";
        }
        if ($this->record->status == 'CURRENT' && $this->record->deleted_at == null) {
            $this->pillButtons[] = [
                'label' => 'Create Blank '.($this->record->company->vtrams_name ?? 'VTRAMS'),
                'path' => '',
                'icon' => 'document-add',
                'order' => '600',
                'id' => 'createVtrams',
                'class' => 'create_vtrams_from_template',
            ];
            $this->pillButtons[] = [
                'label' => 'Create '.($this->record->company->vtrams_name ?? 'VTRAMS').' from Template',
                'path' => '',
                'icon' => 'document-add',
                'order' => '600',
                'id' => 'createVtrams-template',
                'class' => 'create_vtrams_from_template',
            ];
        }
    }

    public function edit()
    {
        $this->args = func_get_args();
        $id = end($this->args);
        $this->record = Template::findOrFail($id);
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED','AMEND','EXTERNAL_AMEND'])) {
            abort(404);
        }
        return parent::_edit(func_get_args());
    }

    public function submitForApproval($templateId, $otherId = null)
    {
        $user = Auth::user();
        $template = Template::findOrFail($templateId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $template->company_id && $template->company_id !== null) {
                abort(404);
            }
        }
        VTLogic::submitForApproval($template);
        if ($this->identifierPath == 'company.template') {
            $url = '/company/'.$otherId.'/template/'.$templateId;
        } else {
            $url = '/template/'.$templateId;
        }
        toast()->success("Template submitted for Review");
        return redirect($url);
    }

    public function viewA3($templateId, $companyId = null, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $template = Template::withTrashed()->findOrFail($templateId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $template->company_id) {
                abort(404);
            }
        }
        if (in_array($template->pages_in_pdf, [2,3,4])) {
            return VTLogic::createA3Pdf($template, null, true);
        }
        return VTLogic::createPdf($template, null, true);
    }

    public function viewA4($templateId, $companyId = null, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $template = Template::withTrashed()->findOrFail($templateId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $template->company_id) {
                abort(404);
            }
        }
        return VTLogic::createPdf($template, null, true);
    }

    public function updated($update, $orig, $request, $args)
    {
        if (isset($request['send_for_approval'])) {
            VTLogic::submitForApproval($update);
            toast()->success("Template submitted for Review");
        } else {
            VTLogic::createPdf($update, null, true);
        }

        if (isset($request['from_methodology'])) {
            if (isset($request['send_for_approval'])) { // remove 'methodology' from the end, needs to go back to  e.g. /vtram/{id}
                return str_replace("/methodology", "", $request['return_path']);
            }
            return $request['return_path'];
        }

        if (isset($request['back_to_edit'])) {
            return $this->fullPath.'/edit';
        }
    }

    public function created($insert, $request, $args)
    {
        VTLogic::createDefaultMethodologies($insert, "TEMPLATE");
        VTLogic::createPdf($insert, null, true);
    }

    public function commentsList()
    {
        $args = func_get_args();
        $id = end($args);
        $userCompany = Auth::User()->company_id;
        $record = Template::findOrFail($id);

        if (!is_null($userCompany)) {
            if ($userCompany != $record->company_id) {
                abort(404);
            }
        }

        $this->view = 'modules.company.project.vtram.comment.display';
        $this->heading = 'Viewing All Comments';
        $this->customValues['comments'] = VTLogic::getComments($record, null, "TEMPLATE");

        $url = \URL::current();
        $this->backButton = [
            'path' => str_replace("/comment", "", $url),
            'label' => 'Back to Template',
            'icon' => 'arrow-left',
        ];
        parent::_buildProperties($args);
        return parent::_renderView("layouts.custom");
    }
}
