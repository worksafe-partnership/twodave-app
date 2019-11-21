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
            if ($this->vtconfig->entityType == 'VTRAM') {
                if ($this->user->company_id !== $this->vtconfig->entity->project->company_id) {
                    abort(404);
                }
            } else {
                if ($this->user->company_id !== $this->vtconfig->entity->company_id) {
                    abort(404);
                }
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
        if (in_array($approval->type, ['A'])) {
            if ($this->vtconfig->entityType == 'VTRAM' && $this->vtconfig->entity->project->principle_contractor) {
                $this->vtconfig->entity->update([
                    'status' => 'AWAITING_EXTERNAL',
                    'approved_date' => date('Y-m-d'),
                    'approved_by' => $this->user->id,
                    'resubmit_by' => null,
                ]);
                VTLogic::sendPcApprovalEmail($this->vtconfig);
                $approval->update([
                    'type' => 'AC',
                ]);
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
                                'resubmit_by' => null,
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
                            'resubmit_by' => null,
                        ]);
                    }
                }
                $this->vtconfig->entity->update([
                    'status' => 'CURRENT',
                    'approved_date' => date('Y-m-d'),
                    'approved_by' => $this->user->id,
                    'revision_number' => $revisionNumber,
                    'resubmit_by' => null,
                ]);
            }
        } else if ($approval->type == 'R') {
            $this->vtconfig->entity->update([
                'status' => 'REJECTED',
                'resubmit_by' => $request['resubmit_date'],
            ]);
        } else if ($approval->type == 'AC') {
            $this->vtconfig->entity->update([
                'status' => 'AMEND',
                'resubmit_by' => $request['resubmit_date'],
            ]);
        } else if (in_array($approval->type, ['PC_A'])) {
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
            $this->vtconfig->entity->update($update);
        } else if ($approval->type == 'PC_R') {
            $this->vtconfig->entity->update([
                'status' => 'EXTERNAL_REJECT',
                'resubmit_by' => $request['resubmit_date'],
            ]);
        } else if ($approval->type == 'PC_AC') {
            $this->vtconfig->entity->update([
                'status' => 'EXTERNAL_AMEND',
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
            if ($type == 'VTRAMS') {
                if ($this->user->company_id !== $this->customValues['entity']->project->company_id) {
                    abort(404);
                }
            } else {
                if ($this->user->company_id !== $this->customValues['entity']->company_id) {
                    abort(404);
                }
            }
        }
        $this->view = 'modules.company.project.vtram.approveVtram';
        $company = Company::findOrFail($this->user->company_id ?? $this->customValues['entity']->company_id);
        $this->customValues['company'] = $company;

        if (($this->user->company_id == $company->id || is_null($user->company)) && $company->is_principal_contractor && $this->customValues['entity']->status == 'AWAITING_EXTERNAL') {
            $types = config('egc.pc_approval_type');
            $types['PC_A'] = $company->accept_label;
            $types['PC_AC'] = $company->amend_label;
            $types['PC_R'] = $company->reject_label;
        } else {
            $types = config('egc.approval_type');
            $types['A'] = $company->accept_label;
            $types['AC'] = $company->amend_label;
            $types['R'] = $company->reject_label;
        }
        if ($type == 'VTRAMS' && $this->customValues['entity']->project->principle_contractor == 0) {
            unset($types['AC-NS']);
        }
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
        if ($this->record->entity_id != $this->parentId) {
            abort(404);
        }
    }

    public function view()
    {
        return parent::_view(func_get_args());
    }

    public function viewHook()
    {
        $path = '';
        switch ($this->identifierPath) {
            case 'company.template.approval':
                $path = '/company/'.$this->args[0].'/template/'.$this->parentId.'/methodology';
                break;
            case 'template.approval':
                $path = '/template/'.$this->parentId.'/methodology';
                break;
            case 'company.project.vtram.approval':
                $path = '/company/'.$this->args[0].'/project/'.$this->args[1].'/vtram/'.$this->parentId.'/methodology';
                break;
            case 'project.vtram.approval':
                $path = '/project/'.$this->args[0].'/vtram/'.$this->parentId.'/methodology';
                break;
        }
        if (strpos($this->identifierPath, 'template') !== false) {
            $record = Template::withTrashed()->findOrFail($this->parentId);
        } else {
            $record = Vtram::withTrashed()->findOrFail($this->parentId);
        }
    }
}
