<?php

namespace App\Http\Controllers;

use Controller;
use App\Briefing;
use App\Vtram;
use App\Http\Requests\BriefingRequest;

class BriefingController extends Controller
{
    protected $identifierPath = 'company.project.briefing';

    public function bladeHook()
    {
        $this->customValues['vtrams'] = Vtram::where('project_id', '=', $this->parentId)
            ->pluck('name', 'id');
    }
    
    public function store(BriefingRequest $request, $companyId, $projectId)
    {
        $request->merge([
            'project_id' => $projectId,
        ]);
        return parent::_store(func_get_args());
    }

    public function update(BriefingRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
