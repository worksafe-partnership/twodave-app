<?php

namespace App;

use DB;
use Auth;
use Carbon;
use EGFiles;
use Storage;
use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;
    protected $table = 'templates';
    protected $softCascade = ['approvals', 'previousTemplates'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
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
        'approved_by',
        'date_replaced',
        'resubmit_by',
        'show_area',
        'area',
        'main_description',
        'post_risk_assessment_text',
        'pages_in_pdf',
        'pdf',
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'company_id',
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
                'approved_by',
                'date_replaced',
                'resubmit_by',
                'deleted_at'
            ]);

        $user = Auth::user();

        if ($identifier['identifier_path'] == 'company.template') {
            $query->where('status', '!=', 'PREVIOUS')
                  ->where(function ($q) use ($parent) {
                    $q->where('company_id', $parent)
                    ->orWhereNull('company_id');
                  });
        } else if (in_array($identifier['identifier_path'], ['template.previous', 'company.template.previous'])) {
            $query->where('current_id', '=', $parent)
                ->where('status', '=', 'PREVIOUS');
        } else {
            if (is_null($user->company_id)) {
                $query->where('status', '!=', 'PREVIOUS');
            } else {
                $query->where(function ($myId) use ($user) {
                    $myId->where('company_id', $user->company_id)
                         ->where('status', '!=', 'PREVIOUS');
                })
                ->orWhere(function ($notMyId) use ($user) {
                    $notMyId->whereIn('company_id', $user->getAccessCompanies())
                            ->where('status', 'CURRENT');
                });
            }
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
                return '';
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
        return $this->belongsTo(Company::class, 'company_id', 'id')->withTrashed();
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

    public function niceResubmitByDate() // for custom datatables
    {
        if (!is_null($this->resubmit_by)) {
            return Carbon::createFromFormat("Y-m-d", $this->resubmit_by)->format('d/m/Y');
        }
        return "";
    }

    public function approvedName()
    {
        if (!is_null($this->approved)) {
            return $this->approved->name;
        }
        return 'Not Approved';
    }

    public function niceApprovedDate()
    {
        if (!is_null($this->approved_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->approved_date)->format('d/m/Y');
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

    public function niceSubmittedDate() // for custom datatables
    {
        if (!is_null($this->submitted_date)) {
            return Carbon::createFromFormat("Y-m-d", $this->submitted_date)->format('d/m/Y');
        }
        return "";
    }

    public function hazards()
    {
        return $this->hasMany(Hazard::class, 'entity_id', 'id')
            ->where('entity', '=', 'TEMPLATE');
    }

    public function methodologies()
    {
        return $this->hasMany(Methodology::class, 'entity_id', 'id')
            ->where('entity', '=', 'TEMPLATE');
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

    public function approvals()
    {
        if ($this->deleted_at == null) {
            return $this->hasMany(Approval::class, 'entity_id', 'id')
                ->where('entity', '=', 'TEMPLATE');
        }
        return $this->hasMany(Approval::class, 'entity_id', 'id')
            ->where('entity', '=', 'TEMPLATE')
            ->withTrashed();
    }

    public function previousTemplates()
    {
        return $this->hasMany(Template::class, 'current_id', 'id')
            ->where('created_from_entity', '=', 'TEMPLATE');
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

            foreach ($this->approvals as $approval) {
                $approval->delete();
            }

            $hazards = Hazard::where('entity', '=', 'TEMPLATE')
                            ->where('entity_id', '=', $this->id)
                            ->delete();

            $links = DB::table('hazards_methodologies')->whereIn('methodology_id', $this->methodologies->pluck('id'))->delete();

            Template::where('current_id', $this->id)->delete();

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
}
