<?php

namespace App\Http\Controllers;

use Controller;
use App\Vtram;
use App\Project;
use App\Http\Requests\VtramRequest;

class PreviousVtramController extends CompanyPreviousVtramController
{
    protected $identifierPath = 'project.vtram.previous';

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

    }
}
