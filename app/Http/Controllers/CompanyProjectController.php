<?php

namespace App\Http\Controllers;

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

        $this->customValues['subcontractors'] = Company::where('id', '!=', $company->id)->pluck('name', 'id');
        $this->getCurrentSubcontractors();
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
        $this->customValues['allUsers'] = [];
        $this->customValues['selectedUsers'] = $selected;
        if ($this->pageType == 'view') {
            $users = UserProject::where('project_id', '=', $this->id)
                ->join('users', 'user_projects.user_id', '=', 'users.id')
                ->join('companies', 'companies.id', '=', 'users.company_id')
                ->get(['companies.name as company_name', 'users.name', 'users.id']);

            foreach ($users as $user) {
                $this->customValues['allUsers'][$user['id']] = $user->name . " (" . $user['company_name'] . ")";
            }
        } else {
            $users1 = User::withTrashed()
                         ->join('companies', 'users.company_id', '=', 'companies.id')
                         ->where('company_id', '=', $companyId)
                         ->get(['companies.name as c_name', 'users.name', 'users.id']);

            foreach ($users1 as $user) {
                $this->customValues['allUsers'][$user['id']] = $user->name . " (" . $user['c_name'] . ")";
            }

            $users2 = ProjectSubcontractor::where('project_id', $this->id)
                                          ->join('companies', 'project_subcontractors.company_id', '=', 'companies.id')
                                          ->join('users', 'users.company_id', '=', 'companies.id')
                                          ->get(['companies.name as company_name', 'users.name', 'users.id']);

            foreach ($users2 as $user) {
                $this->customValues['allUsers'][$user['id']] = $user->name . " (" . $user->company_name . ")";
            }
        }
    }

    protected function getCurrentSubcontractors()
    {
        $selected = [];
        if ($this->pageType != 'create') {
            $subs = ProjectSubcontractor::where('project_id', '=', $this->id)->get();
            foreach ($subs as $sub) {
                $selected[$sub->company_id] = true;
            }
        }
        $this->customValues['selectedSubs'] = $selected;
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
        if (isset($request['add_subcontractor']) && $request['add_subcontractor']) {
            $new = $this->sortOutNewContractor($request, $insert);
            // add it to the subcontractor array so it's picked up during the SubContractor block.
            if ($new['company']) {
                $request['subcontractors'][] = (string) $new['company']->id;
            }
            if ($new['user']) {
                $request['users'][] = $new['user']->id;
            }
        }

        if (isset($request['subcontractors']) && count($request['subcontractors']) > 0) {
            $toInsert = [];
            foreach ($request['subcontractors'] as $sub) {
                $toInsert[] = [
                    'company_id' => $sub,
                    'project_id' => $insert->id,
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

        if (isset($request['subcontractors']) && count($request['subcontractors']) > 0) {
            $toInsert = [];
            foreach ($request['subcontractors'] as $sub) {
                $toInsert[] = [
                    'company_id' => $sub,
                    'project_id' => $insert->id,
                ];
            }
            ProjectSubcontractor::insert($toInsert);
        }
    }

    public function updated($updated, $orig, $request, $args)
    {
        if (isset($request['add_subcontractor']) && $request['add_subcontractor']) {
            $new = $this->sortOutNewContractor($request, $updated);
            // add it to the subcontractor array so it's picked up during the SubContractor block.
            if ($new['company']) {
                $request['subcontractors'][] = (string) $new['company']->id;
            }
            if ($new['user']) {
                $request['users'][] = $new['user']->id;
            }
        }

        ProjectSubcontractor::where('project_id', '=', $updated->id)->delete();
        if (isset($request['subcontractors']) && count($request['subcontractors']) > 0) {
            $toInsert = [];
            foreach ($request['subcontractors'] as $sub) {
                $toInsert[] = [
                    'company_id' => $sub,
                    'project_id' => $updated->id,
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
                'logo',
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
