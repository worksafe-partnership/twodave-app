<?php

namespace App\Http\Controllers;

use Auth;
use Carbon;
use Controller;
use App\Vtram;

class CompanyProjectTrackerController extends Controller
{
    protected $identifierPath = 'company.project.tracker';
    protected $disableCreate = true;

    public function postIndexHook()
    {
        $this->heading = str_replace("VTRAMS of", "VTRAMs Tracker for", $this->heading);
    }

    public function _datatableAll()
    {
        $user = Auth::User();
        $args = func_get_args();
        $nowCarbon = Carbon::now();
        $twoWeeksCarbon = $nowCarbon->copy()->addWeeks(2);
        $query = Vtram::where('status', "PENDING")
                        ->where('company_id', '=', $args[0])
                        ->get([
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
                        ]);

        return app('datatables')->of($query)
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
