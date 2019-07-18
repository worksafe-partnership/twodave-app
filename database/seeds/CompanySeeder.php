<?php

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
        $counts = [
            'extraCompanies' => 5
        ];

        $hash = new Bhash();
        $suffix = env("EGC_PROJECT_NAME", "egl");
        $prefix = "egWelding";
        $password = $hash->make($prefix.$suffix);

        // require_once base_path().'/vendor/fzaninotto/faker/src/autoload.php';
        // $faker = Faker\Factory::create('en_GB');

        $starterCompanies = [
            'UK FM Services',
            'Woodford Heating and Energy',
            'Berkeley Homes',
        ];
        $usersAndRoles = [
            'Company Admin' => 3,
            'Contracts Manager' => 4,
            'Project Admin' => 5,
            'Supervisor' => 6,
        ];

        foreach ($starterCompanies as $companyName) {
            $company = factory(App\Company::class)->create(['name' => $companyName]);
            foreach ($usersAndRoles as $role => $roleId) {
                $users = factory(User::class, 2)->create([
                    'name' => $company->name." - ".$role,
                    'company_id' => $company->id,
                    'password' => $password
                ]);

                foreach ($users as $user) {
                    $user = User::find($user->id);
                    $user->roles()->attach([$roleId]);
                }
            }

            $projectAdmins = User::join('role_users', 'role_users.user_id', '=', 'users.id')
                ->where('company_id', $company->id)
                ->where('role_id', 5)
                ->get(['id']);


            $projects = factory(App\Project::class, 5)->create([
                'company_id' => $company->id,
                'project_admin' => $projectAdmins->random(1)->first()->id
            ]);

            $contractManagers = User::join('role_users', 'role_users.user_id', '=', 'users.id')
                ->where('company_id', $company->id)
                ->where('role_id', 4)
                ->get(['id']);

            foreach ($projects as $project) {
                factory(App\Vtram::class, 5)->create([
                    'company_id' => $company->id,
                    'project_id' => $project->id,
                    'created_by' => $project->project_admin,
                    'submitted_by' => $project->project_admin,
                ]);

                factory(App\Vtram::class, 5)->create([
                    'company_id' => $company->id,
                    'project_id' => $project->id,
                    'created_by' => $contractManagers->random(1)->first()->id,
                    'submitted_by' => $contractManagers->random(1)->first()->id,
                ]);

                $companyVTrams = \App\Vtram::where('company_id', $company->id)->get();
                $currentVTrams = $companyVTrams->where('status', 'CURRENT');
                if ($currentVTrams->isNotEmpty()) {
                    factory(App\Briefing::class, 2)->create([
                        'project_id' => $currentVTrams->random(1)->first()->project_id,
                        'vtram_id' => $currentVTrams->random(1)->first()->id,
                    ]);
                }

            }
            // a couple of templates for each company as well
            factory(App\Template::class, 2)->create([
                'company_id' => $company->id,
                'created_by' => $contractManagers->random(1)->first()->id,
                'submitted_by' => $contractManagers->random(1)->first()->id,
            ]);
        }

        // $seededCompanies = factory(App\Company::class, $counts['extraCompanies'])->create();
    }
}
