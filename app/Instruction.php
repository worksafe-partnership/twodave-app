<?php

namespace App;

use EGFiles;
use Storage;
use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Instruction extends Model
{
    protected $table = 'instructions';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'label',
        'heading',
        'list_order',
        'image',
        'methodology_id'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->select([
                'id',
                'description',
                'label',
                'heading',
                'list_order',
                'image',
                'methodology_id'
            ]);

        return Datatables::of($query)->make(true);
    }

    public function delete()
    {
        if ($this->image != null) {
            $file = EGFiles::findOrFail($this->image);
            Storage::disk('local')->delete($file->location);
            $file->forceDelete();
        }
        parent::delete();
    }
}
