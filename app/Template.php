<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;
    protected $table = 'templates';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'logo',
        'reference',
        'key_points',
        'havs_noise_assessment',
        'coshh_assessment',
        'review_due',
        'approved_date',
        'original_id',
        'revision_number',
        'status',
        'created_by',
        'updated_by',
        'submitted_by',
        'approved_by',
        'date_replaced',
        'resubmit_by'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'company_id',
                'name',
                'description',
                'logo',
                'reference',
                'key_points',
                'havs_noise_assessment',
                'coshh_assessment',
                'review_due',
                'approved_date',
                'original_id',
                'revision_number',
                'status',
                'created_by',
                'updated_by',
                'submitted_by',
                'approved_by',
                'date_replaced',
                'resubmit_by',
                'deleted_at'
            ]);

        return Datatables::of($query)->make(true);
    }
}
