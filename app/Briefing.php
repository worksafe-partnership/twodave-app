<?php

namespace App;

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
                'deleted_at'
            ]);

        return Datatables::of($query)->make(true);
    }
}
