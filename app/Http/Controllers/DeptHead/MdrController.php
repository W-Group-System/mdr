<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use App\Admin\MdrGroup;
use App\Admin\MdrSetup;
use App\Approver\MdrSummary;
use App\DeptHead\Attachments;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\MdrScore;
use App\DeptHead\MdrStatus;
use App\DeptHead\OnGoingInnovation;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\EmailNotification;
use App\Notifications\NotifyDeptHead;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class MdrController extends Controller
{
    public function index(Request $request) {
        $mdrSetup = MdrGroup::with([
            'mdrSetup' => function($q) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('status',1);
            },
            'mdrSetup.departmentalGoals' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            },
            'mdrSetup.attachments' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            },
            'processDevelopment' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            }, 
            'innovation' => function($q)use($request) {
                $q->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            }
        ])
        ->get();
        
        $approver = MdrSummary::with('mdrStatus')
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->where('department_id', auth()->user()->department_id)
            ->first();

        return view('dept-head.mdr',
            array(
                'mdrSetup' => $mdrSetup,
                'approver' => $approver,
                'yearAndMonth' => $request->yearAndMonth
            )
        );
    }

    public function mdrView() {
        // $mdrScoreList = Department::with([
        //     'kpi_scores' => function($q) {
        //         $q->orderBy('year', 'DESC')
        //             ->orderBy('month', 'DESC');
        //     }
        // ])
        // ->where('id', auth()->user()->department_id)
        // ->first();

        // foreach($mdrScoreList->kpi_scores as $kpiScore) {
        //     // if($kpiScore->final_approved == 0) {
        //     // }
        //     $yearAndMonth = $kpiScore->year.'-'.$kpiScore->month;
        // }

        $kpiScore = MdrScore::where('department_id', auth()->user()->department_id)
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
        
        $yearAndMonth = "0000-00";
        foreach($kpiScore as $kpiScoreData) {
            $yearAndMonth = $kpiScoreData->year.'-'.$kpiScoreData->month;
            break;
        }

        $department = Department::select('name')->where('id', auth()->user()->department_id)->first();

        return view('dept-head.mdr-list', 
            array(
                // 'mdrScoreList' => $mdrScoreList,
                'yearAndMonth' => isset($yearAndMonth) ? $yearAndMonth : date('Y-m'),
                'kpiScore' => $kpiScore,
                'department' => $department
            )
        );
    }

    public function edit(Request $request) {
        $departmentKpiGroup = MdrGroup::with([
            'mdrSetup' => function($q) {
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
            'mdrSetup.attachments' => function($q)use($request) {
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
            'mdrSummary' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
                    // ->where('status_level', 0);
            },
            'kpi_scores' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('status_level', 0);
            }
        ])
            ->where('id', auth()->user()->department_id)
            ->first();

        if ($departmentData->departmentalGoals->isNotEmpty()) {
            $kpiScore = $departmentData->kpi_scores->first();
            $mdrSummary = $departmentData->mdrSummary->first();
            
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

            $totalInnovationAndPIScores = 0.0;
            if ($kpiScore->innovation_scores == 0.0 && $kpiScore->pd_scores == 0.0) {
                $totalInnovationAndPIScores = 0.0;
            }
            else if (($kpiScore->innovation_scores == 0.5 && $kpiScore->pd_scores == 0.5) || ($kpiScore->innovation_scores == 0.5 || $kpiScore->innovation_scores == 0.0) && ($kpiScore->pd_scores == 0.5 || $kpiScore->pd_scores == 0.0)) {
                $totalInnovationAndPIScores = 0.5;
            }
            else if (($kpiScore->innovation_scores == 1.0 && $kpiScore->pd_scores == 1.0) || ($kpiScore->innovation_scores == 0.5 || $kpiScore->innovation_scores == 1.0) && ($kpiScore->pd_scores == 0.5 || $kpiScore->pd_scores == 1.0) || ($kpiScore->innovation_scores == 1.0 || $kpiScore->innovation_scores == 0.0) && ($kpiScore->pd_scores == 1.0 || $kpiScore->pd_scores == 0.0)) {

                $totalInnovationAndPIScores = 1.0;
            }
        
            $deadlineDate = $kpiScore->deadline;
            $timeliness =  $deadlineDate >= date('Y-m-d') ? 0.4 : 0.0;
            $totalRating = $kpiScore->score + $totalInnovationAndPIScores + $timeliness;
            
            $kpiScore->update([
                'status_level' => 1,
                'total_rating' => $totalRating,
                'timeliness' => $timeliness
            ]);

            $mdrSummary = $mdrSummary->update([
                'rate' => $kpiScore->total_rating, 
                'status_level' => 1,
                'submission_date' => date('Y-m-d')
            ]);

            Alert::success('SUCCESS', 'Your MDR is been approved.');
            return back();
        }
        else {
            $mdrSummary = $departmentData->mdrSummary->first();
            
            if (!empty($mdrSummary)) {
                if ($mdrSummary->status_level != 0) {
                    Alert::error("ERROR", "Your MDR is currently approved.");
    
                    return back();
                };
            }

            Alert::error("ERROR", "Cannot DepartmentApprovers. Please fill-up your KPI.");
            return back();
        }
    }

    public function submitMdr(Request $request) {
        
        $userData = User::where('department_id', auth()->user()->department_id)
            ->where('account_role', 2)
            ->first();

        $userData->notify(new NotifyDeptHead($userData->name, $request->yearAndMonth));

        Alert::success('SUCCESS', 'The MDR is successfully submit.');
        return back();
    }
}
