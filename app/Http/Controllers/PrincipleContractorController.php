<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Vtram;
use App\Company;
use App\UniqueLink;
use App\Http\Classes\VTLogic;
use App\Http\Classes\VTConfig;
use App\Http\Requests\ApprovalRequest;
use Illuminate\Http\Request;

class PrincipleContractorController extends Controller
{
    protected $identifierPath = 'company.project.vtram.approval';

    public function vtramsList($uniqueLink)
    {
        if (Auth::user()) {
            abort(404);
        }
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $this->baseLink = '/'.$link->unique_link.'/vtrams';
        $this->datatable['data'] = "vtrams/vtrams.datatable.json";
        $this->datatable['href'] = "vtrams/%ID%";
        $this->datatable['name'] = 'vtrams-approval';
        $this->datatable['columns'] = [
            'id' => ['visible' => false, 'searchable' => false, 'label' => 'Id'],
            'company_id' => ['label' => 'Company'],
            'number' => ['label' => 'VTRAMS Number'],
            'name' => ['label' => 'Name'],
            'reference' => ['label' => 'Reference'],
            'status' => ['label' => 'Status'],
            'approved_date' => [ 'label' => 'Approved Date', 'col_type' => 'date', ],
            'external_approval_date' => [ 'label' => 'External Approved Date', 'col_type' => 'date', ],
            'review_due' => [ 'label' => 'Review Due', 'col_type' => 'date', ],
            'revision_number' => ['label' => 'Revision Number'],
            'submitted_by' => ['label' => 'Submitted By'],
            'approved_by' => ['label' => 'Approved By'],
            'resubmit_by' => [ 'label' => 'Resubmit By', 'col_type' => 'date', ],
        ];
        $this->datatable['serverSide'] = "false";
        return parent::_index();
    }

    public function postIndexHook()
    {
        $this->sidebarLinks = [];
        $this->sidebarLinks[] = [
            'label' => 'VTRAMS',
            'order' => 1,
            'identifier_path' => $this->identifierPath,
            'extra' => '',
            'current' => true,
            'url' => $this->baseLink,
            'icon' => 'document-add',
        ];
        $this->backButton = null;
        $this->breadcrumbs = null;
        $this->heading = 'VTRAMS';
        $this->config['singular'] = 'VTRAMS';
    }

    public function _datatableAll($uniqueLink = null)
    {
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $this->model = '\App\Vtram';
        $this->setup();

        $args = func_get_args();
        $this->parentId = end($args);

        return $this->model::pcDatatableAll($this->parentId, $this->config, $link->email);
    }

    public function viewVtrams($uniqueLink, $id)
    {
        if (Auth::user()) {
            abort(404);
        }
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $this->baseLink = '/'.$link->unique_link.'/vtrams';
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

        $this->view = 'modules.company.project.vtram.display';
        $this->pageType = 'view';
        $this->record = $vtrams;
        // another example where it needs to open in A4 but actually print in A3.
        $path = 'javascript: window.open("'.$this->record->id.'/view_a3", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';

        $this->actionButtons['approve_vtrams'] = [
            'label' => 'Approve / Amend / Reject VTRAMS',
            'path' => $path,
            'icon' => 'playlist_add_check',
            'id' => 'approve_vtrams',
            'class' => 'is-success',
            'order'=> 100,
        ];
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;

        parent::setup();
        parent::_buildProperties();
        $this->sidebarLinks = [];
        $this->sidebarLinks[] = [
            'label' => 'VTRAMS',
            'order' => 1,
            'identifier_path' => $this->identifierPath,
            'extra' => '',
            'current' => true,
            'url' => $this->baseLink,
            'icon' => 'document-add',
        ];
        $this->heading = 'Viewing VTRAMS';
        $this->backButton = [
            'path' => $this->baseLink,
            'label' => 'Back to VTRAMS',
            'icon' => 'arrow-left',
        ];
        $this->breadcrumbs = null;
        $this->customValues['associatedUsers'] = [];
        $this->customValues['projectUsers'] = [];
        return parent::_renderView("layouts.custom");
    }

