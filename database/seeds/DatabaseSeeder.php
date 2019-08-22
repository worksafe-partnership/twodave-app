<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(EGLUsersTableSeeder::class);
        $this->call(EGLRolesTableSeeder::class);
        $this->call(CompanySeeder::class);
    }
}
