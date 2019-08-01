<?php

namespace App\Http\Controllers;

use Controller;
use App\Company;
use App\User;
use App\Http\Requests\ApprovalRequest;

class CompanyWorksafeUserController extends WorksafeUserController
{
    protected $identifierPath = 'company.user';
}
