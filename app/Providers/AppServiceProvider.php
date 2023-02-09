<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('onlyOneRole', function ($attribute, $value, $parameters, $validator) {
            $totalVal = 0;
            foreach ($value as $role => $val) {
                $totalVal += $val;
            }
            if ($totalVal == 1) {
                return true;
            } else {
                return false;
            }
        });

        Validator::extend('companyRequired', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $roles = $data['roles'];

            $evergreen = 0;
            $worksafe = 0;

            if (isset($roles[1])) {
                $evergreen = $roles[1];
            }

            if (isset($roles[2])) {
                $worksafe = $roles[2];
            }

            if ($evergreen == 0 && $worksafe == 0 && strlen($value) == 0) {
                return false;
            }
            return true;
        });

        Validator::extend('csv', function ($attribute, $value, $parameters, $validator) {
            $emails = array_filter(explode(',', $value));
            $rules = [
                'email' => 'email',
            ];
            foreach ($emails as $email) {
                $data = [
                    'email' => trim($email),
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
        });

        // this guy helps on the Projects page, where a Principle Contract Company user can select Contractors and Subcontractors per project.
        // It stops you selecting the same company for both Contractor and Sub.
        Validator::extend('noConflictWithSubs', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            if (isset($data['contractors']) && isset($data['subcontractors'])) {
                foreach ($data['contractors'] as $c) {
                    if (in_array($c, $data['subcontractors'])) {
                        return false;
                    }
                }
            }
            // if they're not both set there can't be a conflict.
            return true;
        });

        Validator::extend('duplicateEmailCheck', function ($attribute, $value, $parameters, $validator) {
            $r = $validator->getData();
            $emailsToCheck = [
                $r['company_admin_email'],
                $r['email'],
            ];
            if ($r['principle_contractor'] == '1') {
                $emailsToCheck[] = $r['principle_contractor_email'];
            }
            if (isset($r['company_admin_email_con'])) {
                $emailsToCheck[] = $r['company_admin_email_con'];
            }
            $check = array_unique($emailsToCheck);
            if (count($check) == count($emailsToCheck)) {
                return true;
            }
            return false;
        });

        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
