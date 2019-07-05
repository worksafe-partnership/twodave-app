<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class PreviousVtramController extends CompanyPreviousVtramController
{
    protected $identifierPath = 'project.vtram.previous';

    public function __construct() 
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }

    public function viewHook()
    {

    }

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
        if ($this->user->company_id !== null) {
            if ($this->user->company_id !== $this->record->project->company_id) {
                abort(404);
            }
        }
    }
}
