<?php

use Illuminate\Database\Seeder;

class EGLUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hash = new Bhash();

        $suffix = env("EGC_PROJECT_NAME", "egl");
        $prefix = "egWelding";
        $password = $prefix.$suffix;

        DB::table('users')->insert([
            'name' => "Evergreen",
            'email' => "info@evergreen.co",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);

        // Worksafe staff
        DB::table('users')->insert([
            'name' => "Mark Carrington",
            'email' => "markc@worksafe-partnership.co.uk",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => "Katya Carrington",
            'email' => "katya@worksafe-partnership.co.uk",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);
        //Evergreen Test Users
        DB::table('users')->insert([
            'name' => "Worksafe Evergreen",
            'email' => "worksafe@evergreen.co",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => "Company Evergreen",
            'email' => "company@evergreen.co",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => "Contract Evergreen",
            'email' => "contract@evergreen.co",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => "Project Evergreen",
            'email' => "project@evergreen.co",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);
        DB::table('users')->insert([
            'name' => "Supervisor Evergreen",
            'email' => "supervisor@evergreen.co",
            'password' => $hash->make($password),
            'created_at' => Date("Y-m-d H:i:s")
        ]);
    }
}
