<?php

namespace App;

use Carbon;
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
                'deleted_at',
                'created_at'
            ]);

        $query->where('briefing_id', '=', $parent);

        return app('datatables')->of($query)
            ->rawColumns(['file_id'])
            ->editColumn('file_id', function ($item) {
                return '<a href="/image/'.$item->file_id.'" target="_blank" class="button is-primary">View Document</a>';
            })
            ->editColumn('created_at', function ($item) {
                if (is_null($item->created_at)) {
                    return '';
                }
                return Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('d/m/Y H:i');
            })
            ->make('query');
    }
}
