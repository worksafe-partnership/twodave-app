<?php

namespace App\Http\Controllers;

use Controller;
use App\Attendance;
use App\Http\Requests\AttendanceRequest;

class AttendanceController extends Controller
{
    protected $identifierPath = 'company.project.briefing.attendance';
    
    public function store(AttendanceRequest $request, $companyId, $projectId, $briefingId)
    {
        $request->merge([
            'briefing_id' => $briefingId,
        ]);
        return parent::_store(func_get_args());
    }

    public function update(AttendanceRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
