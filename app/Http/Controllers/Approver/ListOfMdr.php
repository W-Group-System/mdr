<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Approve;
use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\Approver\MdrSummary;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\KpiScore;
use App\DeptHead\MdrStatus;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ApprovedNotificationJob;
use App\Jobs\ReturnNotificationJob;
use App\Notifications\ApprovedNotification;
use App\Notifications\ReturnNotification;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ListOfMdr extends Controller
{
    public function index(Request $request) {
        $departmentData = Department::with('kpi_scores', 'departmentKpi', 'departmentalGoals', 'process_development', 'innovation', 'user', 'approver')
            ->where('id', $request->department_id)
            ->first();

        return view('approver.list-of-mdr', 
            array(
                'department' => $request->department_id,
                'yearAndMonth' => $request->yearAndMonth,
                'data' => $departmentData,
            )
        );
    }

    public function returnMdr(Request $request) {
        $departmentData = Department::with(['departmentalGoals', 'process_development', 'kpi_scores', 'approver'])
            ->where('id', $request->department_id)
            ->first();

        foreach($departmentData->approver as $approver) {
            if (auth()->user()->id == $approver->user_id) {
                $departmentalGoalsList = $departmentData->departmentalGoals()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $processDevelopmentList = $departmentData->process_development()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $innovation = $departmentData->innovation()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $kpiScore = $departmentData->kpi_scores()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->first();

                $mdrSummary = $departmentData->mdrSummary()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('department_id', $departmentData->id)
                    ->first();

                if ($departmentalGoalsList->isNotEmpty()) {
                    // $departmentalGoals = $departmentalGoalsList->when(true, function($q) {
                    //     return $q->where('final_approved', 1)->isNotEmpty();
                    // });
    
                    // $processDevelopment = $processDevelopmentList->when(true, function($q) {
                    //     return $q->where('final_approved', 1)->isNotEmpty();
                    // });
    
                    // $innovations = $innovation->when(true, function($q) {
                    //     return $q->where('final_approved', 1)->isNotEmpty();
                    // });

                    // if ($kpiScore->final_approved == 1) {
                    //     return true;
                    // }
                    
                    // if ($departmentalGoals && $kpiScore && $processDevelopment && $innovations) {
                    //     Alert::error('ERROR', 'Cannot return the MDR because its already been approved.');
                        
                    //     return back();
                    // }
                    $departmentalGoalsList->each(function($item, $key)use($approver) {
                        $item->update([
                            'status_level' => $approver->status_level != 1 ? 1 : 0
                        ]);
                    });

                    $processDevelopmentList->each(function($item, $key)use($approver) {
                        $item->update([
                            'status_level' => $approver->status_level != 1 ? 1 : 0
                        ]);
                    });

                    $innovation->each(function($item, $key)use($approver) {
                        $item->update([
                            'status_level' => $approver->status_level != 1 ? 1 : 0
                        ]);
                    });

                    $kpiScore->update([
                        'status_level' => $approver->status_level != 1 ? 1 : 0
                    ]);

                    MdrStatus::where('mdr_summary_id', $mdrSummary->id)
                        ->update(['status' => 0, 'start_date' => null]);

                    $mdrSummary = $mdrSummary->update(['status_level' => $approver->status_level != 1 ? 1 : 0]);

                    $user = User::where('id', $departmentData->dept_head_id)->first();

                    $approver = auth()->user()->name;

                    ReturnNotificationJob::dispatch($user, $approver, $request->monthOf)->delay(now()->addMinutes(1));

                    Alert::success('SUCCESS', 'Successfully Returned.');
                    return back();
                }
                else {
                    Alert::error('ERROR', 'error');
                    return back();
                }
            }
        }        
    }

    public function addRemarks(Request $request) {
        $departmentalGoalsList =  DepartmentalGoals::where('department_id', $request->department_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->get();

        if ($departmentalGoalsList->isNotEmpty()) {
            $departmentalGoalsList->each(function($item, $key)use($request) {
                $item->update([
                    'remarks' => $request->remarks[$key]
                ]);
            });

            Alert::success('SUCCESS', 'Successfully Added.');
            return back();
        }
        else {
            return back()->with('errors', ["Can not add remarks"]);
        }
    }

    public function approveMdr(Request $request) {

        $departmentData = Department::with(['departmentalGoals', 'process_development', 'kpi_scores', 'approver'])
            ->where('id', $request->department_id)
            ->first();

        foreach($departmentData->approver as $approver) {
            
            if (auth()->user()->id == $approver->user_id) {

                $departmentalGoalsList = $departmentData->departmentalGoals()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $processDevelopmentList = $departmentData->process_development()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $innovation = $departmentData->innovation()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $kpiScore = $departmentData->kpi_scores()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->first();

                $mdrSummary = $departmentData->mdrSummary()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('department_id', $departmentData->id)
                    ->first();

                if ($departmentalGoalsList->isNotEmpty()) {
                    
                    if($departmentData->approver->last() == $approver) {
                        $departmentalGoalsList->each(function($item, $key)use($approver) {
                            $item->update([
                                'final_approved' => 1
                            ]);
                        });

                        $processDevelopmentList->each(function($item, $key)use($approver) {
                            $item->update([
                                'final_approved' => 1
                            ]);
                        });
                        
                        $innovation->each(function($item, $key) use($approver) {
                            $item->update([
                                'final_approved' => 1
                            ]);
                        });

                        $kpiScore->update([
                            'final_approved' => 1
                        ]);
                        
                        $mdrSummary->update([
                            'approved_date' => date('Y-m-d'), 
                            'final_approved' => 1
                        ]);

                        $mdrStatus = MdrStatus::where('mdr_summary_id', $mdrSummary->id)->get();
                        foreach($mdrStatus as $status) {
                            if ($approver->user_id == $status->user_id) {
                                $status->update([
                                    'status' => 1,
                                    'start_date' => date('Y-m-d')
                                ]);
                            }
                        }
                    }
                    else {
                        $departmentalGoalsList->each(function($item, $key)use($approver) {
                            $item->update([
                                'status_level' => $approver->status_level+1
                            ]);
                        });

                        $processDevelopmentList->each(function($item, $key)use($approver) {
                            $item->update([
                                'status_level' => $approver->status_level+1
                            ]);
                        });

                        $innovation->each(function($item, $key) use($approver) {
                            $item->update([
                                'status_level' => $approver->status_level+1
                            ]);
                        });

                        $kpiScore->update([
                            'status_level' => $approver->status_level+1
                        ]);
                    
                        $mdrStatus = MdrStatus::where('mdr_summary_id', $mdrSummary->id)->get();
                        foreach($mdrStatus as $status) {
                            if ($approver->user_id == $status->user_id) {
                                $status->update([
                                    'status' => 1,
                                    'start_date' => date('Y-m-d')
                                ]);
                            }
                        }

                        $mdrSummary->update([
                            'status_level' => $approver->status_level+1
                        ]);
                    }
                    
                    $user = User::where('id', $departmentData->dept_head_id)->first();

                    $approver = auth()->user()->name;

                    ApprovedNotificationJob::dispatch($user, $approver, $request->monthOf)->delay(now()->addMinutes(1));

                    Alert::success('SUCCESS', 'Successfully Approved.');
                    return back();
                }
                else {
                    Alert::error('ERROR', 'Cannot approved the MDR.');

                    return back();
                }
            }
        }        
    }

    public function submitScores(Request $request) {
        $kpiScoreData = KpiScore::findOrFail($request->id);

        if ($kpiScoreData) {
            $kpiScoreData->score = $request->kpiScores;
            $kpiScoreData->pd_scores = $request->pdScores;
            $kpiScoreData->innovation_scores = $request->innovationScores;
            $kpiScoreData->timeliness = $request->timelinessScores;
            $kpiScoreData->total_rating = $request->ratingScores;
            $kpiScoreData->save();

            Alert::success('SUCCESS', 'Successfully Updated.');
            return back();
        }
    }

    public function addInnovationRemarks(Request $request) {
        $innovationData = Innovation::findOrFail($request->id);

        if ($innovationData) {
            $innovationData->remarks = $request->remarks;
            $innovationData->save();

            Alert::success('SUCCESS', 'Successfully Updated.');

            return back();
        }
    }

    public function addPdRemarks(Request $request) {
        $pdData = ProcessDevelopment::findOrFail($request->id);

        if ($pdData) {
            $pdData->remarks = $request->remarks;
            $pdData->save();

            Alert::success('SUCCESS', 'Successfully Updated.');
            return back();
        }
    }
}
