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
        $contractsManagerPermissions = [];
        $projectAdminPermissions = [];
        $supervisorPermissions = [];

        // $permissions = [];
        foreach (Gate::abilities() as $perm => $ability) {
            $evergreenPermissions[$perm] = 1;
            if (strpos($perm, "role") !== false) {
                $adminPermissions[$perm] = 1;
                $contractsManagerPermissions[$perm] = 1;
                $projectAdminPermissions[$perm] = 1;
                $supervisorPermissions[$perm] = 1;
            }
        }

        $users = User::all();
        $evergreen = Role::create([
            'name' => 'Evergreen Super Admin',
            'slug' => 'evergreen',
            'permissions' => $evergreenPermissions
        ]);
        $users->where('email', 'info@evergreen.co')->first()->roles()->attach([$evergreen->id]);

        // make the rest but don't attach them yet
        Role::create([
            'name' => 'Worksafe Admin',
            'slug' => 'admin',
            'permissions' => $adminPermissions
        ]);

        Role::create([
            'name' => 'Contract Manager',
            'slug' => 'contract_manager',
            'permissions' => $contractsManagerPermissions
        ]);

        Role::create([
            'name' => 'Project Admin',
            'slug' => 'project_admin',
            'permissions' => $projectAdminPermissions
        ]);

        Role::create([
            'name' => 'Supervisor',
            'slug' => 'supervisor',
            'permissions' => $supervisorPermissions
        ]);
    }
}
