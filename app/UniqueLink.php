<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UniqueLink extends Model
{
    protected $table = 'principle_contractor_links';

    protected $fillable = [
        'unique_link',
        'email',
    ];
}
