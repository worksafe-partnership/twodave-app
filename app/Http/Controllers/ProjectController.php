<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\Project;
use App\Company;
use App\User;
use App\Http\Requests\ProjectRequest;

class ProjectController extends CompanyProjectController
{
    protected $identifierPath = 'project';

    public function bladeHook()
    {
        if ($this->user->company_id !== null) {
            if ($this->user->company_id !== $this->record->company_id) {
                abort(404);
            }
        }

        $this->customValues['projectAdmins'] = User::where('company_id', '=', $this->user->company_id)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'project_admin');
            })
            ->pluck('name', 'id');
    }

    public function viewHook()
    {
        $briefConfig = config('structure.project.briefing.config');
        $this->actionButtons['briefings'] = [
            'label' => ucfirst($this->pageType)." ".$briefConfig['plural'],
            'path' => '/project/'.$this->id.'/briefing',
            'icon' => $briefConfig['icon'],
            'order' => '400',
            'id' => 'briefingsList'
        ];

        $vtramConfig = config('structure.project.vtram.config');
        $this->actionButtons['vtrams'] = [
            'label' => ucfirst($this->pageType)." ".$vtramConfig['plural'],
            'path' => '/project/'.$this->id.'/vtram',
            'icon' => $vtramConfig['icon'],
            'order' => '500',
            'id' => 'vtramsList'
        ];

        $trackerConfig = config('structure.project.tracker.config');
        $this->actionButtons['tracker'] = [
            'label' => "VTRAM Tracker",
            'path' => '/project/'.$this->id.'/tracker',
            'icon' => $trackerConfig['icon'],
            'order' => '600',
            'id' => 'vtramsTracker'
        ];
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
