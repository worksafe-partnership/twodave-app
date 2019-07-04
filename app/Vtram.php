<?php

namespace App;

use Carbon;
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
        'company_id',
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

        if ($identifier['identifier_path'] == 'company.project.vtram.previous') {
            $query->where('original_id', '=', $parent)
                ->where('status', '=', 'PREVIOUS');
        } else {
            $query->where('status', '!=', 'PREVIOUS')
                ->where('project_id', '=', $parent);
        }

        return app('datatables')->of($query)
            ->editColumn('project_id', function ($item) {
                $project = $item->project;
                if (!is_null($project)) {
                    return $item->project->name;
                }
                return 'None Selected';
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
            ->editColumn('approved_date', function ($item) {
                return $item->approvedDateTimestamp();
            })
            ->editColumn('review_due', function ($item) {
                return $item->nextReviewDateTimestamp();
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

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function submitted()
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id');
    }

    public function submittedName()
    {
        if (!is_null($this->submitted)) {
            return $this->submitted->name;
        }
        return 'Not Submitted';
    }

    public function submittedDateTimestamp() // for custom datatables
    {
        if (!is_null($this->submitted_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->submitted_date)->timestamp;
        }
        return "";
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function createdName()
    {
        if (!is_null($this->createdBy)) {
            return $this->createdBy->name;
        }
        return "User Not Found"; // shouldn't happen
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function approvedName()
    {
        if (!is_null($this->approved)) {
            return $this->approved->name;
        }
        return 'Not Approved';
    }

    public function approvedDateTimestamp() // for custom datatables
    {
        if (!is_null($this->approved_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->approved_date)->timestamp;
        }
        return "";
    }

    public function nextReviewDateTimestamp() // for custom datatables
    {
        if (!is_null($this->review_due)) {
            return Carbon::createFromFormat("Y-m-d", $this->review_due)->timestamp;
        }
        return "";
    }

    public function resubmitByDateTimestamp() // for custom datatables
    {
        if (!is_null($this->resubmit_by)) {
            return Carbon::createFromFormat("Y-m-d", $this->resubmit_by)->timestamp;
        }
        return "";
    }
    public function adminUrl()
    {
        if (!is_null($this->company_id) && !is_null($this->project_id) && !is_null($this->id)) {
            return "/company/".$this->company_id."/project/".$this->project_id."/vtram/".$this->id;
        }
        return '/dashboard'; // Assuming this should never be hit, but placeholder to be safe.
    }


    public function url() // for custom datatables
    {
        if (!is_null($this->project_id) && !is_null($this->id)) {
            return "/project/".$this->project_id."/vtram/".$this->id;
        }
        return '/dashboard'; // Assuming this should never be hit, but placeholder to be safe.
    }

    public function companyName()
    {
        if (!is_null($this->company)) {
            return $this->company->name;
        }
        return "";
    }
}
