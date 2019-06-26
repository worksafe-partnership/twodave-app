<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Methodology extends Model
{
    protected $table = 'methodologies';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'entity',
        'entity_id',
        'text_before',
        'text_after',
        'image',
        'image_on',
        'list_order'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->select([
                'id',
                'category',
                'entity',
                'entity_id',
                'text_before',
                'text_after',
                'image',
                'image_on',
                'list_order'
            ]);

        return Datatables::of($query)->make(true);
    }
}
