<?php

namespace App\Http\Controllers;

use App\Vtram;
use Controller;

class DashboardController extends Controller
{
    protected $identifierPath = 'dashboard';

    public function __construct()
    {
        $this->disableCreate = true;
        $this->disableEdit = true;
        $this->disableDelete = true;
        $this->disableRestore = true;
        $this->disablePermanetlyDelete = true;
        parent::__construct();
    }

    public function viewHook()
    {
        $user = $this->user;
        $role = $user->roles->first()->slug;

        $statuses = Config('egc.vtram_status');
        if ($role != 'supervisor') {
            unset($statuses['EXTERNAL_REJECT']);
        }

        $submitted = Vtram::where('submitted_by', $this->user->id)
                            ->whereIn('status', $statuses)
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            ->get([
                                // 'company name', // "leave for now" - CP
                                // 'plan number', // "leave for now" - CP
                                'name',
                                'status',
                                'created_by',
                                'submitted_by',
                                'submitted_date',
                                'approved_date',
                                'approved_by',
                                'review_due'
                            ]);

        $this->customValues['tables'][] = [
            'heading' => 'Submitted By You',
            'table-id' => 'submitted',
            'data' => $submitted,
        ];

        if ($role == 'company_admin') {
            $pending = Vtram::where('status', "PENDING")
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            ->whereHas('submitted', function ($roleQuery) {
                                $roleQuery->whereHas('roles', function ($sub) {
                                    $sub->whereIn('slug', ['contract_manager', 'project_admin', 'supervisor']);
                                });
                            })
                            ->get([
                                // 'company name', // "leave for now" - CP
                                // 'plan number', // "leave for now" - CP
                                'name',
                                'status',
                                'created_by',
                                'submitted_by',
                                'submitted_date',
                                'approved_date',
                                'approved_by',
                                'review_due'
                            ]);

            $this->customValues['tables'][] = [
                'heading' => 'Pending',
                'table-id' => 'pending',
                'data' => $pending,
            ];

            $rejected = Vtram::whereIn('status', ["EXTERNAL_REJECT", "REJECTED"])
                            ->when($user->company_id !== null, function ($q) use ($user) {
                                $q->where('company_id', '=', $user->company_id);
                            })
                            // ->whereHas('submitted', function ($roleQuery) {
                            //     $roleQuery->whereHas('roles', function ($sub) {
                            //         $sub->whereIn('slug', ['contract_manager', 'project_admin', 'supervisor']);
                            //     });
                            // })
                            ->get([
                                // 'company name', // "leave for now" - CP
                                // 'plan number', // "leave for now" - CP
                                'name',
                                'status',
                                'created_by',
                                'submitted_by',
                                'submitted_date',
                                'approved_date',
                                'approved_by',
                                'review_due'
                            ]);

            $this->customValues['tables'][] = [
                'heading' => 'Rejected',
                'table-id' => 'rejected',
                'data' => $rejected,
            ];
        }


    }
}
