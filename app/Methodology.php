<?php

namespace App;

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
}
