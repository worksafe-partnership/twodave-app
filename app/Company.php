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

        return Datatables::of($query)->make(true);
    }
}
