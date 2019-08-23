<?php

namespace App;

use EGFiles;
use Storage;
use Yajra\DataTables\Datatables;
use Illuminate\Database\Eloquent\Model;

class Methodology extends Model
{
    protected $table = 'methodologies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category',
        'title',
        'entity',
        'entity_id',
        'text_before',
        'text_after',
        'image',
        'image_on',
        'list_order',
        'icon_main_heading',
        'icon_sub_heading'
    ];

    public static function scopeDatatableAll($query, $parent, $identifier)
    {
        $query->select([
                'id',
                'category',
                'entity',
                'entity_id',
                'text_before',
                'text_after',
                'image',
                'image_on',
                'list_order'
            ]);

        return Datatables::of($query)->make(true);
    }

    public function icons()
    {
        return $this->hasMany(Icon::class, 'methodology_id', 'id');
    }

    public function instructions()
    {
        return $this->hasMany(Instruction::class, 'methodology_id', 'id');
    }

    public function tableRows()
    {
        return $this->hasMany(TableRow::class, 'methodology_id', 'id');
    }

    public function entityRecord()
    {
        if ($this->entity == 'VTRAM') {
            return $this->belongsTo(Vtram::class, 'entity_id', 'id');
        } else if ($this->entity == 'TEMPLATE') {
            return $this->belongsTo(Template::class, 'entity_id', 'id');
        }
    }

    public function delete()
    {
        if (!is_null($this->image)) {
            $file = EGFiles::findOrFail($this->image);
            Storage::disk('local')->delete($file->location);
            $file->forceDelete();
        }

        foreach ($this->instructions as $process) {
            $process->delete();
        }

        parent::delete();
    }
}
