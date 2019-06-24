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
        $role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'permissions' => [
                'edit-role' => 1,
                'view-role' => 1,
                'list-role' => 1,
                'create-role' => 1,
                'delete-role' => 1,
                'permanentlyDelete-role' => 1,
                'restore-role' => 1,
                'edit-user' => 1,
                'view-user' => 1,
                'list-user' => 1,
                'create-user' => 1,
                'delete-user' => 1,
                'permanentlyDelete-user' => 1,
                'restore-user' => 1,
            ]
        ]);

        $users = User::all();
        foreach ($users as $user) {
            $user->roles()->attach([$role->id]);
        }
    }
}
