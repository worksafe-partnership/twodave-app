<?php

namespace App\Http\Controllers;

use Controller;
use App\Template;
use App\Company;
use App\Http\Requests\TemplateRequest;

class CompanyTemplateController extends TemplateController
{
    protected $identifierPath = 'company.template';
    
    public function store(TemplateRequest $request, $companyId = null)
    {
        $request->merge([
            'company_id' => $companyId,
        ]);
        return parent::_store(func_get_args());
    }

    public function update(TemplateRequest $request, $companyId, $templateId = null)
    {
        return parent::_update(func_get_args());
    }
}
