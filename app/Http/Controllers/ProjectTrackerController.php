<?php

namespace App\Http\Controllers;

use Auth;
use Carbon;
use Controller;
use App\Vtram;
use App\VtramUser;
use App\Project;
use App\Template;
use App\ProjectSubcontractor;

class ProjectTrackerController extends Controller
{
    protected $identifierPath = 'project.tracker';
    protected $disableCreate = true;

    public function postIndexHook()
    {
        $this->heading = str_replace("VTRAMS Tracker of", "VTRAMS Tracker for", $this->heading);
        $this->heading = str_replace("VTRAMS", $this->parentRecord->company->vtrams_name ?? "VTRAMS", $this->heading);
        $project = Project::findOrFail($this->parentId);
        $this->customValues['company'] = $this->user->company;
        $companies = [$project->company_id, $this->user->company_id];
        if ($this->user->company_id != $project->company_id) {
            $companiesWithAccess = ProjectSubcontractor::where('project_id', $this->parentId)->get();
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

        $this->customValues['path'] = 'vtram/create';
    }

    public function indexHook()
    {
        if (!in_array($this->parentId, Auth::User()->projectCompanyIds())) {
            abort(404);
        }

        $this->parentRecord = Project::findOrFail($this->parentId);
        if (can('create', 'project.vtram')) {
            $this->actionButtons['create_vtram'] = [
                'label' => 'Create '.($this->parentRecord->company->vtrams_name ?? 'VTRAMS'),
                'path' => '/project/'.$this->parentId.'/vtram/create',
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
        $query = Vtram::whereIn('vtrams.id', $user->vtramsCompanyIds())
                        ->join('companies', 'companies.id', '=', 'vtrams.company_id')
                        ->where('status', '!=', 'PREVIOUS')
                        ->where('project_id', '=', $args[0])
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
                        ->select([
                            'vtrams.id',
                            'number',
                            'project_id',
                            'companies.name AS company_name',
                            'vtrams.name',
                            'status',
                            'created_by',
                            'submitted_by',
                            'submitted_date',
                            'approved_date',
                            'approved_by',
                            'review_due'
                        ]);

        return app('datatables')->of($query)
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
