<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\UserProject;
use App\Project;
use App\Company;
use App\User;
use App\Template;
use App\Http\Requests\ProjectRequest;

class ProjectController extends CompanyProjectController
{
    protected $identifierPath = 'project';

    public function editHook()
    {
        $this->formButtons['back_to_edit'] = [
            'class' => [
                'submitbutton',
                'button',
                'is-primary',
            ],
            'name' => 'back_to_edit',
            'label' => 'Save',
            'order' => 150,
            'value' => true,
        ];
    }

    public function bladeHook()
    {
        $permittedProjects = Auth::User()->projectCompanyIds();
        if (!is_null($this->record) && !in_array($this->record->id, $permittedProjects)) {
            abort(404);
        }

        // old access bits, irrelevant now, commenting for now just in case it's needed.
        // if ($this->user->company_id !== null && $this->record !== null) {
        //     if ($this->user->company_id !== $this->record->company_id) {
        //         abort(404);
        //     }
        // }
        // if ($this->user->inRole('supervisor') && $this->record !== null) {
        //     if (!$this->record->userOnProject($this->user->id)) {
        //         abort(404);
        //     }
        // }

        $this->customValues['company'] = $company = Company::findOrFail($this->user->company_id);
        $this->customValues['projectAdmins'] = User::where('company_id', '=', $this->user->company_id)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'project_admin');
            })
            ->pluck('name', 'id');

        $timescales = config('egc.review_timescales');
        $timescales[0] = "Use Company Schedule";
        $this->customValues['timescales'] = $timescales;

        $this->getProjectUsers($this->user->company_id);

        $this->customValues['subcontractors'] = Company::where('id', '!=', $company->id)->pluck('name', 'id');
        $this->getCurrentSubcontractors();
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
            'label' => ($this->user->company->vtrams_name ?? "VTRAMS")." Tracker",
            'path' => '/project/'.$this->id.'/tracker',
            'icon' => $trackerConfig['icon'],
            'order' => '600',
            'id' => 'vtramsTracker'
        ];

        $this->actionButtons[] = [
            'label' => 'Create '.($this->user->company->vtrams_name ?? 'VTRAMS'),
            'path' => '/project/'.$this->id.'/vtram/create',
            'icon' => 'document-add',
            'order' => 700,
            'id' =>'createVtrams',
        ];
        $templates = Template::whereIn('company_id', [$this->record->company_id, $this->user->company_id])
                                                   ->join('companies', 'templates.company_id', '=', 'companies.id')
                                                   ->where('status', 'CURRENT')
                                                   ->get([
                                                        'companies.name as company_name', 'templates.name', 'templates.id'
                                                    ]);
        $this->customValues['templates'] = [];
        foreach ($templates as $template) {
            $this->customValues['templates'][$template->id] = $template->name . " (" . $template->company_name .")";
        }
        $this->customValues['templates'] = collect($this->customValues['templates']);
        $this->customValues['path'] = $this->id.'/vtram/create';
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

    // commenting this guy out. It goes the eact same as the parent updated() function except the parent does a lot more. Function not required?
    // public function updated($record, $orig, $request, $args)
    // {
    //     if (isset($request['back_to_edit'])) {
    //         return $this->fullPath.'/edit';
    //     }
    // }

    public function update(ProjectRequest $request)
    {
        return parent::_update(func_get_args());
    }
}
