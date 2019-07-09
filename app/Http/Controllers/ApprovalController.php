<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Approval;
use App\Http\Requests\ApprovalRequest;

class ApprovalController extends CompanyApprovalController
{
    protected $identifierPath = 'project.vtram.approval';

    public function indexHook()
    {
        if ($this->user->company_id !== null) {
            $vtram = Vtram::with('project')
                ->findOrFail($this->parentId);
            if ($this->user->company_id !== $vtram->project->company_id) {
                abort(404);
            }
        }
    }

    public function bladeHook()
    {
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->vtram->project->company_id) {
                abort(404);
            }
        }
        if ($this->user->inRole('supervisor') && $this->record !== null) {
            if (!$this->record->vtram->project->userOnProject($this->user->id)) {
                abort(404);
            }        
        }
    }
}
