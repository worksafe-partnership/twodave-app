<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\UserProject;
use App\Project;
use App\Company;
use App\User;
use App\Http\Requests\ProjectRequest;

class ProjectController extends CompanyProjectController
{
    protected $identifierPath = 'project';

    public function bladeHook()
    {
        if ($this->user->company_id !== null && $this->record !== null) {
            if ($this->user->company_id !== $this->record->company_id) {
                abort(404);
            }
        }
        if ($this->user->inRole('supervisor') && $this->record !== null) {
            if (!$this->record->userOnProject($this->user->id)) {
                abort(404);
            }
        }

        $this->customValues['company'] = Company::findOrFail($this->user->company_id);
        $this->customValues['projectAdmins'] = User::where('company_id', '=', $this->user->company_id)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'project_admin');
            })
            ->pluck('name', 'id');

        $timescales = config('egc.review_timescales');
        $timescales[0] = "Use Company Schedule";
        $this->customValues['timescales'] = $timescales;

        $this->getProjectUsers($this->user->company_id);
    }

    public function viewHook()
    {
        $briefConfig = config('structure.project.briefing.config');
        $this->actionButtons['briefings'] = [
            'label' => ucfirst($this->pageType)." ".$briefConfig['plural'],
            'path' => '/project/'.$this->id.'/briefing',
            'icon' => $briefConfig['icon'],
            'order' => '550',
            'id' => 'briefingsList'
        ];

        $trackerConfig = config('structure.project.tracker.config');
        $this->actionButtons['tracker'] = [
            'label' => "VTRAMS Tracker",
            'path' => '/project/'.$this->id.'/tracker',
            'icon' => $trackerConfig['icon'],
            'order' => '600',
            'id' => 'vtramsTracker'
        ];
    }

    public function viewEditHook()
    {
        // no withTrashed() - hide from lower level users when soft deleted.
        $this->record = Project::findOrFail(end($this->args));
    }

    public function store(ProjectRequest $request, $companyId = null)
    {
        $request->merge([
            'company_id' => Auth::user()->company_id
        ]);
        return parent::_store(func_get_args());
    }

    public function update(ProjectRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
