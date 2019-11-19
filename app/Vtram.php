<?php

namespace App;

use DB;
use Carbon;
use EGFiles;
use Storage;
use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vtram extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;

    use SoftDeletes;
    protected $table = 'vtrams';
    protected $softCascade = ['approvals', 'briefings', 'previousVTs'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'project_id',
        'name',
        'logo',
        'reference',
        'key_points',
        'havs_noise_assessment',
        'coshh_assessment',
        'review_due',
        'approved_date',
        'current_id',
        'revision_number',
        'status',
        'created_by',
        'updated_by',
        'submitted_by',
        'submitted_date',
        'approved_by',
        'date_replaced',
        'resubmit_by',
        'main_description',
        'post_risk_assessment_text',
        'dynamic_risk',
        'pdf',
        'pages_in_pdf',
        'created_from_entity',
        'created_from_id',
        'show_responsible_person',
        'responsible_person',
        'number',
        'client_on_pdf',
        'pc_on_pdf',
        'show_area',
        'area',
        'general_rams',
        'company_logo_id',
        'vtram_is_file',
        'vtram_file'
    ];


    public static function scopePcDatatableAll($query, $parent, $identifier, $email)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'project_id',
                'name',
                'logo',
                'reference',
                'key_points',
                'havs_noise_assessment',
                'coshh_assessment',
                'review_due',
                'approved_date',
                'current_id',
                'revision_number',
                'status',
                'created_by',
                'updated_by',
                'submitted_by',
                'submitted_date',
                'approved_by',
                'date_replaced',
                'resubmit_by',
                'post_risk_assessment_text',
                'dynamic_risk',
                'pdf',
                'pages_in_pdf',
                'show_responsible_person',
                'responsible_person',
                'deleted_at',
                'number',
            ]);

        $query->whereHas('project', function ($q) use ($email) {
            return $q->where('principle_contractor_email', '=', $email);
        })
        ->where('status', '=', 'AWAITING_EXTERNAL');

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

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'vtrams.id',
                'project_id',
                'vtrams.name',
                'vtrams.logo',
                'reference',
                'key_points',
                'havs_noise_assessment',
                'coshh_assessment',
                'review_due',
                'approved_date',
                'current_id',
                'revision_number',
                'status',
                'created_by',
                'updated_by',
                'submitted_by',
                'submitted_date',
                'approved_by',
                'date_replaced',
                'resubmit_by',
                'vtrams.post_risk_assessment_text',
                'vtrams.dynamic_risk',
                'pdf',
                'pages_in_pdf',
                'show_responsible_person',
                'responsible_person',
                'vtrams.deleted_at',
                'number',
                'companies.name as company_name'
            ])
        ->join('companies', 'companies.id', '=', 'vtrams.company_id');

        if (in_array($identifier['identifier_path'], ['company.project.vtram.previous', 'project.vtram.previous'])) {
            $query->where('current_id', '=', $parent)
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
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id')->withTrashed();
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

    public function submittedDateTimestamp()
    {
        if (!is_null($this->submitted_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->submitted_date)->timestamp;
        }
        return "";
    }

    public function niceSubmittedDate()
    {
        if (!is_null($this->submitted_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->submitted_date)->format('d/m/Y');
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
        return "";
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function updatedName()
    {
        $updated = $this->updatedBy;
        if (!is_null($updated)) {
            return $updated->name;
        }
        return "";
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

    public function approvedDateTimestamp()
    {
        if (!is_null($this->approved_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->approved_date)->timestamp;
        }
        return "";
    }

    public function niceApprovedDate()
    {
        if (!is_null($this->approved_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->approved_date)->format('d/m/Y');
        }
        return "";
    }

    public function nextReviewDateTimestamp()
    {
        if (!is_null($this->review_due)) {
            return Carbon::createFromFormat("Y-m-d", $this->review_due)->timestamp;
        }
        return "";
    }

    public function resubmitByDateTimestamp()
    {
        if (!is_null($this->resubmit_by)) {
            return Carbon::createFromFormat("Y-m-d", $this->resubmit_by)->timestamp;
        }
        return "";
    }

    public function niceResubmitByDate()
    {
        if (!is_null($this->resubmit_by)) {
            return Carbon::createFromFormat("Y-m-d", $this->resubmit_by)->format('d/m/Y');
        }
        return "";
    }

    public function niceReviewDueDate()
    {
        if (!is_null($this->review_due)) {
            return Carbon::createFromFormat("Y-m-d", $this->review_due)->format('d/m/Y');
        }
        return "";
    }

    public function niceDateReplaced()
    {
        if (!is_null($this->date_replaced)) {
            return Carbon::createFromFormat("Y-m-d", $this->date_replaced)->format('d/m/Y');
        }
        return "";
    }

    public function adminUrl()
    {
        if (!is_null($this->company_id) && !is_null($this->project_id) && !is_null($this->id)) {
            return "/company/".$this->company_id."/project/".$this->project_id."/vtram/".$this->id;
        }
        return '/';
    }


    public function url()
    {
        if (!is_null($this->project_id) && !is_null($this->id)) {
            return "/project/".$this->project_id."/vtram/".$this->id;
        }
        return '/';
    }

    public function companyName()
    {
        if (!is_null($this->company)) {
            return $this->company->name;
        }
        return "";
    }

    public function dTClass($nowCarbon, $twoWeeksCarbon)
    {
        if ($this->review_due) {
            $dueCarbon = Carbon::createFromFormat("Y-m-d", $this->review_due);
            if ($nowCarbon->gt($dueCarbon)) {
                return "review-overdue";
            }
            if ($dueCarbon->lt($twoWeeksCarbon)) {
                return "review-two-weeks";
            }
        }
        return "review-okay";
    }

    public function hazards()
    {
        return $this->hasMany(Hazard::class, 'entity_id', 'id')
            ->where('entity', '=', 'VTRAM');
    }

    public function methodologies()
    {
        return $this->hasMany(Methodology::class, 'entity_id', 'id')
            ->where('entity', '=', 'VTRAM');
    }

    public function getCreatedFromAttribute()
    {
        if ($this->created_from_entity == 'VTRAM') {
            $vt = VTRAM::find($this->created_from_id);
            if ($vt) {
                return $vt->name;
            }
        } else if ($this->created_from_entity == 'TEMPLATE') {
            $template = Template::find($this->created_from_id);
            if ($template) {
                return $template->name;
            }
        }
        return "";
    }

    public function approvals()
    {
        if ($this->deleted_at == null) {
            return $this->hasMany(Approval::class, 'entity_id', 'id')
                ->where('entity', '=', 'VTRAM');
        }
        return $this->hasMany(Approval::class, 'entity_id', 'id')
            ->where('entity', '=', 'VTRAM')
            ->withTrashed();
    }

    public function previousVTs()
    {
        return $this->hasMany(VTRAM::class, 'current_id', 'id')
            ->where('created_from_entity', '=', 'VTRAM');
    }

    public function briefings()
    {
        return $this->hasMany(Briefing::class, 'vtram_id', 'id');
    }

    public function delete()
    {
        if (!is_null($this->deleted_at)) {
            if (!is_null($this->logo)) {
                $file = EGFiles::findOrFail($this->logo);
                Storage::disk('local')->delete($file->location);
                $file->forceDelete();
            }

            if (!is_null($this->pdf)) {
                $file = EGFiles::findOrFail($this->pdf);
                Storage::disk('local')->delete($file->location);
                $file->forceDelete();
            }

            if (!is_null($this->havs_noise_assessment)) {
                $file = EGFiles::findOrFail($this->havs_noise_assessment);
                Storage::disk('local')->delete($file->location);
                $file->forceDelete();
            }

            if (!is_null($this->coshh_assessment)) {
                $file = EGFiles::findOrFail($this->coshh_assessment);
                Storage::disk('local')->delete($file->location);
                $file->forceDelete();
            }

            $project = Project::withTrashed()->findOrFail($this->project_id);
            $vtramsCount = VTram::join('projects', 'vtrams.project_id', '=', 'projects.id')
                           ->where('vtrams.status', 'AWAITING_EXTERNAL')
                           ->where('principle_contractor', 1)
                           ->where('principle_contractor_email', $project->principle_contractor)
                           ->count();

            if ($vtramsCount == 0) {
                UniqueLink::where('email', $project->principle_contractor_email)->delete();
            }

            foreach ($this->approvals as $approval) {
                $approval->delete();
            }

            $hazards = Hazard::where('entity', '=', 'VTRAM')
                            ->where('entity_id', '=', $this->id)
                            ->delete();

            $links = DB::table('hazards_methodologies')->whereIn('methodology_id', $this->methodologies->pluck('id'))->delete();

            // Delete Previous
            VTRAM::where('current_id', $this->id)->delete();

            foreach ($this->methodologies as $meth) {
                $meth->delete();
            }
        }
        parent::delete();
    }

    public function companyLogo()
    {
        return $this->hasOne(Company::class, 'id', 'company_logo_id');
    }

    public function vtramsUsers()
    {
        return $this->hasMany(VtramUser::class, 'vtrams_id', 'id');
    }
}
