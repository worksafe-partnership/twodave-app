<?php

namespace App\Http\Controllers;

use App\Company;
use \Evergreen\Generic\App\Http\Controllers\UserController;

class WorksafeUserController extends UserController
{
    public function bladeHook()
    {
        $this->customValues['companies'] = Company::withTrashed()
            ->pluck('name', 'id');

        parent::bladeHook();
    }
}
