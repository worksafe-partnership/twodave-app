<?php

use App\Company;
use App\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $usersAndRoles = [
        //     'Company Admin' => 3,
        //     'Contracts Manager' => 4,
        //     'Project Admin' => 5,
        //     'Supervisor' => 6,
        // ];

        DB::table('companies')->insert([
            'name' => 'Worksafe Partnership',
            'review_timescale' => 12,
            'vtrams_name' => 'TBC',
            'email' => 'contact@worksafe-partnership.co.uk',
            'phone' => 'TBC',
            'low_risk_character' => 'L',
            'med_risk_character' => 'M',
            'high_risk_character' => 'H',
            'no_risk_character' => '#',
            'primary_colour' => '#621738',
            'secondary_colour' => '#388331',
            'accept_label' => 'TBC',
            'amend_label' => 'TBC',
            'reject_label' => 'TBC',
            'contact_name' => 'Mark Carrington',
        ]);

        $companies = Company::all();
        $company = $companies->first();

        $users = User::all();
        $users->first()->update(['company_id' => $company->id]);
        $users->first()->roles()->attach([3]);
    }
}
