<?php

namespace App\Http\Controllers;

use Auth;
use Carbon;
use App\Vtram;
use Controller;

class DashboardController extends Controller
{
    protected $identifierPath = 'dashboard';
    protected $datatableFields = [
        'company_id',
        // 'plan number', // "leave for now" - CP
        'id',
        'company_id',
        'project_id',
        'name',
        'status',
        'created_by',
        'submitted_by',
        'submitted_date',
        'approved_date',
        'approved_by',
        'review_due'
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
        $statuses = Config('egc.vtram_status');
        unset($statuses['PREVIOUS']);
        if ($role != 'supervisor') {
            unset($statuses['EXTERNAL_REJECT']);
        }

        $submitted = Vtram::where('submitted_by', $user->id)
                            ->whereIn('status', $statuses)
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            ->get($this->datatableFields);

        $this->customValues['tables'][] = [
            'heading' => 'Submitted By You',
            'table-id' => 'submitted',
            'data' => $submitted,
        ];

        if (in_array($role, ['company_admin', 'contract_manager', 'project_admin'])) {
            if ($role == 'company_admin') {
                $roleCheck = ['contract_manager', 'project_admin', 'supervisor'];
            } else if ($role == 'contact_manager') {
                $roleCheck = ['project_admin', 'supervisor'];
            } else { // must be project admin based on above if!
                $roleCheck = ['supervisor'];
            }

            $pending = Vtram::where('status', "PENDING")
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            ->whereHas('submitted', function ($roleQuery) use ($roleCheck) {
                                $roleQuery->whereHas('roles', function ($sub) use ($roleCheck) {
                                    $sub->whereIn('slug', $roleCheck);
                                });
                            })
                            ->get($this->datatableFields);

            $this->customValues['tables'][] = [
                'heading' => 'Pending',
                'table-id' => 'pending',
                'data' => $pending,
            ];


            // weird indentation but PSR2 demands it?
            $tracker = Vtram::when($user->company_id !== null, function ($q) use ($user) {
                            $q->where('company_id', '=', $user->company_id);
            })
            ->when($role == "project_admin", function ($pm) use ($user) {
                $pm->whereHas('project', function ($project) use ($user) {
                    $project->where('project_admin', $user->id);
                });
            })
            ->where('status', '!=', 'PREVIOUS')
            ->get($this->datatableFields);

            $this->customValues['tables'][] = [
                'heading' => 'VTRAMS Tracker',
                'table-id' => 'tracker',
                'data' => $tracker,
            ];

            $rejected = Vtram::whereIn('status', ["EXTERNAL_REJECT", "REJECTED"])
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
                'heading' => 'Rejected',
                'table-id' => 'rejected',
                'data' => $rejected,
            ];
        }
    }
}
