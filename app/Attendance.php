<?php

namespace App;

use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;
    protected $table = 'attendance';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'briefing_id',
        'file_id'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->withTrashed(can('permanentlyDelete', $identifier))->select([
                'id',
                'briefing_id',
                'file_id',
                'deleted_at'
            ]);

        return Datatables::of($query)->make(true);
    }
}
