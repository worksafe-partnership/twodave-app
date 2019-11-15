<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VtramUser extends Model
{
    protected $table = 'vtram_users';

    protected $fillable = [
        'user_id',
        'vtrams_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function vtrams()
    {
        return $this->belongsTo(Vtram::class, 'vtrams_id', 'id');
    }
}
