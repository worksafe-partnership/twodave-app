<?php

namespace App\Http\Controllers;

use Controller;
use App\Icon;
use App\Http\Requests\IconRequest;

class IconController extends Controller
{
    protected $identifierPath = 'company.project.vtram.methodology.icon';
    
    public function store(IconRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(IconRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
