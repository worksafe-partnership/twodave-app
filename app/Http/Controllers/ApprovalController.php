<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Template;
use App\Approval;
use App\Http\Requests\ApprovalRequest;

class ApprovalController extends CompanyApprovalController
{
    protected $identifierPath = 'project.vtram.approval';

    public function indexHook()
    {
        if ($this->user->company_id !== null) {
            if (strpos($this->identifierPath, "vtram") !== false) {
                $record = Vtram::with('project')
                               ->findOrFail($this->parentId);
                if ($this->user->company_id !== $vtram->project->company_id) {
                    abort(404);
                }
            } else { // template
                $template = Template::findOrFail($this->parentId);
                if ($this->user->company_id !== $template->company_id) {
                    abort(404);
                }

            }
        }
    }

    public function bladeHook()
    {
        $this->heading = "Viewing Approval Feedback";
        if ($this->user->company_id !== null && $this->record !== null) {
            if (strpos($this->identifierPath, "vtram") !== false) {
                if ($this->user->company_id !== $this->record->vtram->project->company_id) {
                    abort(404);
                }
            } else {
                if ($this->user->company_id !== $this->record->vtram->company_id) { // vtram getter returns a template??
                    abort(404);
                }
            }
        }
        if ($this->user->inRole('supervisor') && $this->record !== null) {
            if (!$this->record->vtram->project->userOnProject($this->user->id)) {
                abort(404);
            }
        }
    }
}
