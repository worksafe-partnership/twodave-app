<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $table = 'companies';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'review_timescale',
        'vtrams_name',
        'email',
        'phone',
        'fax',
        'low_risk_character',
        'med_risk_character',
        'high_risk_character',
        'no_risk_character',
        'primary_colour',
        'secondary_colour',
        'light_text',
        'accept_label',
        'amend_label',
        'reject_label',
        'logo',
        'main_description',
        'post_risk_assessment_text',
        'task_description',
        'plant_and_equipment',
        'disposing_of_waste',
        'first_aid',
        'noise',
        'working_at_height',
        'manual_handling',
        'accident_reporting',
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'name',
                'review_timescale',
                'vtrams_name',
                'email',
                'phone',
                'fax',
                'low_risk_character',
                'med_risk_character',
                'high_risk_character',
                'no_risk_character',
                'primary_colour',
                'secondary_colour',
                'light_text',
                'accept_label',
                'amend_label',
                'reject_label',
                'logo',
                'deleted_at'
            ]);

        return app('datatables')->of($query)
            ->rawColumns(['logo', 'primary_colour'])
            ->editColumn('review_timescale', function ($item) {
                return $item->reviewTimeScaleName();
            })
            ->editColumn('logo', function ($item) {
                if (!is_null($item->logo)) {
                    return '<img src="/image/'.$item->logo.'">';
                } else {
                    return 'No Logo';
                }
            })
            ->editColumn('primary_colour', function ($item) {
                return '<div style="background-color:'.$item->primary_colour.';height:30px;width:100%;"></div>';
            })
            ->make("query");
    }

    public function reviewTimeScaleName()
    {
        $config = config('egc.review_timescales');
        if (isset($config[$this->review_timescale])) {
            return $config[$this->review_timescale];
        }
        return 'None Selected';
    }
}
