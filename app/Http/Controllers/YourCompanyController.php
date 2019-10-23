<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Project;
use App\Http\Requests\CompanyRequest;

class YourCompanyController extends Controller
{
    protected $identifierPath = "your_company";

    public function _setData($args)
    {
        $this->record = Auth::user()->company;
        if ($this->record == null) {
            abort(404);
        }
        $this->id = $this->record->id;
    }

    public function update(CompanyRequest $request, $id = null)
    {
        return parent::_update(func_get_args());
    }

    public function editHook()
    {
        $this->customValues['projects'] = Project::where('company_id', $this->user->company_id)->pluck('name', 'id');
    }
}
