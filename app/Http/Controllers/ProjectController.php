<?php

namespace App\Http\Controllers;

use Controller;
use App\Project;
use App\Company;
use App\User;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    protected $identifierPath = 'company.project';

    public function bladeHook()
    {
        $this->customValues['companies'] = Company::pluck('name', 'id');
        $this->customValues['projectAdmins'] = User::pluck('name', 'id');
    }
    
    public function store(ProjectRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(ProjectRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
