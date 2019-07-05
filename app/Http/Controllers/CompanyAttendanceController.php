<?php

namespace App\Http\Controllers;

use Controller;
use App\Attendance;
use App\Http\Requests\AttendanceRequest;

class CompanyAttendanceController extends Controller
{
    protected $identifierPath = 'company.project.briefing.attendance';

    public function postIndexHook()
    {
        $this->datatable['href'] = null;
    }
    
    public function store(AttendanceRequest $request, $companyId, $projectId, $briefingId)
    {
        $request->merge([
            'briefing_id' => $briefingId,
        ]);
        return parent::_store(func_get_args());
    }

    public function created($insert, $request, $args)
    {
        return '/company/'.$args[1].'/project/'.$args[2].'/briefing/'.$args[3].'/attendance';
    }

    public function update(AttendanceRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
