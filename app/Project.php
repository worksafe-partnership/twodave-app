<?php

namespace App;

use Auth;
use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    use SoftDeletes;
    protected $table = 'projects';
    protected $softCascade = ['vtrams', 'briefings'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ref',
        'company_id',
        'project_admin',
        'principle_contractor',
        'principle_contractor_name',
        'principle_contractor_email',
        'client_name',
        'review_timescale',
        'show_contact'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $user = Auth::user();

        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'name',
                'ref',
                'company_id',
                'project_admin',
                'principle_contractor',
                'principle_contractor_name',
                'principle_contractor_email',
                'client_name',
                'review_timescale',
                'show_contact',
                'deleted_at'
            ]);

        if ($identifier['identifier_path'] == 'project') {
            $ids = $user->projectCompanyIds();
            $query->whereIn('id', $ids);
        }

        if ($identifier['identifier_path'] == 'company.project') {
            $query->where('company_id', '=', $parent);
        }

        return app('datatables')->of($query)
            ->rawColumns(['principle_contractor'])
            ->editColumn('review_timescale', function ($item) {
                return $item->reviewTimeScaleName();
            })
            ->editColumn('company_id', function ($item) {
                $company = $item->company;
                if (!is_null($company)) {
                    return $item->company->name;
                }
                return 'All';
            })
            ->editColumn('project_admin', function ($item) {
                $admin = $item->admin;
                if (!is_null($admin)) {
                    return $item->admin->name;
                }
                return 'None Selected';
            })
            ->editColumn('principle_contractor', function ($item) {
                return '<div class="checkbox">
                            <div class="control">
                                <div class="b-checkbox is-primary">
                                    <input class="styled" type="checkbox" id="active" disabled="" '.($item->principle_contractor ? 'checked' : '').'>
                                    <label for="active" style="padding:0px"></label>
                                </div>
                            </div>
                        </div>';
            })
            ->make("query");
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'project_admin', 'id');
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
        return $this->belongsToMany(User::class, 'user_projects');
    }

    public function userOnProject($userId)
    {
        $count = $this->users()->where('user_id', '=', $userId)->count();
        if ($count == 1) {
            return true;
        }
        return false;
    }

    public function vtrams()
    {
        if (is_null($this->deleted_at)) {
            return $this->hasMany(Vtram::class, 'project_id', 'id');
        } else {
            return $this->hasMany(Vtram::class, 'project_id', 'id')->withTrashed();
        }
    }

    public function briefings()
    {
        return $this->hasMany(Briefing::class, 'project_id', 'id');
    }

    public function delete()
    {
        if (!is_null($this->deleted_at)) {
            foreach ($this->vtrams as $vtram) {
                $vtram->delete();
            }
        }
        parent::delete();
    }

    public function subContractorLinks()
    {
        return $this->hasMany(ProjectSubcontractor::class, 'project_id', 'id');
    }
}
