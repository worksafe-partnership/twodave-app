<?php

namespace App;

use Auth;
use Evergreen\Generic\App\Role;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id','position','signature'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeDatatableAll($query, $parentId, $config)
    {
        $user = Auth::user();
        $myRoleId = $user->roles->first()->id;

        // parentId is 'false', as opposed to null if there's no parent (identifierPath is not available in list view)
        $query->when($parentId, function ($company) use ($parentId) {
            $company->where('company_id', $parentId);
        })
        ->with('company')
        // only show users with roles equal or higher than your role id
        ->whereHas('roles', function ($roles) use ($myRoleId) {
            $roles->where('id', '>=', $myRoleId);
        });

        if ($user->company_id !== null) {
            $query->where('company_id', '=', $user->company_id);
        }

        $data = $query->withTrashed(can('permanentlyDelete', $config))->select(
            "id",
            "company_id",
            "name",
            "email",
            "deleted_at"
        );

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
