<?php

use App\User;
use Evergreen\Generic\App\Role;
use Illuminate\Database\Seeder;

class EGLRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminPermissions = [];
        $companyAdminPermissions = [];
        $contractsManagerPermissions = [];
        $projectAdminPermissions = [];
        $supervisorPermissions = [];

        // $permissions = [];
        // Layout is
        /*
         * {type}-{identifier_path}
         * e.g. list-user
         * Or   view-company.project.vtram
         */
        // Generic Setup
        foreach (Gate::abilities() as $perm => $ability) {
            if (strpos($perm, "company") !== false) {
                $adminPermissions[$perm] = 1;
            }

            if (strpos($perm, "dashboard") !== false) {
                $companyAdminPermissions[$perm] = 1;
                $contractsManagerPermissions[$perm] = 1;
                $projectAdminPermissions[$perm] = 1;
            }

            if (strpos($perm, "project") !== false && strpos($perm, "company.project") === false) {
                // Supervisor not to have create/edit/delete/restore/perm delete anything under here
                if (strpos($perm, "view") !== false || strpos($perm, "list") !== false) {
                    // Supervisor not to have view/list previous vtrams or previous vtram approvals
                    if (strpos($perm, "vtram.previous") !== false) {
                        $companyAdminPermissions[$perm] = 1;
                        $contractsManagerPermissions[$perm] = 1;
                        $projectAdminPermissions[$perm] = 1;
                    } else {
                        $supervisorPermissions[$perm] = 1;
                        $companyAdminPermissions[$perm] = 1;
                        $contractsManagerPermissions[$perm] = 1;
                        $projectAdminPermissions[$perm] = 1;
                    }
                } else {
                    if (strpos($perm, "permanentlyDelete") !== false) {
                        if (strpos($perm, "vtram.previous") === false) {
                            $companyAdminPermissions[$perm] = 1;
                        }
                    } else {
                        $companyAdminPermissions[$perm] = 1;
                        $contractsManagerPermissions[$perm] = 1;
                        $projectAdminPermissions[$perm] = 1;
                    }
                }
                // Supervisor cannot add attendance to briefing, but can view briefings
                // Supervisor can view vtram approvals
            }

            if (strpos($perm, "hazard") !== false) {
                $companyAdminPermissions[$perm] = 1;
                $contractsManagerPermissions[$perm] = 1;
                $projectAdminPermissions[$perm] = 1;
                $adminPermissions[$perm] = 1;
            }

            if (strpos($perm, "methodology") !== false) {
                $companyAdminPermissions[$perm] = 1;
                $contractsManagerPermissions[$perm] = 1;
                $projectAdminPermissions[$perm] = 1;
                $adminPermissions[$perm] = 1;
            }

            if (strpos($perm, "template") !== false && strpos($perm, "company.template") === false) {
                if (strpos($perm, "permanentlyDelete") !== false) {
                    if (strpos($perm, "previous") === false) {
                        $companyAdminPermissions[$perm] = 1;
                        $adminPermissions[$perm] = 1;
                    } else {
                        $adminPermissions[$perm] = 1;
                    }
                } else {
                    $adminPermissions[$perm] = 1;
                    $companyAdminPermissions[$perm] = 1;
                }
            }

            // covers /user
            if (strpos($perm, "-user") !== false) {
                $adminPermissions[$perm] = 1;
            }

            //covers /company/{id}/user
            if (strpos($perm, ".user") !== false) {
                $adminPermissions[$perm] = 1;
            }
        }

        $users = User::all();

        $admin = Role::create([
            'name' => 'Worksafe Admin',
            'slug' => 'admin',
            'permissions' => $adminPermissions,
        ]);
        $users->first()->roles()->attach([$admin->id]);

        $company = Role::create([
            'name' => 'Company Admin',
            'slug' => 'company_admin',
            'permissions' => $companyAdminPermissions,
        ]);

        $contract = Role::create([
            'name' => 'Contract Manager',
            'slug' => 'contract_manager',
            'permissions' => $contractsManagerPermissions,
        ]);

        $project = Role::create([
            'name' => 'Project Admin',
            'slug' => 'project_admin',
            'permissions' => $projectAdminPermissions,
        ]);

        $supervisor = Role::create([
            'name' => 'Supervisor',
            'slug' => 'supervisor',
            'permissions' => $supervisorPermissions,
        ]);
    }
}
