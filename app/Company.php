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
        'logo'
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
            ->editColumn('review_timescale', function ($item) {
                return self::reviewTimeScaleName($item);
            })
            ->editColumn('logo', function ($item) {
                if (!is_null($item->logo)) {
                    return '<img src="/image/'.$item->logo.'">';
                } else {
                    return 'No Logo';
                }
            })
            /*->editColumn('primary_colour', function ($item) {
                return '<div
            })*/
            ->make("query");
        return Datatables::of($query)->make(true);
    }

    public static function reviewTimeScaleName($company)
    {
        $config = config('egc.review_timescales');
        if (isset($config[$company->review_timescale])) {
            return $config[$company->review_timescale];
        }
        return 'None Selected';
    }
}
