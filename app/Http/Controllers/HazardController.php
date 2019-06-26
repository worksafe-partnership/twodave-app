<?php

namespace App\Http\Controllers;

use Controller;
use App\Hazard;
use App\Http\Requests\HazardRequest;

class HazardController extends Controller
{
    protected $identifierPath = 'company.project.vtram.hazard';
    
    public function store(HazardRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(HazardRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
