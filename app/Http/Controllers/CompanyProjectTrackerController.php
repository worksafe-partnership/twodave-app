<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon;
use Controller;
use App\Vtram;
use App\Template;
use App\Company;
use App\Project;

class CompanyProjectTrackerController extends Controller
{
    protected $identifierPath = 'company.project.tracker';
    protected $disableCreate = true;

    public function postIndexHook()
    {
        $this->heading = str_replace("VTRAMS Tracker of", "VTRAMS Tracker for", $this->heading);
        $templates = Template::whereIn('company_id', [$this->args[0], $this->user->company_id])
                                                   ->join('companies', 'templates.company_id', '=', 'companies.id')
                                                   ->where('status', 'CURRENT')
                                                   ->get([
                                                        'companies.name as company_name', 'templates.name', 'templates.id'
                                                    ]);
        $this->customValues['company'] = Company::findOrFail($this->args[0]);
        $this->customValues['templates'] = [];
        foreach ($templates as $template) {
            $this->customValues['templates'][$template->id] = $template->name . " (" . $template->company_name .")";
        }
        $this->customValues['templates'] = collect($this->customValues['templates']);
        $this->customValues['path'] = 'vtram/create';
    }

    public function indexHook()
    {
        $this->parentRecord = Project::findOrFail($this->parentId);
        if (can('create', 'company.project.vtram')) {
            $this->actionButtons['create_vtram'] = [
                'label' => 'Create VTRAMS',
                'path' => '/company/'.$this->args[0].'/project/'.$this->parentId.'/vtram/create',
                'icon' => 'plus2',
                'order' => '500',
                'id' => 'create_vtram',
                'class' => 'is-success create_vtram',
            ];
        }
    }

    public function _datatableAll()
    {
        $user = Auth::User();
        $args = func_get_args();
        $nowCarbon = Carbon::now();
        $twoWeeksCarbon = $nowCarbon->copy()->addWeeks(2);
        $query = Vtram::withTrashed()
                        ->with(['approvals' => function ($q) {
                            $q->where('type', '=', 'PC_A');
                        }])
                        ->where('status', '!=', 'PREVIOUS')
                        ->where('project_id', '=', $args[1])
                        ->where('company_id', '=', $args[0])
                        ->get([
                            'company_id',
                            'number',
                            'id',
                            'company_id',
                            'project_id',
                            'name',
                            'status',
                            'created_by',
                            'submitted_by',
                            'submitted_date',
                            'reference',
                            'revision_number',
                            'resubmit_by',
                            'approved_date',
                            'approved_by',
                            'review_due',
                            'deleted_at',
                            DB::raw('0 as external_approval_date')
                        ]);

        return app('datatables')->of($query)
            ->editColumn('external_approval_date', function ($item) {
                $approval = $item->approvals->first();
                if ($approval != null) {
                    $date = Carbon::createFromFormat('Y-m-d', $approval->approved_date);
                    if ($date != null) {
                        return $date->timestamp;
                    }
                }

                return '';
            })
            ->editColumn('company_id', function ($item) {
                return $item->companyName();
            })
            ->editColumn('status', function ($item) {
                return $item->niceStatus();
            })
            ->editColumn('created_by', function ($item) {
                return $item->createdName();
            })
            ->editColumn('submitted_by', function ($item) {
                return $item->submittedName();
            })
            ->editColumn('submitted_date', function ($item) {
                return $item->submittedDateTimestamp();
            })
            ->editColumn('approved_date', function ($item) {
                return $item->approvedDateTimestamp();
            })
            ->editColumn('approved_by', function ($item) {
                return $item->approvedName();
            })
            ->editColumn('review_due', function ($item) use ($nowCarbon, $twoWeeksCarbon) {
                $class = $item->dTClass($nowCarbon, $twoWeeksCarbon);
                return [
                    'date' => $item->nextReviewDateTimestamp(),
                    'class' => $class,
                    'colour' => config('egc.review_colours')[$class],
                ];
            })
            ->make(true);
    }
}
