<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    protected $table = 'icons';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'text',
        'list_order',
        'methodology_id',
        'type'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->select([
                'id',
                'image',
                'text',
                'list_order',
                'methodology_id',
                'type'
            ]);

        return Datatables::of($query)->make(true);
    }
}
