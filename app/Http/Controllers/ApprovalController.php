<?php

namespace App\Http\Controllers;

use Controller;
use App\Approval;
use App\Http\Requests\ApprovalRequest;

class ApprovalController extends CompanyApprovalController
{
    protected $identifierPath = 'project.vtram.approval';
}
