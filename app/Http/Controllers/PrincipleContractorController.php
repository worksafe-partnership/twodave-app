<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Vtram;
use App\Company;
use App\UniqueLink;
use App\Http\Classes\VTLogic;
use App\Http\Requests\ApprovalRequest;
use Illuminate\Http\Request;

class PrincipleContractorController extends Controller
{
    public function __construct()
    {
        if (Auth::user() != null) {
//          abort(404);
        }
    }

    public function vtramsList()
    {

    }

    public function viewVtrams($uniqueLink, $id)
    {
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

        $this->view = 'modules.company.project.vtram.display';
        $this->pageType = 'view';
        $this->record = $vtrams;
        if ($this->record->pages_in_pdf == 4) {
            $path = 'javascript: window.open("'.$this->record->id.'/view_a3", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
        } else {
            $path = 'javascript: window.open("/image/'.$this->record->pdf.'", "_blank");window.open("'.$this->record->id.'/approve", "_self");window.focus();';
        }
        $this->actionButtons['approve_vtrams'] = [
            'label' => 'Approve VTRAMS',
            'path' => $path,
            'icon' => 'playlist_add_check',
            'id' => 'approve_vtrams',
            'order'=> 100,
        ];
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;

        parent::setup();
        parent::_buildProperties();
        return parent::_renderView("layouts.custom");
    }

    public function viewApproval($uniqueLink, $id)
    {
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
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

        return parent::_custom();
    }

    public function store(ApprovalRequest $request, $uniqueLink, $id)
    {
        dd(func_get_args());
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

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
        if (in_array($approval->type, ['PC_A','PC_AC'])) {
            $this->vtconfig->entity->update([
                'status' => 'CURRENT',
                'approved_date' => date('Y-m-d'),
                'approved_by' => $this->user->id,
            ]);
        } else if ($approval->type == 'PC_R') {
            $this->vtconfig->entity->update([
                'status' => 'EXTERNAL_REJECT',
                'resubmit_by' => $request['resubmit_date'],
            ]);
        }
        return $this->parentPath;
    }

    public function view($uniqueLink, $id)
    {
        $link = UniqueLink::where('unique_link', '=', $uniqueLink)
            ->firstOrFail();
        $vtrams = Vtram::where('id', '=', $id)
            ->whereHas('project', function ($q) use ($link) {
                return $q->where('principle_contractor_email', '=', $link->email);
            })
            ->where('status', '=', 'AWAITING_EXTERNAL')
            ->firstOrFail();

        if ($vtrams->pages_in_pdf == 4) {
            return VTLogic::createA3Pdf($vtrams);
        }
        return VTLogic::createPdf($vtrams);
    }
}
