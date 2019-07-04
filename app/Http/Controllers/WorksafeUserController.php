<?php

namespace App\Http\Controllers;

use Auth;
use Bhash;
use Controller;
use App\User;
use App\Company;
use App\Http\Requests\WorksafeUserRequest as UserRequest;
use \Evergreen\Generic\App\Http\Controllers\UserController;
use \Evergreen\Generic\App\Role;

class WorksafeUserController extends Controller
{
    protected $identifierPath = "user";

    public function bladeHook()
    {
        if ($this->user->company_id !== null) {
            if ($this->user->company_id !== $this->record->company_id) {
                abort(404);
            }
        }

        $this->customValues['companies'] = Company::withTrashed()
            ->pluck('name', 'id');
        $this->customValues['userPage'] = true;

        $this->customValues['roles'] = Role::pluck("name", "id");
        if ($this->user->company_id !== null) {
            unset($this->customValues['roles'][1]);
            unset($this->customValues['roles'][2]);
        }
        $currentRoles = [];
        if (!is_null($this->record)) {
            $roles = $this->record['roles'];
            if (!is_null($roles)) {
                foreach ($roles as $role) {
                    $currentRoles[$role->id] = 1;
                }
            }
        }

        $this->customValues['currentRoles'] = $currentRoles;
    }

    public function store(UserRequest $request)
    {
        $this->user = Auth::user();
        if ((isset($request['roles'][1]) && $request['roles'][1]) || (isset($request['roles'][2]) && $request['roles'][2])) {
            $request->merge([
                'company_id' => null,
            ]);
        }
        if (!is_null($this->user->company_id)) {
            $request->merge([
                'company_id' => $this->user->company_id,
            ]);
        }
        $hash = new Bhash();
        $request->merge(['password' => $hash->make($request->password)]);
        return parent::_store([$request->all()]);
    }

    public function created($insert, $request, $args)
    {
        $attach = [];
        foreach ($request['roles'] as $role => $checked) {
            if ($checked) {
                $attach[] = $role;
            }
        }

        $insert->roles()->attach($attach);
    }

    public function update(UserRequest $request, $id)
    {
        $this->user = Auth::user();
        if ((isset($request['roles'][1]) && $request['roles'][1]) || (isset($request['roles'][2]) && $request['roles'][2])) {
            $request->merge([
                'company_id' => null,
            ]);
        }
        if (!is_null($this->user->company_id)) {
            $request->merge([
                'company_id' => $this->user->company_id,
            ]);
        }
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
        return parent::_update([$r, $id]);
    }

    public function updated($update, $original, $request, $args)
    {
        $attach = [];
        $detach = [];
        if (isset($request['roles'])) {
            foreach ($request['roles'] as $role => $checked) {
                $detach[] = $role;
                if ($checked) {
                    $attach[] = $role;
                }
            }

            $update->roles()->detach($detach);
            $update->roles()->attach($attach);
        }
    }

    public function search(Request $request)
    {
        $results = [];
        if (isset($request->search)) {
            $search = User::where("name", "LIKE", "%".$request->search."%")
                          ->orWhere("email", "LIKE", "%".$request->search."%")
                          ->get();
            foreach ($search as $value) {
                $results[] = [
                    'url' => '/user/'.$value->id,
                    'value' => $value->name.' ('.$value->email.')'
                ];
            }
        }
        return $results;
    }
}
