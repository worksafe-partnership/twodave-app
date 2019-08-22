<?php

namespace App\Http\Controllers;

use Controller;
use App\Approval;
use App\Http\Requests\ApprovalRequest;

class PreviousApprovalController extends PreviousCompanyApprovalController
{
    protected $identifierPath = 'project.vtram.previous.approval';

    public function __construct()
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }

    public function viewEditHook()
    {
        // no withTrashed() - hide from lower level users when soft deleted.
        $this->record = Approval::findOrFail(end($this->args));
    }

    public function store(ApprovalRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(ApprovalRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
