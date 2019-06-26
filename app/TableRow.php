<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;

class TableRow extends Model
{
    protected $table = 'table_rows';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'col_1',
        'col_2',
        'col_3',
        'col_4',
        'list_order',
        'methodology_id',
        'cols_filled'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->select([
                'id',
                'col_1',
                'col_2',
                'col_3',
                'col_4',
                'list_order',
                'methodology_id',
                'cols_filled'
            ]);

        return Datatables::of($query)->make(true);
    }
}
