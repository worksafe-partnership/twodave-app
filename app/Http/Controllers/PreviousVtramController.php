<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class PreviousVtramController extends VtramController
{
    protected $identifierPath = 'company.project.vtram.previous';

    public function __construct() 
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }

    public function bladeHook()
    {
        $project = Project::findOrFail($this->parentId);
        $this->customValues['projects'] = Project::where('company_id', '=' , $project->company_id)
            ->pluck('name', 'id');
    }

    public function viewHook()
    {

    }
    
    public function store(VtramRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(VtramRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
