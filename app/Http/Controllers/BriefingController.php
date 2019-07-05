<?php

namespace App\Http\Controllers;

use Controller;
use App\Briefing;
use App\Vtram;
use App\Http\Requests\BriefingRequest;

class BriefingController extends CompanyBriefingController
{
    protected $identifierPath = 'project.briefing';

    public function viewHook()
    {
        $attendanceConfig = config('structure.project.briefing.attendance.config');
        $this->actionButtons['attendance'] = [
            'label' => ucfirst($this->pageType)." ".$attendanceConfig['plural'],
            'path' => '/project/'.$this->parentId.'/briefing/'.$this->id.'/attendance',
            'icon' => $attendanceConfig['icon'],
            'order' => '400',
            'id' => 'briefingsList'
        ];
    }
    
    public function store(BriefingRequest $request, $projectId, $otherId = null)
    {
        $request->merge([
            'project_id' => $projectId,
        ]);
        unset($otherId);
        return parent::_store([
            $request,
            $projectId
        ]);
    }

    public function update(BriefingRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
