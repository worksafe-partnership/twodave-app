<?php

namespace App\Http\Controllers;

use Controller;
use App\Hazard;
use App\Company;
use App\Project;
use App\Template;
use App\Methodology;
use App\Http\Classes\VTLogic;
use App\Http\Requests\TemplateRequest;

class CompanyTemplateController extends TemplateController
{
    protected $identifierPath = 'company.template';

    public function viewHook()
    {
        if (in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            $this->actionButtons['methodologies'] = [
                'label' => 'Hazards & Method Statements',
                'path' => '/company/'.$this->parentId.'/template/'.$this->id.'/methodology',
                'icon' => 'receipt',
                'order' => '550',
                'id' => 'methodologyEdit',
            ];
        }

        $prevConfig = config('structure.company.template.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/company/'.$this->parentId.'/template/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '560',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.company.template.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/company/'.$this->parentId.'/template/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '570',
            'id' => 'approvalList'
        ];
        if (!in_array($this->record->status, ['NEW','EXTERNAL_REJECT','REJECTED'])) {
            $this->disableEdit = true;
        }
        $this->customValues['templatePath'] = '/company/'.$this->record->company_id.'/project/';
    }

    public function store(TemplateRequest $request, $companyId = null)
    {
        $company = Company::findOrFail($companyId ?? $request->company_id);
        $request->merge([
            'company_id' => $companyId,
            'main_description' => $company->main_description,
            'post_risk_assessment_text' => $company->post_risk_assessment_text,
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
