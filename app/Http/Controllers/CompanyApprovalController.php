<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Vtram;
use App\Template;
use App\Approval;
use App\Company;
use App\Http\Classes\VTLogic;
use App\Http\Classes\VTConfig;
use App\Http\Requests\ApprovalRequest;

class CompanyApprovalController extends Controller
{
    protected $identifierPath = 'company.project.vtram.approval';

    public function __construct()
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }

    public function store(ApprovalRequest $request)
    {
        $this->args = func_get_args();
        $id = end($this->args);
        if (strpos($this->identifierPath, 'vtram') !== false) {
            $this->vtconfig = new VTConfig($id, 'VTRAM');
        } else {
            $this->vtconfig = new VTConfig($id, 'TEMPLATE');
        }
        if (!VTLogic::canReview($this->vtconfig->entity)) {
            abort(404);
        }
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $this->vtconfig->entity !== null) {
            if ($this->user->company_id !== $this->vtconfig->entity->project->company_id) {
                abort(404);
            }
        }
        $request->merge([
            'entity' => $this->vtconfig->entityType,
            'entity_id' => $this->vtconfig->entityId,
            'completed_by' => $this->user->name,
            'completed_by_id' => $this->user->id,
            'approved_date' => date('Y-m-d'),
            'status_at_time' => $this->vtconfig->entity->status,
        ]);
        return parent::_store(func_get_args());
    }

    public function created($approval, $request, $args)
    {
        if (in_array($approval->type, ['A','AC'])) {
            if ($this->vtconfig->entityType == 'VTRAM' && $this->vtconfig->entity->project->principle_contractor) {
                $this->vtconfig->entity->update([
                    'status' => 'AWAITING_EXTERNAL',
                    'approved_date' => date('Y-m-d'),
                    'approved_by' => $this->user->id,
                ]);
                VTLogic::sendPcApprovalEmail($this->vtconfig);
            } else {
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
                $this->vtconfig->entity->update([
                    'status' => 'CURRENT',
                    'approved_date' => date('Y-m-d'),
                    'approved_by' => $this->user->id,
                    'revision_number' => $revisionNumber,
                ]);
            }
        } else if ($approval->type == 'R') {
            $this->vtconfig->entity->update([
                'status' => 'REJECTED',
                'resubmit_by' => $request['resubmit_date'],
            ]);
        }
        return $this->parentPath;
    }

    public function viewApproval()
    {
        $this->args = func_get_args();
        $id = end($this->args);
        if (strpos($this->identifierPath, 'vtram') !== false) {
            $this->customValues['entity'] = Vtram::findOrFail($id);
            $type = 'VTRAMS';
        } else {
            $this->customValues['entity'] = Template::findOrFail($id);
            $type = 'Template';
        }
        if (!VTLogic::canReview($this->customValues['entity'])) {
            abort(404);
        }
        $this->user = Auth::user();
        if ($this->user->company_id !== null && $this->customValues['entity'] !== null) {
            if ($this->user->company_id !== $this->customValues['entity']->project->company_id) {
                abort(404);
            }
        }
        $this->view = 'modules.company.project.vtram.approveVtram';
        $company = Company::findOrFail($this->user->company_id ?? $this->customValues['entity']->company_id);
        $this->customValues['company'] = $company;
        $types = config('egc.approval_type');
        $types['A'] = $company->accept_label;
        $types['AC'] = $company->amend_label;
        $types['R'] = $company->reject_label;
        $this->customValues['approvalTypes'] = $types;

        $this->args = func_get_args();
        $this->id = $id;
        parent::setup();
        parent::_buildProperties($this->args);
        $path = str_replace('/approve', '', \Request::path());
        $this->backButton = [
            'path' => '/'.$path,
            'label' => 'Back to '.$type,
            'icon' => 'arrow-left',
        ];
        $this->heading = "Viewing Approval Feedback";
        $this->customValues['cancelPath'] = $this->backButton['path'];
        return parent::_renderView("layouts.custom");
    }

    public function viewEditHook()
    {
        $this->heading = "Viewing Approval Feedback";
    }
}
