<?php

namespace App\Http\Controllers;

use Controller;
use App\Briefing;
use App\Vtram;
use App\Http\Requests\BriefingRequest;

class CompanyBriefingController extends Controller
{
    protected $identifierPath = 'company.project.briefing';

    public function bladeHook()
    {
        if ($this->pageType == 'create') {
            $this->customValues['vtrams'] = Vtram::withTrashed()->where('project_id', '=', $this->parentId)
                ->where('status', '=', 'CURRENT')
                ->pluck('name', 'id');
        } else {
            $this->customValues['vtrams'] = Vtram::withTrashed()->where('project_id', '=', $this->parentId)
                ->pluck('name', 'id');
        }
    }

    public function viewHook()
    {
        $attendanceConfig = config('structure.company.project.briefing.attendance.config');
        $this->actionButtons['attendance'] = [
            'label' => ucfirst($this->pageType)." ".$attendanceConfig['plural'],
            'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/briefing/'.$this->id.'/attendance',
            'icon' => $attendanceConfig['icon'],
            'order' => '550',
            'id' => 'briefingsList'
        ];
    }

    public function store(BriefingRequest $request, $companyId, $projectId)
    {
        $request->merge([
            'project_id' => $projectId,
        ]);
        return parent::_store(func_get_args());
    }

    public function update(BriefingRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
