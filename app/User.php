<?php

namespace App;

use Auth;
use Evergreen\Generic\App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeDatatableAll($query)
    {
        $user = Auth::user();
        $data = $query->select(
            "id",
            "company_id",
            "name",
            "email"
        )
        ->with('company');
        
        if ($user->company_id !== null) {
            $data->where('company_id', '=', $user->company_id);
        }
        
        $data = $data->get();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'id' => $row->id,
                'company_name' => $row->company_name,
                'name' => $row->name,
                'email' => $row->email,
            ];
        }
        return ["data" => $result];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    /**
     * Checks if User has access to $permissions.
     */
    public function hasAccess(array $permissions) : bool
    {
        // check if the permission is available in any role
        foreach ($this->roles as $role) {
            if ($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks if the user belongs to role.
     */
    public function inRole(string $roleSlug)
    {
        return $this->roles->where('slug', $roleSlug)->count() == 1;
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function getCompanyNameAttribute()
    {
        $company = $this->company;
        if ($company != null) {
            return $company->name;
        }
        return '';
    }
}
