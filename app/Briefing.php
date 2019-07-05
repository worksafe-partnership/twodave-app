<?php

namespace App;

use Carbon;
use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Briefing extends Model
{
    use SoftDeletes;
    protected $table = 'briefings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'vtram_id',
        'briefed_by',
        'name',
        'notes'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'project_id',
                'vtram_id',
                'briefed_by',
                'name',
                'notes',
                'deleted_at',
                'created_at',
            ]);

        $query->where('project_id', '=', $parent);

        return app('datatables')->of($query)
            ->editColumn('project_id', function ($item) {
                $project = $item->project;
                if (!is_null($project)) {
                    return $item->project->name;
                }
                return 'Not Selected';
            })
            ->editColumn('vtram_id', function ($item) {
                $vtram = $item->vtram;
                if (!is_null($vtram)) {
                    return $item->vtram->name;
                }
                return 'Not Selected';
            })
            ->editColumn('created_at', function ($item) {
                return $item->createdTimestamp();
            })
            ->make('query');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function vtram()
    {
        return $this->belongsTo(Vtram::class, 'vtram_id', 'id');
    }

    public function createdTimestamp()
    {
        if ($this->created_at !== null) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->timestamp;
        }
        return '';
    }
}
