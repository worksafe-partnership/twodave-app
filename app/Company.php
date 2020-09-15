<?php

namespace App;

use Carbon;
use EGFiles;
use Storage;
use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;
    protected $table = 'companies';
    protected $softCascade = ['users', 'projects', 'templates'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_name',
        'review_timescale',
        'vtrams_name',
        'email',
        'phone',
        'contact_name',
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
        'main_description',
        'post_risk_assessment_text',
        'show_document_ref_on_pdf',
        'show_message_on_pdf',
        'message',
        'show_revision_no_on_pdf',
        'billable',
        'allow_file_uploads',
        'is_principal_contractor',
        'num_vtrams',
        'sub_frequency',
        'start_date',
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
                'contact_name',
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

        return app('datatables')->of($query)
            ->rawColumns(['logo', 'primary_colour'])
            ->editColumn('review_timescale', function ($item) {
                return $item->reviewTimeScaleName();
            })
            ->editColumn('logo', function ($item) {
                if (!is_null($item->logo)) {
                    return '<img src="/image/'.$item->logo.'">';
                } else {
                    return 'No Logo';
                }
            })
            ->editColumn('primary_colour', function ($item) {
                return '<div style="background-color:'.$item->primary_colour.';height:30px;width:100%;"></div>';
            })
            ->make("query");
    }

    public function methodologies()
    {
        return $this->hasMany(Methodology::class, 'entity_id', 'id')
            ->where('entity', '=', 'COMPANY');
    }

    public function reviewTimeScaleName()
    {
        $config = config('egc.review_timescales');
        if (isset($config[$this->review_timescale])) {
            return $config[$this->review_timescale];
        }
        return 'None Selected';
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }

    public function projects()
    {
        if (is_null($this->deleted_at)) {
            return $this->hasMany(Project::class, 'company_id', 'id');
        } else {
            return $this->hasMany(Project::class, 'company_id', 'id')->withTrashed();
        }
    }

    public function templates()
    {
        if (is_null($this->deleted_at)) {
            return $this->hasMany(Template::class, 'company_id', 'id');
        }
        return $this->hasMany(Template::class, 'company_id', 'id')->withTrashed();
    }

    public function vtrams()
    {
        if (is_null($this->deleted_at)) {
            return $this->hasMany(Vtram::class, 'company_id', 'id');
        }
        return $this->hasMany(Vtram::class, 'company_id', 'id')->withTrashed();
    }

    public function delete()
    {
        if (!is_null($this->deleted_at)) {
            foreach ($this->projects as $project) {
                $project->delete();
            }
            foreach ($this->templates as $template) {
                $template->delete();
            }
            if ($this->logo != null) {
                $file = EGFiles::withTrashed()->find($this->logo);
                if ($file != null) {
                    Storage::disk('local')->delete($file->location);
                    $file->forceDelete();
                }
            }
        }
        parent::delete();
    }

    public function canCreateType($type = 'VTRAMS')
    {
        // No limit set so allow unlimited
        if ($this->num_vtrams == null) {
            return true;
        }

        // start date 15/06/2020 - monthly, get todays date
        // get previous start date, so we're on 16/09, need to get 15/09 for monthly
        $originalStart = Carbon::createFromFormat('Y-m-d', $this->start_date);
        $now = Carbon::now();
        $startingDate = Carbon::now();
        if ($this->sub_frequency == 'MONTH') {
            $year = $now->year;
            $month = $now->month;
            if ($now->month < $originalStart->month) {
                $month = $now->month - 1;
            } else if ($now->month == $originalStart->month && $now->day < $originalStart->day) {
                $month = $now->month - 1;
            }

            if ($month < 1) {
                $month = 12;
                $year--;
            }
        } else {
            if ($now->year > $originalStart->year && $now->month < $originalStart->month) {
                $year = $now->year - 1;
            } else if ($now->year > $originalStart->year && $now->month == $originalStart->month && $now->day < $originalStart->day) {
                $year = $now->year - 1;
            } else {
                $year = $now->year;
            }

            $month = $originalStart->month;
        }

        $daysThisMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        if ($originalStart->day > $daysThisMonth) {
            if ($month == 2) { // February stuff
                $day = $originalStart->day - 1;
                while ($day >= 28) {
                    if ($day <= $daysThisMonth) {
                        $startingDate = Carbon::create($year, $month, $day, 0, 0, 0);
                        break;
                    }
                    $day--;
                }
            } else {
                // If not Feb, then we the subscription started on the 31st, so take it to the 30th
                $startingDate = Carbon::create($year, $month, $originalStart->day - 1, 0, 0, 0);
            }
        } else {
            $startingDate = Carbon::create($year, $month, $originalStart->day, 0, 0, 0);
        }

        if ($startingDate == null) {
            return false;
        }

        // Calc end date
        if ($this->sub_frequency == 'MONTH') {
            $month++;
        } else {
            $year++;
        }

        $daysInEndMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $endingDate = Carbon::create($year, $month, $originalStart->day > $daysInEndMonth ? $daysInEndMonth : $originalStart->day, 0, 0, 0);//clone $startingDate;
        $count = $this->{strtolower($type)}()
            ->withTrashed()
            ->whereDate('created_at', '>=', $startingDate)
            ->whereDate('created_at', '<', $endingDate)
            ->count();  

        return $count < $this->num_vtrams;
    }
}
