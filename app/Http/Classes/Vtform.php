<?php

namespace App\Http\Classes;

use Browser;
use Evergreen\Generic\App\Helpers\EGForm;

class VTForm extends EGForm
{
    public static function color($key, $aInp)
    {
        self::colour($key, $aInp);
    }
    public static function colour($key, $aInp)
    {
        $aInp['inp_type'] = 'color';
        EGForm::input($key, $aInp);
    }
}
