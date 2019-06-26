<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Hazard extends Model
{
    protected $table = 'hazards';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'entity',
        'entity_id',
        'control',
        'risk',
        'r_risk',
        'list_order',
        'at_risk',
        'other_at_risk'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->select([
                'id',
                'description',
                'entity',
                'entity_id',
                'control',
                'risk',
                'r_risk',
                'list_order',
                'at_risk',
                'other_at_risk'
            ]);

        return Datatables::of($query)->make(true);
    }
}