    public function viewApproval($uniqueLink, $id)
    {
        if (Auth::user()) {
            abort(404);
        }
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $this->baseLink = '/'.$link->unique_link.'/vtrams';
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

        $this->view = 'modules.company.project.vtram.approveVtram';
        $this->heading = "Approving ".$vtrams->name." VTRAMS";
        $company = Company::findOrFail($vtrams->company_id);
        $this->customValues['company'] = $company;
        $types = config('egc.pc_approval_type');
        $types['PC_A'] = $company->accept_label;
        $types['PC_AC'] = $company->amend_label;
        $types['PC_R'] = $company->reject_label;
        $this->customValues['approvalTypes'] = $types;
        $this->customValues['entity'] = $vtrams;
        $this->pageType = 'custom';
        $this->submitButtonText = 'Create VTRAMS Approval';

        parent::setup();
        parent::_buildProperties();
        $this->sidebarLinks = [];
        $this->sidebarLinks[] = [
            'label' => 'VTRAMS',
            'order' => 1,
            'identifier_path' => $this->identifierPath,
            'extra' => '',
            'current' => true,
            'url' => $this->baseLink,
            'icon' => 'document-add',
        ];
        $this->cancelPath = $this->baseLink.'/'.$id;
        $this->breadcrumbs = null;
        return parent::_renderView("layouts.custom");
    }

    public function store(ApprovalRequest $request, $uniqueLink, $id)
    {
        if (Auth::user()) {
            abort(404);
        }
        $this->link = $uniqueLink;
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();
        $this->totalVtrams = Vtram::where('id', '!=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->count();
        $this->vtconfig = new VTConfig($vtrams);
        $this->redirectPath = '/'.$uniqueLink.'/vtrams';

        $request->merge([
            'entity' => 'VTRAM',
            'entity_id' => $vtrams->id,
            'completed_by' => $vtrams->project->principle_contractor_name,
            'approved_date' => date('Y-m-d'),
            'status_at_time' => $vtrams->status,
        ]);
        return parent::_store(func_get_args());
    }

    public function created($approval, $request, $args)
    {
        if (in_array($approval->type, ['PC_A'])) {
            $revisionNumber = 1;
            if ($this->vtconfig->entityType == 'VTRAM') {
                if ($this->vtconfig->entity->created_from_entity == 'VTRAM') {
                    $vtram = Vtram::find($this->vtconfig->entity->created_from_id);
                    if ($vtram != null && $vtram->revision_number != null) {
                        $revisionNumber = $vtram->revision_number + 1;
                        $vtram->update([
                            'status' => 'PREVIOUS',
                            'date_replaced' => date('Y-m-d'),
                        ]);
                    }
                }
            } else {
                $template = Template::find($this->vtconfig->entity->created_from);
                if ($template != null && $template->revision_number != null) {
                    $revisionNumber = $template->revision_number + 1;
                    $template->update([
                        'status' => 'PREVIOUS',
                        'date_replaced' => date('Y-m-d'),
                    ]);
                }
            }
            $update = [
                'status' => 'CURRENT',
                'approved_date' => date('Y-m-d'),
                'revision_number' => $revisionNumber,
            ];

            // if accept, remove resubmit by date.
            if ($approval->type == 'PC_A') {
                $update['resubmit_by'] = null;
            }

            if ($this->vtconfig->entity->project != null && $this->vtconfig->entity->project->review_timescale != 0) {
                $update['review_due'] = date('Y-m-d', strtotime('+'.$this->vtconfig->entity->project->review_timescale.' month'));
            } else if ($this->vtconfig->entity->company->review_timescale != 0) {
                $update['review_due'] = date('Y-m-d', strtotime('+'.$this->vtconfig->entity->company->review_timescale.' month'));
            }

            $this->vtconfig->entity->update($update);

        } else if ($approval->type == 'PC_R') {
            $this->vtconfig->entity->update([
                'status' => 'EXTERNAL_REJECT',
                'resubmit_by' => $request['resubmit_date'],
                'pc_submitted' => null,
            ]);
        } else if ($approval->type == 'PC_AC') {
            $this->vtconfig->entity->update([
                'status' => 'EXTERNAL_AMEND',
                'resubmit_by' => $request['resubmit_date'],
                'pc_submitted' => null,
            ]);
        }
        if ($this->totalVtrams == 0) {
            UniqueLink::where('unique_link', '=', $this->link)
                ->delete();
            toast()->success('Successfully Reviewed VTRAMS, there are no more VTRAMS to review');
            return '/login';
        }
        return $this->redirectPath;
    }

    public function view($uniqueLink, $id)
    {
        if (Auth::user()) {
            abort(404);
        }
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

        if (in_array($vtrams->pages_in_pdf, [2,3,4])) {
            return VTLogic::createA3Pdf($vtrams);
        }
        return VTLogic::createPdf($vtrams);
    }

    public function viewA4($uniqueLink, $id)
    {
        if (Auth::user()) {
            abort(404);
        }
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

        return VTLogic::createPdf($vtrams);
    }
}
