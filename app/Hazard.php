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
        'risk_probability',
        'risk_severity',
        'r_risk',
        'r_risk_probability',
        'r_risk_severity',
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

    public function riskClass($field = 'risk')
    {
        $class = '';
        switch ($this->{$field}) {
            case 0:
                $class = 'green';
                break;
            case 1:
                $class = 'yellow';
                break;
            case 2:
                $class = 'orange';
                break;
            case 3:
                $class = 'red';
                break;
        }

        return $class;
    }
}
