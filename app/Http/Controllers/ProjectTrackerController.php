<?php

namespace App\Http\Controllers;

use Auth;
use Carbon;
use Controller;
use App\Vtram;
use App\Project;
use App\Template;

class ProjectTrackerController extends Controller
{
    protected $identifierPath = 'project.tracker';
    protected $disableCreate = true;

    public function postIndexHook()
    {
        $this->heading = str_replace("VTRAMS Tracker of", "VTRAMS Tracker for", $this->heading);
        $this->heading = str_replace("VTRAMS", $this->parentRecord->company->vtrams_name ?? "VTRAMS", $this->heading);
        $this->customValues['templates'] = Template::where('company_id', $this->user->company_id)
                                                   ->where('status', 'CURRENT')
                                                   ->pluck('name', 'id');
        $this->customValues['path'] = 'vtram/create';
    }

    public function indexHook()
    {
        if ($this->user->company_id !== null) {
            $project = Project::withTrashed()->findOrFail($this->parentId);
            if ($this->user->company_id !== $project->company_id) {
                abort(404);
            }
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
        $query = Vtram::where('company_id', '=', $user->company_id)
                        ->where('status', '!=', 'PREVIOUS')
                        ->where('project_id', '=', $args[0])
                        ->get([
                            'id',
                            'number',
                            'project_id',
                            'name',
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
