<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EGLUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => "System Administrator",
            'email' => "sysadmin@worksafe-partnership.co.uk",
            'password' => Hash::make("Passw0rd1!"),
            'created_at' => Date("Y-m-d H:i:s"),
        ]);
    }
}
