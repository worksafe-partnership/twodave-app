<?php

namespace App\Http\Controllers;

use Controller;
use App\Approval;
use App\Http\Requests\ApprovalRequest;

class TemplateApprovalController extends ApprovalController
{
    protected $identifierPath = 'template.approval';
    
    public function __construct() 
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }
}
