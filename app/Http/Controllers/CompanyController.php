<?php

namespace App\Http\Controllers;

use Controller;
use App\Company;
use App\Http\Requests\CompanyRequest;

class CompanyController extends Controller
{
    protected $identifierPath = 'company';
    
    public function store(CompanyRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(CompanyRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
