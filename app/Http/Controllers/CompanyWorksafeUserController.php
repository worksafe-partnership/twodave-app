<?php

namespace App\Http\Controllers;

use Auth;
use Bhash;
use Controller;
use App\Company;
use App\User;
use App\Http\Requests\WorksafeUserRequest as UserRequest;

class CompanyWorksafeUserController extends WorksafeUserController
{
    protected $identifierPath = 'company.user';

    public function viewEditHook()
    {
        // if viewing company/{id}/user/{id} check that the user belongs to the viewed company - doesn't apply to create as there will be no record.
        if ($this->record->company_id != $this->parentId) {
            abort(404);
        }
    }

    public function update(UserRequest $request, $companyId, $userId = null)
    {
        $this->user = Auth::user();
        $hash = new Bhash();
        // check to see if there has been any change on the hash
        if ($hash->needsRehash($request->password) && $request->password != '') {
            $request->merge(['password' => $hash->make($request->password)]);
            $r = $request->all();
        } else {
            $r = $request->all();
            unset($r['password']);
            unset($r['password_confirmation']);
        }
        $r['company_id'] = $companyId;

        return parent::_update([$r, $companyId, $userId]);
    }
}
