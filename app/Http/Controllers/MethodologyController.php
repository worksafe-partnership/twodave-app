<?php

namespace App\Http\Controllers;

use Controller;
use App\Methodology;
use App\Http\Requests\MethodologyRequest;

class MethodologyController extends Controller
{
    protected $identifierPath = 'company.project.vtram.methodology';
    
    public function store(MethodologyRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(MethodologyRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
