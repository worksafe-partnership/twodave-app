<?php

namespace App;

use Carbon;
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

        if ($identifier['identifier_path'] == 'company.template') {
            $query->where(function ($q) use ($parent) {
                $q->where('company_id', '=', $parent)
                   ->orWhereNull('company_id'); 
            });
        }

        if (in_array($identifier['identifier_path'], ['template.previous', 'company.template.previous'])) {
            $query->where('original_id', '=', $parent)
                ->where('status', '=', 'PREVIOUS');
        } else {
            $query->where('status', '!=', 'PREVIOUS');
        }

        return app('datatables')->of($query)
            ->editColumn('approved_date', function ($item) {
                if ($item->approved_date !== null) {
                    return Carbon::createFromFormat('Y-m-d', $item->approved_date)->timestamp;
                }
                return '';
            })
            ->editColumn('review_due', function ($item) {
                if ($item->review_due !== null) {
                    return Carbon::createFromFormat('Y-m-d', $item->review_due)->timestamp;
                }
                return '';
            })
            ->editColumn('company_id', function ($item) {
                $company = $item->company;
                if (!is_null($company)) {
                    return $item->company->name;
                }
                return 'All';
            })
            ->editColumn('submitted_by', function ($item) {
                $submitted = $item->submitted;
                if (!is_null($submitted)) {
                    return $item->submitted->name;
                }
                return 'Not Submitted';
            })
            ->editColumn('approved_by', function ($item) {
                $approved = $item->approved;
                if (!is_null($approved)) {
                    return $item->approved->name;
                }
                return 'Not Approved';
            })
            ->editColumn('status', function ($item) {
                return $item->niceStatus();
            })
            ->editColumn('resubmit_by', function ($item) {
                return $item->resubmitByDateTimestamp();
            })
            ->make('query');
    }

    public function niceStatus()
    {
        $status = config('egc.vtram_status');
        if (isset($status[$this->status])) {
            return $status[$this->status];
        }
        return '';
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function submitted()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function resubmitByDateTimestamp() // for custom datatables
    {
        if (!is_null($this->resubmit_by)) {
            return Carbon::createFromFormat("Y-m-d", $this->resubmit_by)->timestamp;
        }
        return "";
    }
}
