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
                $permittedVTrams = Auth::User()->vtramsCompanyIds();
                if (!is_null($this->parentId) && !in_array($this->parentId, $permittedVTrams)) {
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

    public function view()
    {
        $this->args = func_get_args();
        $this->record = Approval::findOrFail(end($this->args));
        return parent::_view($this->args);
    }

    public function bladeHook()
    {
        $this->heading = "Viewing Approval Feedback";

        if ($this->user->company_id !== null && $this->record !== null) {
            $permittedVTrams = Auth::User()->vtramsCompanyIds();
            if (!is_null($this->parentId) && !in_array($this->parentId, $permittedVTrams)) {
                abort(404);
            }
        }
    }
}
