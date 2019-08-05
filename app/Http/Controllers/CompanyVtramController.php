<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use EGFiles;
use Controller;
use Route;
use App\Icon;
use App\Vtram;
use App\Hazard;
use App\Project;
use App\Company;
use App\Template;
use App\Approval;
use App\TableRow;
use App\NextNumber;
use App\Instruction;
use App\Methodology;
use App\Http\Classes\VTLogic;
use Illuminate\Http\Request;
use App\Http\Requests\VtramRequest;

class CompanyVtramController extends Controller
{
    protected $identifierPath = 'company.project.vtram';

    public function postIndexHook()
    {
        $this->customValues['templates'] = Template::where('company_id', $this->args[0])->pluck('name', 'id');
        if (isset($this->actionButtons['create']['class'])) {
            $this->actionButtons['create']['class'] .= " create_vtram";
        }
        $this->customValues['path'] = 'vtram/create';
    }

    public function postBladeHook()
    {
        if (isset($this->actionButtons['create']['class'])) {
            $this->actionButtons['create']['class'] .= " create_vtram";
        }
    }

    public function bladeHook()
    {
        $this->customValues['templates'] = Template::where('company_id', $this->args[0])->pluck('name', 'id');
    }

    public function createHook()
    {
        if (isset($_GET['template'])) {
            $template = Template::findOrFail($_GET['template']);
            $this->customValues['createdFromEntity'] = "TEMPLATE";
            $this->customValues['createdFromId'] = $_GET['template'];

            if ($template->company_id != $this->args[0]) {
                abort(404);
            }
            $this->customValues['name'] = $template['name'];
            $this->customValues['reference'] = $template['reference'];
            $this->customValues['logo'] = $template['logo'];
        }

        $company = Company::findOrFail($this->args[0]);
        $this->customValues['main_description'] = $company['main_description'];
        $this->customValues['post_risk_assessment_text'] = $company['post_risk_assessment_text'];
        $this->customValues['task_description'] = $company['task_description'];
        $this->customValues['plant_and_equipment'] = $company['plant_and_equipment'];
        $this->customValues['disposing_of_waste'] = $company['disposing_of_waste'];
        $this->customValues['first_aid'] = $company['first_aid'];
        $this->customValues['noise'] = $company['noise'];
        $this->customValues['working_at_height'] = $company['working_at_height'];
        $this->customValues['manual_handling'] = $company['manual_handling'];
        $this->customValues['accident_reporting'] = $company['accident_reporting'];
    }

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
        $company = $this->args[0];
        $project = $this->args[1];
        $this->customValues['path'] = '/company/'.$company.'/project/'.$project.'/vtram/create';
    }

    public function viewHook()
    {
        if (in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECT'])) {
            $this->actionButtons['methodologies'] = [
                'label' => 'Edit Hazards & Methodologies',
                'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/'.$this->id.'/methodology',
                'icon' => 'receipt',
                'order' => '500',
                'id' => 'methodologyEdit',
            ];
        }
        $prevConfig = config('structure.company.project.vtram.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '500',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.company.project.vtram.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];

        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, "VTRAM");
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECT'])) {
            $this->disableEdit = true;
        }
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
        if ($this->record->status != "NEW") {
            $this->pillButtons['view_comments'] = [
                'label' => 'View All Comments',
                'path' => $this->record->id.'/comment',
                'icon' => 'comment',
                'order' => 100,
                'id' => 'view_comments',
            ];
        }

        if (strpos($this->identifierPath, 'previous') === false) {
            if ($this->record->created_from_entity == 'TEMPLATE' && $this->record->created_from_id != null) {
                $this->pillButtons['view_created_from'] = [
                    'label' => 'View Template',
                    'path' => '/template/'.$this->record->created_from_id,
                    'icon' => 'call_missed',
                    'order' => 100,
                    'id' => 'view_created_from',
                ];
            } else if ($this->record->created_from_entity == 'VTRAMS' && $this->record->created_from_id != null) {
                $this->pillButtons['view_created_from'] = [
                    'label' => 'View VTRAMS Created From',
                    'path' => $this->record->id.'/previous/'.$this->record->created_from_id,
                    'icon' => 'call_missed',
                    'order' => 100,
                    'id' => 'view_created_from',
                ];
            }
        }

        if (VTLogic::canReview($this->record)) {
            if ($this->record->pages_in_pdf == 4) {
                $path = 'javascript: window.open("'.$this->record->id.'/view_a3", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
            } else {
                $path = 'javascript: window.open("'.$this->record->id.'/view_a4", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
            }
            $this->pillButtons['approve_vtrams'] = [
                'label' => 'Approve VTRAMS',
                'path' => $path,
                'icon' => 'playlist_add_check',
                'id' => 'approve_vtrams',
            ];
        }

        $vtrams = Vtram::where('created_from_entity', '=', 'VTRAM')
            ->where('created_from_id', '=', $this->record->id)
            ->get();
        if ($this->record->status == 'CURRENT' && $vtrams->count() == 0) {
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
        $this->record = Vtram::findOrFail($id);
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            abort(404);
        }
        return parent::_edit(func_get_args());
    }

    public function submitForApproval($companyId, $projectId, $vtramId)
    {
        $user = Auth::user();
        $vtram = Vtram::findOrFail($vtramId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $vtram->company_id) {
                abort(404);
            }
        }
        VTLogic::submitForApproval($vtram);
        if ($this->identifierPath == 'company.project.vtram') {
            $url = '/company/'.$companyId.'/project/'.$projectId.'/vtram/'.$vtramId;
        } else {
            $url = '/project/'.$projectId.'/vtram/'.$vtramId;
        }
        toast()->success("VTRAMS submitted for Approval");
        return redirect($url);
    }

    public function viewA3($companyId, $projectId, $vtramId, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $vtram = Vtram::findOrFail($vtramId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $vtram->company_id) {
                abort(404);
            }
        }
        if ($vtram->pages_in_pdf == 4) {
            return VTLogic::createA3Pdf($vtram);
        }
        return VTLogic::createPdf($vtram);
    }

    public function viewA4($companyId, $projectId, $vtramId, $otherId = null)
    {
        $user = Auth::user();
        if ($companyId == null) {
            $companyId = $user->companyId;
        }
        $vtram = Vtram::findOrFail($vtramId);
        if ($user->company_id !== null) {
            if ($user->company_id !== $vtram->company_id) {
                abort(404);
            }
        }
        return VTLogic::createPdf($vtram);
    }

    public function store(VtramRequest $request, $companyId, $projectId)
    {
        $request->merge([
            'project_id' => $projectId,
            'company_id' => $companyId,
            'created_by' => Auth::id(),
        ]);

        return parent::_store(func_get_args());
    }

    public function update(VtramRequest $request)
    {
        $request->merge([
            'updated_by' => Auth::id(),
        ]);
        return parent::_update(func_get_args());
    }

    public function updateFromMethodology(Request $request)
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
        $vtramId = end($this->args);
        $this->record = Vtram::findOrFail($vtramId);
        $this->parentId = $this->record->project_id;
        $this->id = $this->record->id;
        if ($this->record->status != 'CURRENT') {
            abort(404);
        }
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->project->company_id) {
                abort(404);
            }
        }

        // Check there are no existing revisions
        $vtrams = Vtram::where('created_from_entity', '=', 'VTRAM')
            ->where('created_from_id', '=', $this->record->id)
            ->get();
        if ($vtrams->count() > 0) {
            abort(404);
        }

        $result = VTLogic::copyEntity($this->record);

        if ($result instanceof Vtram) {
            $this->_buildProperties($this->args);
            toast()->success("Revision Created, you're now viewing the new VTRAMS");
            return redirect($this->parentPath.'/'.$result->id);
        }
        toast()->error('Failed to create new Revision');
        return back();
    }

    public function editContent($companyId, $projectId, $vtramId)
    {
        $this->record = Vtram::findOrFail($vtramId);
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            abort(404);
        }
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->project->company_id) {
                abort(404);
            }
        }
        $company = Company::findOrFail($companyId);
        $this->view = 'modules.company.project.vtram.editVtram';
        $this->parentId = $vtramId;
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $this->customValues['methTypeList'] = config('egc.methodology_list');
        $this->customValues['riskList'] = [
            0 => $company->no_risk_character,
            1 => $company->low_risk_character,
            2 => $company->med_risk_character,
            3 => $company->high_risk_character,
        ];
        $this->customValues['hazards'] = Hazard::where('entity', '=', 'VTRAM')
            ->where('entity_id', '=', $vtramId)
            ->orderBy('list_order')
            ->get();
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'VTRAM')
            ->where('entity_id', '=', $vtramId)
            ->orderBy('list_order')
            ->get();

        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, "VTRAM");
        $this->customValues['entityType'] = 'VTRAM';

        // Start of Methodology Specific Items
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
        $this->args = func_get_args();
        $this->id = $vtramId;
        $this->parentId = $projectId;
        parent::setup();
        parent::_buildProperties($this->args);
        $this->backButton = [
            'path' => $this->parentPath.'/'.$vtramId,
            'label' => 'Back to VTRAMS',
            'icon' => 'arrow-left',
        ];
        $this->pageType = "custom";
        return parent::_renderView("layouts.custom");
    }

    public function created($insert, $request, $args)
    {
        $nextNumber = NextNumber::where('company_id', '=', $insert->company_id)
            ->first();
        if (is_null($nextNumber)) {
            $nextNumber = NextNumber::create([
                'company_id' => $insert->company_id,
                'number' => 1,
            ]);
        }
        $insert->update([
           'number' => $nextNumber->number,
        ]);

        if (isset($insert['created_from_id'])) {
            $templateId = $insert['created_from_id'];
            $selects = ['entity' => 'TEMPLATE', 'entity_id' => $templateId];

            $original = Template::findOrFail($templateId);

            // copy everything!
            VTLogic::copyEntity($original, $insert);
        }

        $nextNumber->increment('number');

        VTLogic::createDefaultMethodologies($insert, "VTRAM");
        VTLogic::createPdf($insert, null, true);
    }

    public function unsetItems($record)
    {
        unset($record['id']);
        unset($record['created_at']);
        unset($record['updated_at']);
        return $record;
    }

    public function updated($update, $orig, $request, $args)
    {
        if (isset($request['send_for_approval'])) {
            VTLogic::submitForApproval($update);
            toast()->success("VTRAM submitted for Approval");
        } else {
            VTLogic::createPdf($update, null, true);
        }

        if (isset($request['from_methodology'])) {
            return $request['return_path'];
        }
    }

    public function commentsList()
    {
        $args = func_get_args();
        $id = end($args);
        $userCompany = Auth::User()->company_id;
        $record = Vtram::findOrFail($id);

        if (!is_null($userCompany)) {
            if ($userCompany != $record->company_id) {
                abort(404);
            }
        }

        $this->view = 'modules.company.project.vtram.comment.display';
        $this->heading = 'Viewing All Comments';
        $this->customValues['comments'] = VTLogic::getComments($record, null, "VTRAM");
        return parent::_custom();
    }
}
