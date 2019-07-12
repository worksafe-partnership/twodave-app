<?php

namespace App\Http\Controllers;

use Controller;
use App\Hazard;
use App\Http\Requests\HazardRequest;

class HazardController extends Controller
{
    protected $identifierPath = 'company.project.vtram.hazard';

    public function store(HazardRequest $request, $companyId, $projectId, $vtramId)
    {
        $request->merge([
            'entity' => 'VTRAM',
            'entity_id' => $vtramId,
        ]);
        parent::_store(func_get_args());
        return "true"; 
    }

    public function update(HazardRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
