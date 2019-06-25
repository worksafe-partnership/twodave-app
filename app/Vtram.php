<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vtram extends Model
{
    use SoftDeletes;
    protected $table = 'vtrams';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
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
        'pre_risk_assessment_text',
        'post_risk_assessment_text',
        'dynamic_risk',
        'pdf',
        'pages_in_pdf',
        'created_from',
        'show_responsible_person',
        'responsible_person'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'project_id',
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
                'pre_risk_assessment_text',
                'post_risk_assessment_text',
                'dynamic_risk',
                'pdf',
                'pages_in_pdf',
                'created_from',
                'show_responsible_person',
                'responsible_person',
                'deleted_at'
            ]);

        return Datatables::of($query)->make(true);
    }
}
