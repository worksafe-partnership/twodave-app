<?php

namespace App\Http\Controllers;

use Controller;
use App\Template;
use App\Company;
use App\Http\Requests\TemplateRequest;

class TemplateController extends Controller
{
    protected $identifierPath = 'template';

    public function viewHook()
    {
        $this->actionButtons['methodologies'] = [
            'label' => 'Edit Hazards & Methodologies',
            'path' => '/template/'.$this->id.'/methodology',
            'icon' => 'receipt',
            'order' => '300',
            'id' => 'methodologyEdit',
        ];

        $prevConfig = config('structure.template.previous.config');
        $this->actionButtons['previous'] = [
            'label' => ucfirst($this->pageType)." ".$prevConfig['plural'],
            'path' => '/template/'.$this->id.'/previous',
            'icon' => $prevConfig['icon'],
            'order' => '500',
            'id' => 'previousList'
        ];
        $approvalConfig = config('structure.template.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/template/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
    }

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

    public function editContent($templateId, $otherId = null)
    {
        $this->view = 'modules.company.project.vtram.editVtram';
        return parent::_custom();
    }
}
