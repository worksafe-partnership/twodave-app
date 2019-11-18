<?php

namespace App;

use Auth;
use App\Company;
use App\Project;
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
                'deleted_at' => $row->deleted_at
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
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
    }

    public function getCompanyNameAttribute()
    {
        $company = $this->company;
        if ($company != null) {
            return $company->name;
        }
        return '';
    }

    // gets a list of all companies where I have access to a project
    public function getAccessCompanies()
    {
        if (is_null($this->company_id)) { // super admin can do all.
            return Company::pluck('name', 'id')->toArray();
        } else {
            $companies = Project::join('project_subcontractors', 'projects.id', '=', 'project_subcontractors.project_id')
                           ->where('project_subcontractors.company_id', $this->company_id)
                           ->pluck('projects.company_id')->toArray();
            $companies[] = $this->company_id;
            return $companies;
        }
    }

    public function nonBillableContractors()
    {
        if (is_null($this->company_id)) { // super admin can do all.
            return Company::pluck('name', 'id')->toArray();
        } else {
            $contractors = Project::where('projects.company_id', $this->company_id)
                           ->join('project_subcontractors', 'projects.id', '=', 'project_subcontractors.project_id')
                           ->join('companies', 'project_subcontractors.company_id', '=', 'companies.id')
                           ->whereNull('billable')
                           ->pluck('companies.name', 'companies.id')->toArray();

            $contractors[$this->company_id] = $this->companyName;
            return $contractors;
        }
    }

    public function projectCompanyIds()
    {
        $isSuper = is_null($this->company_id);
        $isCompanyAdmin = $this->inRole('company_admin');

        $projects = Project::join('project_subcontractors', 'projects.id', '=', 'project_subcontractors.project_id')
                           ->unless($isSuper, function ($notSuper) {
                                $notSuper->where(function ($sub) {
                                    $sub->where('project_subcontractors.company_id', $this->company_id)
                                      ->orWhere('projects.company_id', $this->company_id);
                                });
                           })
                           ->with(['users'])
                           ->get(['projects.id']);

        $permittedProjects = [];
        foreach ($projects as $project) {
            $users = $project->users;
            if ($isSuper || $isCompanyAdmin) {
                $permittedProjects[] = $project->id;
            } else if ($users->isEmpty()) {
                $permittedProjects[] = $project->id;
            } else if (in_array($this->id, $users->pluck('id')->toArray())) {
                $permittedProjects[] = $project->id;
            }
        }

        return array_unique($permittedProjects);
    }

    public function vtramsCompanyIds()
    {
        $isSuper = is_null($this->company_id);
        $isCompanyAdmin = $this->inRole('company_admin');

        $vtrams = Vtram::join('projects', 'projects.id', '=', 'vtrams.project_id')
                        ->leftJoin('project_subcontractors', 'projects.id', '=', 'project_subcontractors.project_id')
                            ->unless($isSuper, function ($notSuper) {
                                $notSuper->where(function ($sub) {
                                    $sub->where('project_subcontractors.company_id', $this->company_id)
                                      ->orWhere('projects.company_id', $this->company_id);
                                });
                            })
                        ->with(['vtramsUsers'])
                        ->get(['vtrams.id']);

        $permittedVtrams = [];
        foreach ($vtrams as $vtram) {
            $users = $vtram->vtramsUsers;
            if ($isSuper || $isCompanyAdmin) {
                $permittedVtrams[] = $vtram->id;
            } else if ($users->isEmpty()) {
                $permittedVtrams[] = $vtram->id;
            } else if (in_array($this->id, $users->pluck('user_id')->toArray())) {
                $permittedVtrams[] = $vtram->id;
            }
        }

        return array_unique($permittedVtrams);
    }
}
