<?php

namespace App\Http\Controllers;

use Controller;
use App\TableRow;
use App\Http\Requests\TableRowRequest;

class TableRowController extends Controller
{
    protected $identifierPath = 'company.project.vtram.methodology.table_row';
    
    public function store(TableRowRequest $request)
    {
        return parent::_store(func_get_args());
    }

    public function update(TableRowRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
