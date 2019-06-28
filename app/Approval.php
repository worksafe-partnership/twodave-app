<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approval extends Model
{
    use SoftDeletes;
    protected $table = 'approvals';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity',
        'entity_id',
        'comment',
        'type',
        'completed_by',
        'completed_by_id',
        'approved_date',
        'resubmit_date',
        'status_at_time',
        'review_document'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'entity',
                'entity_id',
                'comment',
                'type',
                'completed_by',
                'completed_by_id',
                'approved_date',
                'resubmit_date',
                'status_at_time',
                'review_document',
                'deleted_at'
            ]);

        if ($identifier['identifier_path'] == 'company.project.vtram.approval') {
            // VTRAM
            $query->where('entity', '=' , 'VTRAM')
                ->where('entity_id', '=', $parent);
        } else {
            // Template
            $query->where('entity', '=' , 'TEMPLATE')
                ->where('entity_id', '=', $parent);
        }

        return Datatables::of($query)->make(true);
    }
}
