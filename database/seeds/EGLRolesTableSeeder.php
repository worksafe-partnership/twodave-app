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

        $evergreenPermissions = [];
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
                $evergreenPermissions[$perm] = 1;
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
                $evergreenPermissions[$perm] = 1;
                $adminPermissions[$perm] = 1;
            }

            if (strpos($perm, "methodology") !== false) {
                $companyAdminPermissions[$perm] = 1;
                $contractsManagerPermissions[$perm] = 1;
                $projectAdminPermissions[$perm] = 1;
                $evergreenPermissions[$perm] = 1;
                $adminPermissions[$perm] = 1;
            }

            if (strpos($perm, "role") !== false) {
                $evergreenPermissions[$perm] = 1;
            }

            if (strpos($perm, "template") !== false && strpos($perm, "company.template") === false) {
                if (strpos($perm, "permanentlyDelete") !== false) {
                    if (strpos($perm, "previous") === false) {
                        $companyAdminPermissions[$perm] = 1;
                        $evergreenPermissions[$perm] = 1;
                        $adminPermissions[$perm] = 1;
                    } else {
                        $evergreenPermissions[$perm] = 1;
                        $adminPermissions[$perm] = 1;
                    }
                } else {
                    $evergreenPermissions[$perm] = 1;
                    $adminPermissions[$perm] = 1;
                    $companyAdminPermissions[$perm] = 1;
                }
            }

            // covers /user
            if (strpos($perm, "-user") !== false) {
                // if (strpos($perm, "permanentlyDelete") === false) {
                    $evergreenPermissions[$perm] = 1;
                    $adminPermissions[$perm] = 1;
                    $companyAdminPermissions[$perm] = 1;
                // }
            }

            //covers /company/{id}/user
            if (strpos($perm, ".user") !== false) {
                // if (strpos($perm, "permanentlyDelete") === false) {
                    $evergreenPermissions[$perm] = 1;
                    $adminPermissions[$perm] = 1;
                // }
            }

        }

        // Specific ones to add/remove that can't be done easily above
        // i.e. unset($supervisorPermissions['edit-project'];

        $users = User::all();
        $evergreen = Role::create([
            'name' => 'Evergreen Super Admin',
            'slug' => 'evergreen',
            'permissions' => $evergreenPermissions
        ]);
        $users->where('email', 'info@evergreen.co')->first()->roles()->attach([$evergreen->id]);

        // Mark & Katya will always need accounts
        $admin = Role::create([
            'name' => 'Worksafe Admin',
            'slug' => 'admin',
            'permissions' => $adminPermissions
        ]);
        $users->where('email', 'katya@worksafe-partnership.co.uk')->first()->roles()->attach([$admin->id]);
        $users->where('email', 'markc@worksafe-partnership.co.uk')->first()->roles()->attach([$admin->id]);
        $users->where('email', 'worksafe@evergreen.co')->first()->roles()->attach([$admin->id]);

        $company = Role::create([
            'name' => 'Company Admin',
            'slug' => 'company_admin',
            'permissions' => $companyAdminPermissions
        ]);
        $users->where('email', 'company@evergreen.co')->first()->roles()->attach([$company->id]);

        $contract = Role::create([
            'name' => 'Contract Manager',
            'slug' => 'contract_manager',
            'permissions' => $contractsManagerPermissions
        ]);
        $users->where('email', 'contract@evergreen.co')->first()->roles()->attach([$contract->id]);

        $project = Role::create([
            'name' => 'Project Admin',
            'slug' => 'project_admin',
            'permissions' => $projectAdminPermissions
        ]);
        $users->where('email', 'project@evergreen.co')->first()->roles()->attach([$project->id]);

        $supervisor = Role::create([
            'name' => 'Supervisor',
            'slug' => 'supervisor',
            'permissions' => $supervisorPermissions
        ]);
        $users->where('email', 'supervisor@evergreen.co')->first()->roles()->attach([$supervisor->id]);
    }
}
