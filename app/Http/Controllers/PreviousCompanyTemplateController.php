<?php

namespace App\Http\Controllers;

use Controller;
use App\Template;
use App\Company;
use App\Http\Requests\TemplateRequest;

class PreviousCompanyTemplateController extends CompanyTemplateController
{
    protected $identifierPath = 'company.template.previous';
    
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
        $approvalConfig = config('structure.company.template.previous.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/company/'.$this->args[0].'/template/'.$this->parentId.'/previous/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
    }

    public function store(TemplateRequest $request, $companyId = null)
    {
        return parent::_store(func_get_args());
    }

    public function update(TemplateRequest $request, $companyId, $templateId = null)
    {
        return parent::_update(func_get_args());
    }
}
