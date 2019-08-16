<?php

namespace App\Http\Controllers;

use Controller;
use App\Briefing;
use App\Vtram;
use App\Project;
use App\Http\Requests\BriefingRequest;

class BriefingController extends CompanyBriefingController
{
    protected $identifierPath = 'project.briefing';

    public function bladeHook()
    {
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->project->company_id) {
                abort(404);
            }
        }
        if ($this->user->inRole('supervisor') && $this->record !== null) {
            if (!$this->record->project->userOnProject($this->user->id)) {
                abort(404);
            }
        }
        parent::bladeHook();
    }

    public function indexHook()
    {
        if ($this->user->company_id !== null) {
            $project = Project::findOrFail($this->parentId);
            if ($this->user->company_id !== $project->company_id) {
                abort(404);
            }
        }
    }

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

    public function view() // blocking soft deleted records being seen by users who can't see sd'ed items
    {
        $this->args = func_get_args();
        $this->record = Briefing::findOrFail(end($this->args));
        return parent::_view($this->args);
    }

    public function update(BriefingRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
