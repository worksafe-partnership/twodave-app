<?php

namespace App\Http\Controllers;

use Auth;
use Controller;
use Illuminate\Support\Facades\Mail;
use Evergreen\Generic\App\Role;
use App\ProjectSubcontractor;
use App\Mail\InviteEmail;
use App\UserProject;
use App\Template;
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

        $this->customValues['company'] = $company = Company::withTrashed()->findOrFail($this->parentId);
        $this->customValues['isPrincipalContractor'] = false;
        $ownerCompany = $company;

        if ($ownerCompany->is_principal_contractor && ($this->user->company_id == $ownerCompany->id || is_null($this->user->company_id))) {
            $this->customValues['isPrincipalContractor'] = true;
        }

        $this->customValues['projectAdmins'] = User::withTrashed()->where('company_id', '=', $this->parentId)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'project_admin');
            })
            ->pluck('name', 'id');

        $this->getProjectUsers($this->parentId, $this->customValues['isPrincipalContractor']);

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

        $this->customValues['otherCompanies'] = Company::where('id', '!=', $company->id)->pluck('name', 'id');
        $this->getCurrentSubcontractors();
        $this->getCurrentContractors();

        $this->customValues['isContractor'] = false;
        $role = $this->user->roles()->first()->slug;
        if ((in_array($this->user->company_id, array_merge((isset($this->record) ? [$this->record->company_id] : []), array_keys($this->customValues['selectedContractors']))) || $this->user->company_id == null) && $role != "supervisor") {
            $this->customValues['isContractor'] = true;
        }
    }

    public function view() // blocking soft deleted records being seen by users who can't see sd'ed items
    {
        $this->args = func_get_args();
        $this->record = Project::withTrashed()->findOrFail(end($this->args));
        return parent::_view($this->args);
    }

    protected function getProjectUsers($companyId, $isPrincipalContractor, $piCompanyId = null)
    {
        $companiesOnProject = ProjectSubcontractor::where('project_id', $this->id)->pluck('contractor_or_sub', 'company_id');
        $translation = ["CONTRACTOR" => " (C)", "SUBCONTRACTOR" => " (S)"];
        $selected = [];
        if ($this->pageType != 'create') {
            $users = UserProject::where('project_id', '=', $this->id)
                ->get();
            foreach ($users as $user) {
                $selected[$user->user_id] = true;
            }
        } else {
            $users = User::withTrashed()->where('company_id', '=', $this->parentId)
            ->whereHas('roles', function ($q) {
                $q->where('slug', '=', 'company_admin');
            })->get(["id"]);
            foreach ($users as $user) {
                $selected[$user->id] = true;
            }
        }
        $this->customValues['allUsers'] = [];
        $this->customValues['selectedUsers'] = $selected;
        if ($this->pageType == 'view') {
            $users = UserProject::where('project_id', $this->id)
                              ->join('users', 'user_projects.user_id', '=', 'users.id')
                              ->join('companies', 'companies.id', '=', 'users.company_id')
                              ->get(['companies.name as company_name', 'users.name', 'users.id', 'users.company_id']);

            foreach ($users as $user) {
                $temp = $user->name . " (" . $user['company_name'] . ")";
                if ($isPrincipalContractor && isset($companiesOnProject[$user->company_id])) {
                    $temp .= $translation[$companiesOnProject[$user->company_id]];
                }
                $this->customValues['allUsers'][$user['id']] = $temp;
            }

            if ($piCompanyId) {
                $piUsers = User::where('company_id', $piCompanyId)
                               ->join('companies', 'users.company_id', '=', 'companies.id')
                               ->get(['companies.name as c_name', 'users.name', 'users.id']);
                foreach ($piUsers as $piUser) {
                    $this->customValues['allUsers'][$piUser['id']] = $piUser->name . " (" . $piUser['c_name'] . ")";
                }
            }

        } else {
            // get all users for this company
            $users1 = User::withTrashed()
                         ->join('companies', 'users.company_id', '=', 'companies.id')
                         ->when(is_null($piCompanyId), function ($onlyMine) use ($companyId) {
                            $onlyMine->where('company_id', '=', $companyId);
                         })
                         ->when($piCompanyId, function ($mineAndPi) use ($companyId, $piCompanyId) {
                            $mineAndPi->whereIn('company_id', [$companyId, $piCompanyId]);
                         })
                         ->get(['companies.name as c_name', 'users.name', 'users.id']);

            foreach ($users1 as $user) {
                $this->customValues['allUsers'][$user['id']] = $user->name . " (" . $user['c_name'] . ")";
            }


            // get all users for this project (on create $this->id will be null so will return nothing)
            $users2 = ProjectSubcontractor::where('project_id', $this->id)
                                          ->join('companies', 'project_subcontractors.company_id', '=', 'companies.id')
                                          ->join('users', 'users.company_id', '=', 'companies.id')
                                          ->get(['companies.name as company_name', 'users.name', 'users.id', 'users.company_id']);

            foreach ($users2 as $user) {
                $temp = $user->name . " (" . $user['company_name'] . ")";
                if ($isPrincipalContractor && isset($companiesOnProject[$user->company_id])) {
                    $temp .= $translation[$companiesOnProject[$user->company_id]];
                }
                $this->customValues['allUsers'][$user['id']] = $temp;
            }
        }
    }

    protected function getCurrentSubcontractors()
    {
        $selected = [];
        if ($this->pageType != 'create') {
            $subs = ProjectSubcontractor::where([
                'project_id' => $this->id,
                'contractor_or_sub' => 'SUBCONTRACTOR'
            ])->get();
            foreach ($subs as $sub) {
                $selected[$sub->company_id] = true;
            }
        }
        $this->customValues['selectedSubs'] = $selected;
    }

    public function getCurrentContractors()
    {
        $selected = [];
        if ($this->pageType != 'create') {
            $subs = ProjectSubcontractor::where([
                'project_id' => $this->id,
                'contractor_or_sub' => 'CONTRACTOR'
            ])->get();
            foreach ($subs as $sub) {
                $selected[$sub->company_id] = true;
            }
        }
        $this->customValues['selectedContractors'] = $selected;
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
            'label' => ($this->record->company->vtrams_name ?? 'VTRAMS').' Tracker',
            'path' => '/company/'.$this->parentId.'/project/'.$this->id.'/tracker',
            'icon' => $trackerConfig['icon'],
            'order' => '600',
            'id' => 'vtramsTracker'
        ];

        $this->actionButtons[] = [
            'label' => 'Create '.($this->record->company->vtrams_name ?? 'VTRAMS'),
            'path' => '/company/'.$this->parentId.'/project/'.$this->id.'/vtram/create',
            'icon' => 'document-add',
            'order' => 700,
            'id' =>'createVtrams',
            'class' => 'create_vtram',
        ];

        // NO CHANGES REQUIRED FOR TEMPLATE ACCESS ON THIS CONTROLLER - USER WILL ALWAYS BE ADMIN, AND USE RECORD'S COMPANY ID!
        $templates = Template::whereIn('company_id', [$this->args[0], $this->user->company_id])
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
        if (isset($request['add_contractor']) && $request['add_contractor']) {
            $newContractor = $this->sortOutNewContractor($request, $insert);
            // add it to the contractor array so it's picked up during the SubContractor block.
            if ($newContractor['company']) {
                $request['contractors'][] = (string) $newContractor['company']->id;
            }
            if ($newContractor['user']) {
                $request['users'][] = $newContractor['user']->id;
            }
        }

        if (isset($request['contractors']) && count($request['contractors']) > 0) {
            $toInsert = [];
            foreach ($request['contractors'] as $con) {
                $toInsert[] = [
                    'company_id' => $con,
                    'project_id' => $insert->id,
                    'contractor_or_sub' => 'CONTRACTOR'
                ];
            }
            ProjectSubcontractor::insert($toInsert);
        }

        if (isset($request['add_subcontractor']) && $request['add_subcontractor']) {
            $newSub = $this->sortOutNewSubContractor($request, $insert);
            // add it to the subcontractor array so it's picked up during the SubContractor block.
            if ($newSub['company']) {
                $request['subcontractors'][] = (string) $newSub['company']->id;
            }
            if ($newSub['user']) {
                $request['users'][] = $newSub['user']->id;
            }
        }

        if (isset($request['subcontractors']) && count($request['subcontractors']) > 0) {
            $toInsert = [];
            foreach ($request['subcontractors'] as $sub) {
                $toInsert[] = [
                    'company_id' => $sub,
                    'project_id' => $insert->id,
                    'contractor_or_sub' => 'SUBCONTRACTOR'
                ];
            }
            ProjectSubcontractor::insert($toInsert);
        }

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

        if (isset($request['contractors']) && count($request['contractors']) > 0) {
            $toInsert = [];
            foreach ($request['contractors'] as $con) {
                $toInsert[] = [
                    'company_id' => $con,
                    'project_id' => $insert->id,
                    'contractor_or_sub' => 'CONTRACTOR'
                ];
            }
            ProjectSubcontractor::insert($toInsert);
        }

        if (isset($request['subcontractors']) && count($request['subcontractors']) > 0) {
            $toInsert = [];
            foreach ($request['subcontractors'] as $sub) {
                $toInsert[] = [
                    'company_id' => $sub,
                    'project_id' => $insert->id,
                    'contractor_or_sub' => 'SUBCONTRACTOR'
                ];
            }
            ProjectSubcontractor::insert($toInsert);
        }
    }

    public function updated($updated, $orig, $request, $args)
    {
        $user = Auth::user();
        if (is_null($user->company_id)) {
            ProjectSubcontractor::where('project_id', $updated->id)->delete();
        } else if ($updated->company_id == $user->company_id) {
            ProjectSubcontractor::where('project_id', $updated->id)->delete();
        } else {
            $contractor = ProjectSubcontractor::where([
                                                    'project_id' => $updated->id,
                                                    'company_id' => $user->company_id,
                                                    'contractor_or_sub' => 'CONTRACTOR'
                                                ])->count();
            if ($contractor) {
                ProjectSubcontractor::where('project_id', $updated->id)->where('contractor_or_sub', 'SUBCONTRACTOR')->delete();
            }
        }

        if (isset($request['add_contractor']) && $request['add_contractor']) {
            $newContractor = $this->sortOutNewContractor($request, $updated);
            // add it to the contractor array so it's picked up during the SubContractor block.
            if ($newContractor['company']) {
                $request['contractors'][] = (string) $newContractor['company']->id;
            }
            if ($newContractor['user']) {
                $request['users'][] = $newContractor['user']->id;
            }
        }
        if (isset($request['contractors']) && count($request['contractors']) > 0) {
            $toInsert = [];
            foreach ($request['contractors'] as $con) {
                $toInsert[] = [
                    'company_id' => $con,
                    'project_id' => $updated->id,
                    'contractor_or_sub' => 'CONTRACTOR'
                ];
            }
            ProjectSubcontractor::insert($toInsert);
        }

        if (isset($request['add_subcontractor']) && $request['add_subcontractor']) {
            $newSub = $this->sortOutNewSubContractor($request, $updated);
            // add it to the subcontractor array so it's picked up during the SubContractor block.
            if ($newSub['company']) {
                $request['subcontractors'][] = (string) $newSub['company']->id;
            }
            if ($newSub['user']) {
                $request['users'][] = $newSub['user']->id;
            }
        }

        if (isset($request['subcontractors']) && count($request['subcontractors']) > 0) {
            $toInsert = [];
            foreach ($request['subcontractors'] as $sub) {
                $toInsert[] = [
                    'company_id' => $sub,
                    'project_id' => $updated->id,
                    'contractor_or_sub' => 'SUBCONTRACTOR'
                ];
            }
            ProjectSubcontractor::insert($toInsert);
        }

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

    protected function sortOutNewContractor($request, $update)
    {
        $fromCompany = Company::findOrFail($update['company_id']);

        $company = null;
        $user = null;

        if (isset($request['company_name_con'])) {
            $data = [
                'name' => $request['company_name_con'],
                'short_name' => $request['short_name_con'],
                'contact_name' => $request['contact_name_con'],
                'email' => $request['email_con'],
                'phone' => $request['phone_con'],
            ];

            $fields = [
                'review_timescale',
                'vtrams_name',
                'low_risk_character',
                'med_risk_character',
                'high_risk_character',
                'no_risk_character',
                'primary_colour',
                'secondary_colour',
                'light_text',
                'accept_label',
                'amend_label',
                'reject_label',
                'main_description',
                'post_risk_assessment_text',
                'show_document_ref_on_pdf',
                'show_message_on_pdf',
                'message',
                'show_revision_no_on_pdf'
            ];

            foreach ($fields as $field) {
                $data[$field] = $fromCompany[$field];
            }

            $company = Company::create($data);

            if ($company) {
                $user = User::create([
                    'name' => $request['company_admin_name_con'],
                    'email' => $request['company_admin_email_con'],
                    'company_id' => $company['id'],
                    'password' => 'USER CREATED THROUGH NEW CONTRACTOR - PASSWORD TBC',
                ]);

                $role = Role::where('slug', 'company_admin')->first();
                $user->roles()->attach([$role->id]);

                if ($user) {
                    $this->sendInvite($user);
                }
            }
        }
        return ['company' => $company, 'user' => $user];
    }

    protected function sortOutNewSubContractor($request, $update)
    {
        $fromCompany = Company::findOrFail($update['company_id']);

        $company = null;
        $user = null;

        if (isset($request['company_name'])) {
            $data = [
                'name' => $request['company_name'],
                'short_name' => $request['short_name'],
                'contact_name' => $request['contact_name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
            ];

            $fields = [
                'review_timescale',
                'vtrams_name',
                'low_risk_character',
                'med_risk_character',
                'high_risk_character',
                'no_risk_character',
                'primary_colour',
                'secondary_colour',
                'light_text',
                'accept_label',
                'amend_label',
                'reject_label',
                'main_description',
                'post_risk_assessment_text',
                'show_document_ref_on_pdf',
                'show_message_on_pdf',
                'message',
                'show_revision_no_on_pdf'
            ];

            foreach ($fields as $field) {
                $data[$field] = $fromCompany[$field];
            }

            $company = Company::create($data);

            if ($company) {
                $user = User::create([
                    'name' => $request['company_admin_name'],
                    'email' => $request['company_admin_email'],
                    'company_id' => $company['id'],
                    'password' => 'USER CREATED THROUGH NEW SUBCONTRACTOR - PASSWORD TBC',
                ]);

                $role = Role::where('slug', 'company_admin')->first();
                $user->roles()->attach([$role->id]);

                if ($user) {
                    $this->sendInvite($user);
                }
            }
        }
        return ['company' => $company, 'user' => $user];
    }

    protected function sendInvite(User $user)
    {
        $token = app('auth.password.broker')->createToken($user);

        Mail::to($user->email)
        ->send(new InviteEmail($user, $token));

        return back();
    }
}
