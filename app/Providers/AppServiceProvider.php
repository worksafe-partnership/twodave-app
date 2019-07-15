<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
            if ($roles[1] == 0 && $roles[2] == 0 && strlen($value) == 0) {
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
                    'email' => trim($email)
                ];
                $validator = Validator::make($data, $rules);
                if ($validator->fails()) {
                    return false;
                }
            }
            return true;
        });
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
