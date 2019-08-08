<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Icon;
use Controller;
use App\Hazard;
use App\Company;
use App\TableRow;
use App\Template;
use App\Instruction;
use App\Methodology;
use Illuminate\Http\Request;
use App\Http\Classes\VTLogic;
use App\Http\Requests\TemplateRequest;
use App\Http\Requests\EditVtramRequest;

class TemplateController extends Controller
{
    protected $identifierPath = 'template';

    public function postEditHook()
    {
        if (in_array($this->record->status, ['REJECTED','EXTERNAL_REJECT','NEW'])) {
            $this->formButtons['save_and_submit'] = [
                'class' => [
                    'submitbutton',
                    'button',
                    'is-primary',
                ],
                'name' => 'send_for_approval',
                'label' => 'Update and Submit for Approval',
                'order' => 300,
                'value' => true,
            ];
        }
    }

    public function viewHook()
    {
        if (can('edit', $this->identifierPath) && in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            $this->actionButtons['methodologies'] = [
                'label' => 'Edit Hazards & Methodologies',
                'path' => '/template/'.$this->id.'/methodology',
                'icon' => 'receipt',
                'order' => '500',
                'id' => 'methodologyEdit',
            ];
        }

        $prevConfig = config('structure.template.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/template/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '500',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.template.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/template/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            $this->disableEdit = true;
        }
    }

    public function bladeHook()
    {
        $this->customValues['companies'] = Company::pluck('name', 'id');
    }

    public function store(TemplateRequest $request)
    {
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

    public function editContent($templateId, $otherId = null)
    {
        $this->record = Template::findOrFail($templateId);
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            abort(404);
        }
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $this->record !== null && $this->record->company_id !== null) {
            if ($this->user->company_id !== $this->record->company_id) {
                abort(404);
            }
        }
        if (is_null($otherId)) {
            $otherId = $this->user->company_id;
        }

        $company = Company::find($otherId);
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
        $this->view = 'modules.company.project.vtram.editVtram';
        $this->parentId = $templateId;
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $this->customValues['methTypeList'] = config('egc.methodology_list');
        $this->customValues['hazards'] = Hazard::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $templateId)
            ->orderBy('list_order')
            ->get();
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $templateId)
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

        $this->args = func_get_args();
        $this->id = $templateId;
        $this->parentId = $otherId;
        parent::setup();
        parent::_buildProperties($this->args);
        if ($this->parentPath == '') {
            $this->parentPath = '/template';
        }
        $this->backButton = [
            'path' => $this->parentPath.'/'.$templateId,
            'label' => 'Back to Template',
            'icon' => 'arrow-left',
        ];
        $this->pageType = 'custom';
        return parent::_renderView("layouts.custom");
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
        if ($this->record->pages_in_pdf == 4) {
            $this->pillButtons['view_pdf_a3'] = [
                'label' => 'View PDF A3',
                'path' => $this->record->id.'/view_a3',
                'icon' => 'file-pdf',
                'order' => 100,
                'id' => 'view_pdf_a3',
                'target' => '_blank',
            ];
            $this->pillButtons['print_pdf_a3'] = [
                'label' => 'Print PDF A3',
                'path' => "javascript: var wnd = window.open('".$this->record->id."/view_a3', '_blank');wnd.print();",
                'icon' => 'print',
                'order' => 100,
                'id' => 'print_pdf_a3',
                'target' => '_blank',
            ];
        }

        $this->pillButtons['view_pdf'] = [
            'label' => 'View PDF',
            'path' => $this->record->id.'/view_a4',
            'icon' => 'file-pdf',
            'order' => 100,
            'id' => 'view_pdf',
            'target' => '_blank',
        ];
        $this->pillButtons['print_pdf'] = [
            'label' => 'Print PDF',
            'path' => "javascript:var wnd = window.open('".$this->record->id."/view_a4', '_blank');wnd.print();",
            'icon' => 'print',
            'order' => 100,
            'id' => 'print_pdf',
            'target' => '_blank',
        ];
        if (in_array($this->record->status, ['NEW','REJECTED','EXTERNAL_REJECT'])) {
            $this->pillButtons['submit_for_approval'] = [
                'label' => 'Submit for Approval',
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

        if ($this->record->status != "NEW") {
            $this->pillButtons['view_comments'] = [
                'label' => 'View All Comments',
                'path' => $this->record->id.'/comment',
                'icon' => 'comment',
                'order' => 100,
                'id' => 'view_comments',
            ];
        }

        if (VTLogic::canReview($this->record)) {
            if ($this->record->pages_in_pdf == 4) {
                $path = 'javascript: window.open("'.$this->record->id.'/view_a3", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
            } else {
                $path = 'javascript: window.open("'.$this->record->id.'/view_a4", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
            }
            $this->pillButtons['approve_template'] = [
                'label' => 'Approve Template',
                'path' => $path,
                'icon' => 'playlist_add_check',
                'id' => 'approve_template',
            ];
        }

        $template = Template::where('created_from', '=', $this->record->id)
            ->get();
        if ($this->record->status == 'CURRENT' && $template->count() == 0) {
            $this->actionButtons[] = [
                'label' => 'Create New Revision',
                'path' => $this->record->id.'/revision',
                'icon' => 'new-tab',
                'order' => '200',
                'id' => 'create_new_revision',
            ];
        }
    }

    public function edit()
    {
        $this->args = func_get_args();
        $id = end($this->args);
        $this->record = Template::findOrFail($id);
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
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
        toast()->success("Template submitted for Approval");
        return redirect($url);
    }

    public function viewA3($templateId, $companyId = null, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $template = Template::findOrFail($templateId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $template->company_id && $template->company_id !== null) {
                abort(404);
            }
        }
        if ($templates->pages_in_pdf == 4) {
            return VTLogic::createA3Pdf($template);
        }
        return VTLogic::createPdf($template);
    }

    public function viewA4($templateId, $companyId = null, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $template = Template::findOrFail($templateId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $template->company_id && $template->company_id !== null) {
                abort(404);
            }
        }
        return VTLogic::createPdf($template);
    }

    public function updated($update, $orig, $request, $args)
    {
        if (isset($request['send_for_approval'])) {
            VTLogic::submitForApproval($update);
            toast()->success("Template submitted for Approval");
        } else {
            VTLogic::createPdf($update, null, true);
        }

        if (isset($request['from_methodology'])) {
            return $request['return_path'];
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
        return parent::_custom();
    }
}
