<?php

namespace App\Http\Controllers;

use Controller;
use App\UserProject;
use App\Project;
use App\Company;
use App\User;
use App\Http\Requests\ProjectRequest;

class CompanyProjectController extends Controller
{
    protected $identifierPath = 'company.project';

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
        $this->customValues['company'] = Company::withTrashed()->findOrFail($this->parentId);
        $this->customValues['projectAdmins'] = User::withTrashed()->where('company_id', '=', $this->parentId)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'project_admin');
            })
            ->pluck('name', 'id');

        $this->getProjectUsers($this->parentId);

        $timescales = config('egc.review_timescales');
        $timescales[0] = "Use Company Schedule";
        $limit = $this->customValues['company']->review_timescale;

        if (isset($this->record->review_timescale)) { // view / edit
            if ($this->record->review_timescale > $this->customValues['company']->review_timescale) {
                $limit = $this->record->review_timescale;
            }
        }

        foreach ($timescales as $key => $value) {
            if ($key > $limit) {
                unset($timescales[$key]);
            }
        }

        $this->customValues['timescales'] = $timescales;
    }

    public function view() // blocking soft deleted records being seen by users who can't see sd'ed items
    {
        $this->args = func_get_args();
        $this->record = Project::withTrashed()->findOrFail(end($this->args));
        return parent::_view($this->args);
    }

    protected function getProjectUsers($companyId)
    {
        $selected = [];
        if ($this->pageType != 'create') {
            $users = UserProject::where('project_id', '=', $this->id)
                ->get();
            foreach ($users as $user) {
                $selected[$user->user_id] = true;
            }
        }
        $this->customValues['selectedUsers'] = $selected;
        if ($this->pageType == 'view') {
            $this->customValues['allUsers'] = UserProject::where('project_id', '=', $this->id)
                ->join('users', 'user_projects.user_id', '=', 'users.id')
                ->pluck('name', 'users.id');
        } else {
            $this->customValues['allUsers'] = User::withTrashed()->where('company_id', '=', $companyId)
                ->pluck('name', 'id');
        }
    }

    public function viewHook()
    {
        $briefConfig = config('structure.company.project.briefing.config');
        $this->actionButtons['briefings'] = [
            'label' => ucfirst($this->pageType)." ".$briefConfig['plural'],
            'path' => '/company/'.$this->parentId.'/project/'.$this->id.'/briefing',
            'icon' => $briefConfig['icon'],
            'order' => '550',
            'id' => 'briefingsList'
        ];

        $trackerConfig = config('structure.company.project.tracker.config');
        $this->actionButtons['tracker'] = [
            'label' => 'VTRAMS Tracker',
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

    public function created($insert, $request, $args)
    {
        if (isset($request['users']) && count($request['users']) > 0) {
            $toInsert = [];
            foreach ($request['users'] as $user) {
                $toInsert[] = [
                    'user_id' => $user,
                    'project_id' => $insert->id,
                ];
            }
            UserProject::insert($toInsert);
        }
    }

    public function updated($updated, $orig, $request, $args)
    {
        UserProject::where('project_id', '=', $updated->id)->delete();
        if (isset($request['users']) && count($request['users']) > 0) {
            $toInsert = [];
            foreach ($request['users'] as $user) {
                $toInsert[] = [
                    'user_id' => $user,
                    'project_id' => $updated->id,
                ];
            }
            UserProject::insert($toInsert);
        }
        if (isset($request['back_to_edit'])) {
            return $this->fullPath.'/edit';
        }
    }
}
