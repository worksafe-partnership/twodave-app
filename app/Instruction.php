<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $table = 'instructions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'label',
        'heading',
        'list_order',
        'image',
        'methodology_id'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->select([
                'id',
                'description',
                'label',
                'heading',
                'list_order',
                'image',
                'methodology_id'
            ]);

        return Datatables::of($query)->make(true);
    }
}
