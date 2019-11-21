<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use App\ProjectSubcontractor;
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

        $this->customValues['isPrincipalContractor'] = false;
        if ($this->pageType == 'create') { // there's no record, owner company must be your company.
            $ownerCompany = Company::findOrFail($this->user->company_id);
        } else {
            $ownerCompany = Company::findOrFail($this->record->company_id);
        }
        if ($ownerCompany->is_principal_contractor && $this->user->company_id == $ownerCompany->id) {
            $this->customValues['isPrincipalContractor'] = true;
        }

        $this->customValues['company'] = $company = Company::findOrFail($this->user->company_id);
        $this->customValues['projectAdmins'] = User::where('company_id', '=', $this->user->company_id)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'project_admin');
            })
            ->pluck('name', 'id');

        $timescales = config('egc.review_timescales');
        $timescales[0] = "Use Company Schedule";
        $this->customValues['timescales'] = $timescales;

        $this->getProjectUsers($this->user->company_id, $this->customValues['isPrincipalContractor']);

        $this->customValues['otherCompanies'] = Company::where('id', '!=', $company->id)->pluck('name', 'id');
        $this->getCurrentSubcontractors();
        $this->getCurrentContractors();

        $role = $this->user->roles()->first()->slug;
        $this->customValues['isContractor'] = false;
        if (in_array($this->user->company_id, array_merge((isset($this->record) ? [$this->record->company_id] : []), array_keys($this->customValues['selectedContractors']))) && $role != "supervisor") {
            $this->customValues['isContractor'] = true;
        }
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

        $this->customValues['company'] = $this->user->company;

        $companies = [$this->record->company_id, $this->user->company_id];
        if ($this->user->company_id != $this->record->company_id) {
            $companiesWithAccess = ProjectSubcontractor::where('project_id', $this->id)->get();
            $myAccess = $companiesWithAccess->where('company_id', $this->user->company_id)->first();
            if ($myAccess) {
                if ($myAccess->contractor_or_sub = "SUBCONTRACTOR") {
                    $contractorIds = $companiesWithAccess->where('contractor_or_sub', 'CONTRACTOR')->pluck('company_id')->toArray();
                    foreach ($contractorIds as $id) {
                        $companies[] = $id;
                    }
                }
            }
        }
        $companies = array_unique($companies);
        $templates = Template::whereIn('company_id', $companies)
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
        // END TEMPLATES
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
