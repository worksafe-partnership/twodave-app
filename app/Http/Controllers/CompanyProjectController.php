<?php

namespace App\Http\Controllers;

use Controller;
use App\Project;
use App\Company;
use App\User;
use App\Http\Requests\ProjectRequest;

class CompanyProjectController extends Controller
{
    protected $identifierPath = 'company.project';

    public function bladeHook()
    {
        $this->customValues['projectAdmins'] = User::where('company_id', '=', $this->parentId)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'project_admin');
            })
            ->pluck('name', 'id');
    }

    public function viewHook()
    {
        $briefConfig = config('structure.company.project.briefing.config');
        $this->actionButtons['briefings'] = [
            'label' => ucfirst($this->pageType)." ".$briefConfig['plural'],
            'path' => '/company/'.$this->parentId.'/project/'.$this->id.'/briefing',
            'icon' => $briefConfig['icon'],
            'order' => '400',
            'id' => 'briefingsList'
        ];

        $vtramConfig = config('structure.company.project.vtram.config');
        $this->actionButtons['vtrams'] = [
            'label' => ucfirst($this->pageType)." ".$vtramConfig['plural'],
            'path' => '/company/'.$this->parentId.'/project/'.$this->id.'/vtram',
            'icon' => $vtramConfig['icon'],
            'order' => '500',
            'id' => 'vtramsList'
        ];

        $trackerConfig = config('structure.company.project.tracker.config');
        $this->actionButtons['tracker'] = [
            'label' => "VTRAM Tracker",
            'path' => '/company/'.$this->parentId.'/project/'.$this->id.'/tracker',
            'icon' => $trackerConfig['icon'],
            'order' => '600',
            'id' => 'vtramsTracker'
        ];
    }

    public function store(ProjectRequest $request, $companyId)
    {
        $request->merge([
            'company_id' => $companyId
        ]);
        return parent::_store(func_get_args());
    }

    public function update(ProjectRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
