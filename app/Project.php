<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $table = 'projects';
    
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

        $query->where('company_id', '=', $parent);

        return app('datatables')->of($query)
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
}
