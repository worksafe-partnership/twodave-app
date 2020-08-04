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

    protected $casts = [
        'at_risk' => 'json',
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
        if ($this->{$field} >= 15) {
            $class = 'red';
        } elseif ($this->{$field} >= 5) {
            $class = 'amber';
        } else {
            $class = 'green';
        }

        return $class;
    }

    public function entityRecord()
    {
        if ($this->entity == 'VTRAM') {
            return $this->belongsTo(Vtram::class, 'entity_id', 'id');
        } else if ($this->entity == 'TEMPLATE') {
            return $this->belongsTo(Template::class, 'entity_id', 'id');
        }
    }
}
