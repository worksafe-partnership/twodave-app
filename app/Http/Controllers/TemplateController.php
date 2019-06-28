<?php

namespace App\Http\Controllers;

use Controller;
use App\Template;
use App\Company;
use App\Http\Requests\TemplateRequest;

class TemplateController extends Controller
{
    protected $identifierPath = 'template';

    public function bladeHook()
    {
        $this->customValues['companies'] = Company::pluck('name', 'id');
    }
    
    public function store(TemplateRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(TemplateRequest $request, $companyId)
    {
        return parent::_update(func_get_args());
    }
}
