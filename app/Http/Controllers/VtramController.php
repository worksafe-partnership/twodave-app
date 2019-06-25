<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class VtramController extends Controller
{
    protected $identifierPath = 'company.project.vtram';

    public function bladeHook()
    {
        $project = Project::findOrFail($this->parentId);
        $this->customValues['projects'] = Project::where('company_id', '=' , $project->company_id)
            ->pluck('name', 'id');
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
