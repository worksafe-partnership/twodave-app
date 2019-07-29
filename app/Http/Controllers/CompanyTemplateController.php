<?php

namespace App\Http\Controllers;

use Controller;
use App\Hazard;
use App\Company;
use App\Template;
use App\Methodology;
use App\Http\Classes\VTLogic;
use App\Http\Requests\TemplateRequest;

class CompanyTemplateController extends TemplateController
{
    protected $identifierPath = 'company.template';

    public function viewHook()
    {
        $this->actionButtons['methodologies'] = [
            'label' => 'Edit Hazards & Methodologies',
            'path' => '/company/'.$this->parentId.'/template/'.$this->id.'/methodology',
            'icon' => 'receipt',
            'order' => '500',
            'id' => 'methodologyEdit',
        ];

        $prevConfig = config('structure.company.template.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/company/'.$this->parentId.'/template/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '500',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.company.template.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/company/'.$this->parentId.'/template/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECT'])) {
            $this->disableEdit = true;
        }
    }

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

    public function editContent($companyId, $templateId = null)
    {
        return parent::editContent($templateId, $companyId);
    }

    public function submitForApproval($companyId, $templateId = null)
    {
        return parent::submitForApproval($templateId, $companyId);
    }

    public function viewA3($companyId, $templateId = null, $otherId = null)
    {
        return parent::viewA3($templateId, $companyId);
    }

    public function viewA4($companyId, $templateId = null, $otherId = null)
    {
        return parent::viewA4($templateId, $companyId);
    }
}
