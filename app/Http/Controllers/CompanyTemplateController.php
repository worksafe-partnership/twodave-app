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
        $company = Company::findOrFail($companyId);
        $this->view = 'modules.company.project.vtram.editVtram';
        $this->customValues['whoList'] = config('egc.hazard_who_risk');
        $this->customValues['riskList'] = [
            0 => $company->no_risk_character,
            1 => $company->low_risk_character,
            2 => $company->med_risk_character,
            3 => $company->high_risk_character,
        ];

        $this->customValues['hazards'] = Hazard::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $templateId)
            ->orderBy('list_order')
            ->get()
            ->toJson();
        $this->customValues['methodologies'] = Methodology::where('entity', '=', 'TEMPLATE')
            ->where('entity_id', '=', $templateId)
            ->orderBy('list_order')
            ->get()
            ->toJson();

        $this->record = Template::findOrFail($templateId);
        $this->customValues['comments'] = VTLogic::getComments($this->record->id, $this->record->status, "TEMPLATE");

        return parent::_custom();
    }

    public function submitForApproval($companyId, $templateId = null)
    {
        return parent::submitForApproval($templateId, $companyId);
    }

    public function viewA3($companyId, $templateId = null, $otherId = null)
    {
        return parent::viewA3($templateId, $companyId);
    }
}
