<?php

namespace App\Http\Controllers;

use Controller;
use App\Template;
use App\Company;
use App\Http\Requests\TemplateRequest;

class PreviousTemplateController extends TemplateController
{
    protected $identifierPath = 'template.previous';
    
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
        $approvalConfig = config('structure.template.previous.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/template/'.$this->parentId.'/previous/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
        $this->pillButtons['view_comments'] = [
            'label' => 'View All Comments',
            'path' => $this->record->id.'/comment',
            'icon' => 'comment',
            'order' => '600',
            'id' => 'view_comments',
        ];
        $this->customValues['companyId'] = $this->record->company_id;
    }

    public function store(TemplateRequest $request, $companyId = null)
    {
        return parent::_store(func_get_args());
    }

    public function update(TemplateRequest $request, $companyId, $templateId = null)
    {
        return parent::_update(func_get_args());
    }

    public function viewA3($parentTemplateId, $templateId = null, $companyId = null)
    {
        return parent::viewA3($templateId); 
    }

    public function viewA4($parentTemplateId, $templateId = null, $companyId = null)
    {
        return parent::viewA4($templateId); 
    }
}
