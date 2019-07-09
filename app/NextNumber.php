<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NextNumber extends Model
{
    protected $table = 'next_numbers';
    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'number'
    ];
}
