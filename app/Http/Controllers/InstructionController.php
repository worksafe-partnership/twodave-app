<?php

namespace App\Http\Controllers;

use Controller;
use App\Instruction;
use App\Http\Requests\InstructionRequest;

class InstructionController extends Controller
{
    protected $identifierPath = 'company.project.vtram.methodology.instruction';
    
    public function store(InstructionRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(InstructionRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
