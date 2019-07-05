<?php

namespace App;

use Carbon;
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

        if (in_array($identifier['identifier_path'], [
            'company.project.vtram.previous.approval',
            'company.project.vtram.approval',
            'project.vtram.previous.approval',
            'project.vtram.approval',
        ])) {
            // VTRAM
            $query->where('entity', '=' , 'VTRAM')
                ->where('entity_id', '=', $parent);
        } else {
            // Template
            $query->where('entity', '=' , 'TEMPLATE')
                ->where('entity_id', '=', $parent);
        }

        return app('datatables')->of($query)
            ->editColumn('status_at_time', function ($item) {
                return $item->niceStatus();
            })
            ->editColumn('type', function ($item) {
                return $item->niceType();
            })
            ->editColumn('approved_date', function ($item) {
                if ($item->approved_date !== null) {
                    return Carbon::createFromFormat('Y-m-d', $item->approved_date)->timestamp;
                }
                return '';
            })
            ->editColumn('resubmit_date', function ($item) {
                if ($item->resubmit_date !== null) {
                    return Carbon::createFromFormat('Y-m-d', $item->resubmit_date)->timestamp;
                }
                return '';
            })
            ->make('query');
    }

    public function niceStatus()
    {
        $config = config('egc.vtram_status');
        if (isset($config[$this->status_at_time])) {
            return $config[$this->status_at_time];
        }
        return '';
    }

    public function niceType()
    {
        $config = config('egc.approval_type_list');
        if (isset($config[$this->type])) {
            return $config[$this->type];
        }
        return '';
    }

    public function niceResubmit()
    {
        if (is_null($this->resubmit_date)) {
            return '';
        }
        return Carbon::createFromFormat('Y-m-d', $this->resubmit_date)->format('d/m/Y');
    }

    public function niceApproved()
    {
        if (is_null($this->approved_date)) {
            return '';
        }
        return Carbon::createFromFormat('Y-m-d', $this->approved_date)->format('d/m/Y');
    }

    public function vtram()
    {
        if ($this->entity == 'VTRAM') {
            return $this->belongsTo(Vtram::class, 'entity_id', 'id');
        } else {
            return $this->belongsTo(Template::class, 'entity_id', 'id');
        }
    }
}
