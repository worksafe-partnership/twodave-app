<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class CompanyPreviousVtramController extends VtramController
{
    protected $identifierPath = 'company.project.vtram.previous';

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
        $approvalConfig = config('structure.company.project.vtram.previous.approval.config');
        $this->actionButtons['approval'] = [
            'label' => ucfirst($this->pageType)." ".$approvalConfig['plural'],
            'path' => '/company/'.$this->args[0].'/project/'.$this->args[1].'/vtram/'.$this->parentId.'/previous/'.$this->id.'/approval',
            'icon' => $approvalConfig['icon'],
            'order' => '500',
            'id' => 'approvalList'
        ];
    }

    public function viewA3($companyId, $projectId, $parentVtramId = null, $vtramId = null)
    {
        return parent::viewA3($projectId, $vtramId, $companyId);
    }       

    public function viewA4($companyId, $projectId, $parentVtramId = null, $vtramId = null)
    {
        return parent::viewA4($projectId, $vtramId, $companyId);
    }       
}
