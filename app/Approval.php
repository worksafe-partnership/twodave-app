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

        if ($identifier['identifier_path'] == 'company.project.vtram.approval') {
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
                return $item->niceApproved();
            })
            ->editColumn('resubmit_date', function ($item) {
                return $item->niceResubmit();
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
}
