<?php

namespace App\Http\Controllers;

use App\Template;
use Auth;
use Carbon;
use App\Vtram;
use App\VtramUser;
use Controller;

class DashboardController extends Controller
{
    protected $identifierPath = 'dashboard';
    protected $datatableFields = [
        'id',
        'company_id',
        'project_id',
        'number',
        'name',
        'reference',
        'status',
        'approved_date',
        'review_due',
        'revision_number',
        'submitted_by',
        'approved_by',
        'resubmit_by',
        'submitted_date',
        'review_due',
        'resubmit_by',
    ];

    public function __construct()
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }

    public function view()
    {
        $user = Auth::user();
        $role = $user->roles->first()->slug;

        if ($role == "supervisor") {
            return redirect('/project');
        }
        if (in_array($role, ['evergreen','admin'])) {
            return redirect('/company');
        }

        $this->getTables($user, $role);
        $this->customValues['companyId'] = $user->company_id;
        $this->customValues['nowCarbon'] = Carbon::now();
        $this->customValues['twoWeeksCarbon'] = $this->customValues['nowCarbon']->copy()->addWeeks(2);

        return parent::_view();
    }

    public function getTables($user, $role)
    {
        $this->user = Auth::user();
        $statuses = Config('egc.vtram_status');
        unset($statuses['PREVIOUS']);
        if ($role != 'supervisor') {
            unset($statuses['EXTERNAL_REJECT']);
        }

        $submitted = Vtram::with(['company'])
                            ->where('submitted_by', $user->id)
                            ->whereIn('status', $statuses)
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            ->get($this->datatableFields);

        $this->customValues['tables'][] = [
            'heading' => 'Submitted By You ('.$submitted->count().')',
            'table-id' => 'submitted',
            'data' => $submitted,
        ];

        if (in_array($role, ['company_admin', 'contract_manager', 'project_admin'])) {
            if ($role == 'company_admin') {
                $roleCheck = ['company_admin','contract_manager', 'project_admin', 'supervisor'];
            } else if ($role == 'contract_manager') {
                $roleCheck = ['project_admin', 'supervisor'];
            } else { // must be project admin based on above if!
                $roleCheck = ['supervisor'];
            }

            $pending = Vtram::with(['submitted.roles', 'company'])
                            ->where('status', "=", "PENDING")
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            ->whereHas('submitted', function ($roleQuery) use ($roleCheck) {
                                $roleQuery->whereHas('roles', function ($sub) use ($roleCheck) {
                                    $sub->whereIn('slug', $roleCheck);
                                });
                            })
                            ->when(!is_null($user->company_id), function ($accessCheck) use ($user) {
                                // check the vtram has no vtram users (VtramUser) attached to it
                                $accessCheck->where(function ($noUsers) {
                                    $noUsers->whereIn('vtrams.id', Vtram::doesntHave('vtramsUsers')->pluck('vtrams.id'))
                                        ->where('status', '=', 'PENDING');
                                });
                                // or the user id is in the VTramUser list for the project
                                $accessCheck->orWhere(function ($myProjects) use ($user) {
                                    $myProjects->whereIn('vtrams.id', VtramUser::where('user_id', $user->id)->pluck('vtrams_id')->toArray())
                                        ->where('status', '=', 'PENDING');
                                });
                            })
                            ->get($this->datatableFields);

            $this->customValues['tables'][] = [
                'heading' => 'Pending ('.$pending->count().')',
                'table-id' => 'pending',
                'data' => $pending,
            ];


            // weird indentation but PSR2 demands it?
            $tracker = Vtram::with(['project','company'])
                              ->when($user->company_id !== null, function ($q) use ($user) {
                            $q->where('company_id', '=', $user->company_id);
            })
            ->when($role == "project_admin", function ($pm) use ($user) {
                $pm->whereHas('project', function ($project) use ($user) {
                    $project->where('project_admin', $user->id);
                });
            })
            ->where('status', '!=', 'PREVIOUS')
            ->when(!is_null($user->company_id), function ($accessCheck) use ($user) {
                // check the vtram has no vtram users (VtramUser) attached to it
                $accessCheck->where(function ($noUsers) {
                    $noUsers->whereIn('vtrams.id', Vtram::doesntHave('vtramsUsers')->pluck('vtrams.id'));
                });
                // or the user id is in the VTramUser list for the project
                $accessCheck->orWhere(function ($myProjects) use ($user) {
                    $myProjects->whereIn('vtrams.id', VtramUser::where('user_id', $user->id)->pluck('vtrams_id')->toArray());
                });
            })
            ->get($this->datatableFields);

            $this->customValues['tables'][] = [
                'heading' => ($this->user->company->vtrams_name ?? 'VTRAMS').' Tracker ('.$tracker->count().')',
                'table-id' => 'tracker',
                'data' => $tracker,
            ];

            $rejected = Vtram::with(['project.admin','company'])
                            ->whereIn('status', ["EXTERNAL_REJECT", "REJECTED",'AMEND','EXTERNAL_AMEND'])
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            ->when($role == 'project_admin', function ($pa) use ($user) {
                                $pa->whereHas('project', function ($project) use ($user) {
                                    $project->where('project_admin', '=', $user->id);
                                });
                            })
                            ->get($this->datatableFields);

            $this->customValues['tables'][] = [
                'heading' => 'Rejected ('.$rejected->count().')',
                'table-id' => 'rejected',
                'data' => $rejected,
            ];

            $templates = Template::datatableAllQuery(false, config("structure.template.config"))->get();

            $this->customValues['tables'][] = [
                'heading' => 'Templates ('.$templates->count().')',
                'table-id' => 'templates',
                'data' => $templates,
            ];
        }
    }
}
