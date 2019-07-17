<?php

namespace App\Http\Controllers;

use Controller;
use App\Http\Classes\VTLogic;

class CommentController extends Controller
{
    protected $identifierPath = 'company.project.vtram.comment';
    protected $disableEdit = true;

    public function bladeHook()
    {
        $this->customValues['comments'] = VTLogic::getComments($this->args[2], null, "VTRAM");
    }
}
