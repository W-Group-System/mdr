<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Approve;
use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\Approver\MdrSummary;
use App\DeptHead\Attachments;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\KpiScore;
use App\DeptHead\MdrStatus;
use App\DeptHead\OnGoingInnovation;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\EmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class MdrController extends Controller
{
    public function index(Request $request) {
        $departmentKpi = DepartmentGroup::with([
                'departmentKpi' => function($q) {
                    $q->where('department_id', auth()->user()->department_id);
                },
                'departmentKpi.attachments' => function($q)use($request) {
                    $q->where('department_id', auth()->user()->department_id)
                        ->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->where('month', date('m', strtotime($request->yearAndMonth)));
                },
                'processDevelopment', 
                'innovation'])
            ->get();

        $approver = MdrSummary::with('mdrStatus')
            ->where('year', date('Y'))
            ->where('month', date('m'))
            ->where('department_id', auth()->user()->department_id)
            ->get();

        return view('dept-head.mdr',
            array(
                'departmentKpi' => $departmentKpi,
                'approver' => $approver,
                'yearAndMonth' => $request->yearAndMonth
            )
        );
    }

    public function mdrView() {
        $mdrScoreList = Department::with([
            'kpi_scores' => function($q) {
                $q->orderBy('month', 'DESC');
            }
        ])
        ->where('id', auth()->user()->department_id)
        ->first();

        foreach($mdrScoreList->kpi_scores as $kpiScore) {
            if($kpiScore->final_approved == 0)
                $yearAndMonth = $kpiScore->year.'-'.$kpiScore->month;
            else {
                $yearAndMonth = date('Y-m');
            }
        }

        return view('dept-head.mdr-list', 
            array(
                'mdrScoreList' => $mdrScoreList,
                'yearAndMonth' => isset($yearAndMonth) ? $yearAndMonth : '',
                // 'kpiScore' => $kpiScore
            )
        );
    }

    public function edit(Request $request) {
        $departmentKpiGroup = DepartmentGroup::with([
            'departmentKpi' => function($q) {
                $q->where('department_id', auth()->user()->department_id);
            },
            'departmentalGoals' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            },
            'innovation' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            },
            'processDevelopment' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            },
            'departmentKpi.attachments' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            },
            
        ])
        ->get();

        $approver = MdrSummary::with('mdrStatus')
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->where('department_id', auth()->user()->department_id)
            ->get();

        return view('dept-head.edit-mdr',
            array(
                'departmentKpiGroup' => $departmentKpiGroup,
                'approver' => $approver,
                'yearAndMonth' => $request->yearAndMonth
            )
        );
    }

    // public function computeKpi($grades, $date, $deadlineDate) {
    //     $grade = collect($grades);

    //     $kpiValue = $grade->map(function($item, $key) {
    //         $value = $item / 100.00;

    //         return $value;
    //     });

    //     $kpiScore = $grade->map(function($item, $key) {
    //         $grades =  $item / 100.00 * 0.5;
            
    //         return $grades;
    //     });

    //     $value = number_format($kpiValue->sum(), 2);
    //     $rating = 3.00;
    //     $score = number_format($kpiScore->sum(), 2);
        
    //     $kpiScoreData = KpiScore::where('department_id', auth()->user()->department_id)
    //         ->where('year', date('Y', strtotime($date)))
    //         ->where('month', date('m', strtotime($date)))
    //         ->first();

    //     if (!empty($kpiScoreData)) {
    //         $kpiScoreData->grade = $value;
    //         $kpiScoreData->rating = $rating;
    //         $kpiScoreData->score = $score;
    //         $kpiScoreData->save();
    //     }
    //     else {
    //         $kpiScore = new KpiScore;
    //         $kpiScore->department_id = auth()->user()->department_id;
    //         $kpiScore->grade = $value;
    //         $kpiScore->rating = $rating;
    //         $kpiScore->score = $score;
    //         $kpiScore->year = date('Y', strtotime($date));
    //         $kpiScore->month = date('m', strtotime($date));
    //         $kpiScore->deadline = $deadlineDate;
    //         $kpiScore->save();
    //     }

    //     $departmentData = Department::where('id', auth()->user()->department_id)->first();

    //     $mdrSummary = MdrSummary::with(['mdrStatus'])
    //         ->where('department_id', $departmentData->id)
    //         ->where('year', date('Y', strtotime($date)))
    //         ->where('month', date('m', strtotime($date)))
    //         ->first();

    //     if(empty($mdrSummary)) {
    //         $mdrSummary = new MdrSummary;
    //         $mdrSummary->department_id =$departmentData->id;
    //         $mdrSummary->user_id = auth()->user()->id;
    //         $mdrSummary->deadline = $deadlineDate;
    //         $mdrSummary->submission_date = date('Y-m-d');
    //         $mdrSummary->status = $deadlineDate >= date('Y-m-d') ? 'On-Time' : 'Delayed';
    //         $mdrSummary->year = date('Y', strtotime($date));
    //         $mdrSummary->month = date('m', strtotime($date));
    //         // $mdrSummary->rate = $kpiScoreData->total_rating;
    //         $mdrSummary->save();
    //     }

    //     $mdrStatus = $mdrSummary->mdrStatus()
    //         ->where('mdr_summary_id', $mdrSummary->id)
    //         ->get();

    //     if ($mdrStatus->isEmpty()) {
    //         foreach($departmentData->approver as $data) {
    //             $mdrStatus = new MdrStatus;
    //             $mdrStatus->user_id = $data->user_id;
    //             $mdrStatus->mdr_summary_id = $mdrSummary->id;
    //             $mdrStatus->status = 0;
    //             $mdrStatus->save();
    //         }
    //     }
    //     else {
    //         foreach($mdrStatus as $status) {
    //             $status->delete();
    //         }
            
    //         foreach($departmentData->approver as $data) {
    //             $mdrStatus = new MdrStatus;
    //             $mdrStatus->user_id = $data->user_id;
    //             $mdrStatus->mdr_summary_id = $mdrSummary->id;
    //             $mdrStatus->status = 0;
    //             $mdrStatus->save();
    //         }
    //     }
        
    // }

    public function approveMdr(Request $request) {
        $departmentData = Department::with([
            'departmentalGoals' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('status_level', 0);
            },
            'process_development' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('status_level', 0);
            },
            'innovation' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('status_level', 0);
            },
            'mdrSummary',
            'kpi_scores'
        ])
            ->where('id', auth()->user()->department_id)
            ->first();

        $kpiScore = $departmentData->kpi_scores()
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->where('status_level', 0)
            ->first();
    
        $mdrSummary = $departmentData->mdrSummary()
            ->where('department_id', $departmentData->id)
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->first();

        if ($departmentData->departmentalGoals->isNotEmpty()) {

            $departmentData->departmentalGoals->each(function($item, $key)use($mdrSummary) {
                $item->update([
                    'status_level' => 1,
                ]);
            });

            $departmentData->process_development->each(function($item, $key) {
                $item->update([
                    'status_level' => 1
                ]); 
            });

            $departmentData->innovation->each(function($item, $key) {
                $item->update([
                    'status_level' => 1
                ]);
            });

            $totalRating = $kpiScore->score + $kpiScore->pd_scores + $kpiScore->innovation_scores + $kpiScore->timeliness;
            $deadlineDate = $kpiScore->deadline;

            $kpiScore->update([
                'status_level' => 1,
                'total_rating' => $totalRating,
                'timeliness' => $deadlineDate >= date('Y-m-d') ? 0.5 : 0.0
            ]);

            if (!empty($mdrSummary)) {
                $mdrSummary = $mdrSummary->update(['rate' => $kpiScore->total_rating, 'status_level' => 1]);
            }

            Alert::success('SUCCESS', 'Your MDR is been approved.');
            return back();
        }
        else {
            if (!empty($mdrSummary)) {
                if ($mdrSummary->status_level != 0) {
                    Alert::error("ERROR", "Your MDR is currently approved.");
    
                    return back();
                };
            }

            Alert::error("ERROR", "Cannot Approve. Please fill-up your KPI.");
            return back();
        }
    }
}
