<?php

namespace App;

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
        'is_principal_contractor'
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
}
