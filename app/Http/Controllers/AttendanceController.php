<?php

namespace App\Http\Controllers;

use Controller;
use App\Attendance;
use App\Http\Requests\AttendanceRequest;

class AttendanceController extends CompanyAttendanceController
{
    protected $identifierPath = 'project.briefing.attendance';

    public function postIndexHook()
    {
        $this->datatable['href'] = null;
    }
    
    public function store(AttendanceRequest $request, $projectId, $briefingId, $otherId = null)
    {
        $request->merge([
            'briefing_id' => $briefingId,
        ]);
        return parent::_store([
            $request,
            $projectId,
            $briefingId
        ]);
    }

    public function created($insert, $request, $args)
    {
        return '/project/'.$args[1].'/briefing/'.$args[2].'/attendance';
    }

    public function update(AttendanceRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
