<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectSubcontractor extends Model
{
    protected $table = 'project_subcontractors';

    protected $fillable = [
        'project_id',
        'company_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
