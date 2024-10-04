<?php

namespace App\Http\Controllers\Approver;

use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use App\Admin\MdrGroup;
use App\Admin\MdrSetup;
use App\Approver\MdrSummary;
use App\Approver\Warnings;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\MdrApprovers;
use App\DeptHead\MdrScore;
use App\DeptHead\MdrStatus;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ApprovedNotificationJob;
use App\Jobs\EmailNotificationForApproversJob;
use App\Jobs\ReturnNotificationJob;
use App\Notifications\ApprovedNotification;
use App\Notifications\EmailNotification;
use App\Notifications\EmailNotificationForApprovers;
use App\Notifications\HRNotification;
use App\Notifications\ReturnNotification;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ListOfMdr extends Controller
{
    public function index(Request $request, $id) {
        $mdrSummary = MdrSummary::with('departmentalGoals', 'innovation', 'processImprovement', 'mdrScore')->findOrFail($id);

        return view('approver.list-of-mdr', 
            array(
                'mdrSummary' => $mdrSummary
            )
        );
    }

    public function returnMdr(Request $request) {
        // $departmentData = Department::with([
        //         'departmentalGoals' => function ($q)use($request) {
        //             $q->where('year', date('Y', strtotime($request->monthOf)))
        //                 ->where('month', date('m', strtotime($request->monthOf)));
        //         }, 
        //         'process_development' => function($q)use($request) {
        //             $q->where('year', date('Y', strtotime($request->monthOf)))
        //                 ->where('month', date('m', strtotime($request->monthOf)));
        //         },
        //         'innovation' => function($q)use($request) {
        //             $q->where('year', date('Y', strtotime($request->monthOf)))
        //                 ->where('month', date('m', strtotime($request->monthOf)));
        //         },
        //         'kpi_scores' => function($q)use($request) {
        //             $q->where('year', date('Y', strtotime($request->monthOf)))
        //                 ->where('month', date('m', strtotime($request->monthOf)));
        //         },
        //         'mdrSummary' => function ($q)use($request) {
        //             $q->where('year', date('Y', strtotime($request->monthOf)))
        //                 ->where('month', date('m', strtotime($request->monthOf)));
        //         },
        //         'approver'
        //     ])
        //     ->where('id', $request->department_id)
        //     ->first();
        
        // foreach($departmentData->approver as $approver) {
        //     if (auth()->user()->id == $approver->user_id) {
        //         $departmentalGoalsList = $departmentData->departmentalGoals->where('status_level', $approver->status_level);
        //         $innovation = $departmentData->innovation->where('status_level', $approver->status_level);
        //         $processDevelopmentList = $departmentData->process_development->where('status_level', $approver->status_level);
        //         $kpiScore = $departmentData->kpi_scores->first();
        //         $mdrSummary = $departmentData->mdrSummary->first();
        //         // $mdrStatus = $departmentData->mdrSummary;
        //         $mdrStatus = $mdrSummary;
                
        //         if ($departmentalGoalsList->isNotEmpty()) {
        //             $departmentalGoalsList->each(function($item, $key)use($approver) {
        //                 $item->update([
        //                     'status_level' => $approver->status_level != 1 ? 1 : 0
        //                 ]);
        //             });

        //             $innovation->each(function($item, $key)use($approver) {
        //                 $item->update([
        //                     'status_level' => $approver->status_level != 1 ? 1 : 0
        //                 ]);
        //             });

        //             $processDevelopmentList->each(function($item, $key)use($approver) {
        //                 $item->update([
        //                     'status_level' => $approver->status_level != 1 ? 1 : 0
        //                 ]);
        //             });

        //             $kpiScore->update([
        //                 'status_level' => $approver->status_level != 1 ? 1 : 0,
        //                 'timeliness' => 0.0,
        //                 'total_rating' => 0.00
        //             ]);

        //             $mdrSummary->update([
        //                 'status_level' => $approver->status_level != 1 ? 1 : 0
        //             ]);
                    
        //             // foreach($mdrStatus as $ms) {
        //             //     $status = $ms->mdrStatus->where('mdr_summary_id', $mdrSummary->id);
                        
        //             //     $status->each(function($item, $key)use($approver) {
        //             //         $item->update([
        //             //             'status' => 0, 
        //             //             'start_date' => null
        //             //         ]);
        //             //     });
                        
        //             // }
        //             $status = $mdrStatus->mdrStatus;
                    
        //             $status->each(function($item, $key)use($approver) {
        //                 $item->update([
        //                     'status' => 0, 
        //                     'start_date' => null,
        //                     'status_desc' => null
        //                 ]);
        //             });

        //             foreach($status as $ms) {
        //                 if (auth()->user()->id == $ms->user_id) {
        //                     $ms->update([
        //                         'start_date' => date('Y-m-d'),
        //                         'status_desc' => 'RETURNED'
        //                     ]);
        //                 }
        //             }

        //             $user = User::where('id', $departmentData->user_id)->first();
                    
        //             $approver = auth()->user()->name;

        //             $user->notify(new ReturnNotification($user->name, $request->monthOf, $approver));

        //             Alert::success('SUCCESS', 'Successfully Returned.');
        //             return redirect()->to('for_approval');
        //         }
        //         else {
        //             Alert::error('ERROR', 'You already returned the MDR.');

        //             return back();
        //         }

        //     }
        // }        
    }

    public function addGradeAndRemarks(Request $request) {
        $departmentalGoalsList = DepartmentalGoals::findMany($request->department_goals_id);
        
        foreach($departmentalGoalsList as $key=>$dptGoals)
        {
            $dptGoals->remarks = $request->remarks[$key];
            $dptGoals->grade = $request->grade[$key];
            $dptGoals->save();
        }

        computeKpi($request->grade, $request->target_date, $request->yearAndMonth, $request->department);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
        
        // if ($departmentalGoalsList->isNotEmpty()) {
        //     $grade = collect($request->grade);
            
        //     $kpiScore = $grade->map(function($item, $key) {
        //         if ($item > 100) {
        //             $item = 100;
        //         }

        //         $grades =  $item / 100.00 * 0.5;
                
        //         return $grades;
        //     });
        
        //     $score = number_format($kpiScore->sum(), 2);

        //     $kpiScore = MdrScore::where('year', date('Y', strtotime($request->yearAndMonth)))
        //         ->where('month', date('m', strtotime($request->yearAndMonth)))
        //         ->where('department_id', $request->department_id)
        //         ->first();

        //     $totalInnovationAndPIScores = 0.0;
        //     if ($kpiScore->innovation_scores == 0.0 && $kpiScore->pd_scores == 0.0) {
        //         $totalInnovationAndPIScores = 0.0;
        //     }
        //     else if (($kpiScore->innovation_scores == 0.5 && $kpiScore->pd_scores == 0.5) || ($kpiScore->innovation_scores == 0.5 || $kpiScore->innovation_scores == 0.0) && ($kpiScore->pd_scores == 0.5 || $kpiScore->pd_scores == 0.0)) {
        //         $totalInnovationAndPIScores = 0.5;
        //     }
        //     else if (($kpiScore->innovation_scores == 1.0 && $kpiScore->pd_scores == 1.0) || ($kpiScore->innovation_scores == 0.5 || $kpiScore->innovation_scores == 1.0) && ($kpiScore->pd_scores == 0.5 || $kpiScore->pd_scores == 1.0) || ($kpiScore->innovation_scores == 1.0 || $kpiScore->innovation_scores == 0.0) && ($kpiScore->pd_scores == 1.0 || $kpiScore->pd_scores == 0.0)) {
        //         $totalInnovationAndPIScores = 1.0;
        //     }
            
        //     $deadlineDate = $kpiScore->deadline;
        //     $timeliness =  $deadlineDate >= date('Y-m-d') ? 0.4 : 0.0;
        //     $totalRating = $score + $totalInnovationAndPIScores + $timeliness;
            
        //     $kpiScore->update([
        //         'score' => $score,
        //         'total_rating' => $totalRating
        //     ]);

        //     foreach($departmentalGoalsList as $key=>$dg) {
        //         $dg->remarks = $request->remarks[$key];
        //         $dg->grade = $request->grade[$key];
        //         $dg->save();
        //     }

            // Alert::success('SUCCESS', 'Successfully Added.');
        //     return back();
        // }
        // else {
        //     return back()->with('errors', ["Can not add remarks"]);
        // }
    }

    public function approveMdr(Request $request, $id) {
        // dd($request->all());
        $mdr_approvers = MdrApprovers::findOrFail($id);
        $mdr_approvers->status = $request->action;
        $mdr_approvers->remarks = $request->remarks;
        $mdr_approvers->save();

        $mdrSummary = MdrSummary::where('id', $mdr_approvers->mdrSummary->id)->first();
        $nextMdrApprovers = MdrApprovers::where('mdr_summary_id', $mdr_approvers->mdrSummary->id)->whereIn('status', ['Waiting', 'Returned'])->orderBy('level', 'asc')->first();
        
        if ($request->action == "Approved")
        {
            $mdrSummary->level = $mdr_approvers->level+1;
            $mdrSummary->save();

            if ($nextMdrApprovers != null)
            {
                $nextMdrApprovers->status = "Pending";
                $nextMdrApprovers->save();
            }
            else
            {
                $mdrSummary->status = "Approved";
                $mdrSummary->save();

                if ($mdrSummary->mdrScoreHasOne->total_rating < 2.99)
                {
                    $warnings = Warnings::where('mdr_summary_id',$mdrSummary->id)->first();
                    if ($warnings == null)
                    {
                        $warnings = new Warnings;
                        $warnings->department_id = $mdrSummary->department_id;
                        $warnings->warning_level = 1;
                        $warnings->mdr_summary_id = $mdrSummary->id;
                        $warnings->save();
                    }
                    else
                    {
                        $warnings->warning_level = 2;
                        $warnings->save();

                        $mdrSummary->penalty_status = 'For NTE';
                        $mdrSummary->save();
                    }
                }

                $user = User::where('role', "Human Resources")->get();
                $yearAndMonth = $mdrSummary->year.'-'.$mdrSummary->month;
                $department = $mdrSummary->departments->name;
                $rate = $mdrSummary->rate;

                foreach($user as $u) {
                    $u->notify(new HRNotification($u->name, $yearAndMonth, $department, $rate));
                }
            }

            if (auth()->user()->role == "Approver" || auth()->user()->role == "Business Process Manager")
            {
                $user = User::where('department_id', $mdrSummary->department_id)->where('role', 'Department Head')->first();
                $approvers = User::where('id', $mdr_approvers->user_id)->first();
                $approver = $approvers->name;
                $yearAndMonth = $mdrSummary->yearAndMonth;
                $user->notify(new ApprovedNotification($user->name, $approver, $yearAndMonth));
            }

            Alert::success('Succesfully Approved')->persistent('Dismiss');
        }
        else
        {
            $returnToApprover = MdrApprovers::where('mdr_summary_id', $mdr_approvers->mdrSummary->id)->orderBy('level', 'asc')->get();
            $secondApprover = MdrApprovers::where('level', 2)->where('mdr_summary_id', $mdr_approvers->mdrSummary->id)->first();
            $firstApprover = MdrApprovers::where('level', 1)->where('mdr_summary_id', $mdr_approvers->mdrSummary->id)->first();
            
            if (auth()->user()->id == $firstApprover->user_id)
            {
                $mdrSummary->level = null;
                $mdrSummary->save();
            }
            elseif(auth()->user()->id == $secondApprover->user_id)
            {
                $mdrSummary->level = 1;
                $mdrSummary->save();
            }
            else
            {
                $mdrSummary->level = 2;
                $mdrSummary->save();
            }

            foreach($returnToApprover as $key=>$approver)
            {
                $mdrApprover = MdrApprovers::findOrFail($approver->id);

                if ($firstApprover->user_id == auth()->user()->id)
                {
                    if ($key == 0)
                    {
                        $mdrApprover->status = "Returned";
                    }
                }
                elseif ($secondApprover->user_id == auth()->user()->id)
                {
                    if ($key == 0)
                    {
                        $mdrApprover->status = "Pending";
                    }
                }
                else
                {
                    if($key > 0)
                    {
                        if ($key == 1)
                        {
                            $mdrApprover->status = "Pending";
                        }
                    }
                }

                $mdrApprover->save();
            }

            if (auth()->user()->role == "Approver" || auth()->user()->role == "Business Process Manager")
            {
                $user = User::where('department_id', $mdrSummary->department_id)->where('role', 'Department Head')->first();
                $approvers = User::where('id', $mdr_approvers->user_id)->first();
                $approver = $approvers->name;
                $yearAndMonth = $mdrSummary->yearAndMonth;
                $user->notify(new ReturnNotification($user->name, $yearAndMonth, $approver));
            }

            Alert::success('Succesfully Returned')->persistent('Dismiss');
        }
        
        return redirect('for_approval');
        
        // foreach($departmentData->approver as $approver) {
        //     if (auth()->user()->id == $approver->user_id) {
        //         $departmentalGoalsList = $departmentData->departmentalGoals->where('status_level', $approver->status_level);
        //         $innovation = $departmentData->innovation->where('status_level', $approver->status_level);
        //         $processDevelopmentList = $departmentData->process_development->where('status_level', $approver->status_level);
        //         $kpiScore = $departmentData->kpi_scores->first();
        //         $mdrSummary = $departmentData->mdrSummary->first();
        //         $status = $departmentData->mdrSummary->first();
        //         $departmentWarning = $departmentData->warnings->first();
                
        //         if ($departmentalGoalsList->isNotEmpty()) {
        //             if ($departmentData->approver->last() == $approver) {
        //                 $departmentalGoalsList->each(function($item, $key)use($approver) {
        //                     $item->update([
        //                         'final_approved' => 1
        //                     ]);
        //                 });

        //                 $processDevelopmentList->each(function($item, $key)use($approver) {
        //                     $item->update([
        //                         'final_approved' => 1
        //                     ]);
        //                 });
                        
        //                 $innovation->each(function($item, $key) use($approver) {
        //                     $item->update([
        //                         'final_approved' => 1
        //                     ]);
        //                 });

        //                 $kpiScore->update([
        //                     'final_approved' => 1
        //                 ]);
                        
        //                 foreach($status->mdrStatus as $ms) {
        //                     if ($approver->user_id == $ms->user_id) {
        //                         $ms->update([
        //                             'status' => 1,
        //                             'start_date' => date('Y-m-d'),
        //                             'status_desc' => 'APPROVED'  
        //                         ]);
        //                     }
        //                 }

        //                 if ($mdrSummary->rate < 2.99) {
        //                     if (empty($departmentWarning)) {
        //                         $warnings = new Warnings;
        //                         $warnings->department_id = $departmentData->id;
        //                         $warnings->warning_level = 1;
        //                         $warnings->mdr_summary_id = $mdrSummary->id;
        //                         $warnings->save();

        //                         $mdrSummary->update([
        //                             'approved_date' => date('Y-m-d'), 
        //                             'final_approved' => 1,
        //                         ]);
        //                     }
        //                     else {
        //                         $warnings = $departmentWarning->findOrFail($departmentWarning->id);
        //                         $warnings->warning_level = $warnings->warning_level+1;
        //                         $warnings->save();

        //                         $mdrSummary->update([
        //                             'approved_date' => date('Y-m-d'), 
        //                             'final_approved' => 1,
        //                             'penalty_status' => 'For NTE'
        //                         ]);

        //                         $user = User::where('role', "Human Resources")->get();
        //                         $yearAndMonth = $mdrSummary->year.'-'.$mdrSummary->month;
        //                         $department = $mdrSummary->departments->name;
        //                         $rate = $mdrSummary->rate;

        //                         foreach($user as $u) {
        //                             $u->notify(new HRNotification($u->name, $yearAndMonth, $department, $rate));
        //                         }
        //                     }
        //                 }
        //                 else {
        //                     if(!empty($departmentWarning)) {
        //                         $warnings = $departmentWarning->findOrFail($departmentWarning->id);
        //                         $warnings->warning_level = 0;
        //                         $warnings->save();
        //                     }   

        //                     $mdrSummary->update([
        //                         'approved_date' => date('Y-m-d'), 
        //                         'final_approved' => 1,
        //                     ]);
        //                 }

        //                 Alert::success('SUCCESS', 'Successfully Approved.');
        //                 return redirect()->to('for_approval');
                    
        //             }
        //             else {
        //                 $departmentalGoalsList->each(function($item, $key)use($approver) {
        //                     $item->update([
        //                         'status_level' => $approver->status_level+1
        //                     ]);
        //                 });

        //                 $innovation->each(function($item, $key) use($approver) {
        //                     $item->update([
        //                         'status_level' => $approver->status_level+1
        //                     ]);
        //                 });

        //                 $processDevelopmentList->each(function($item, $key)use($approver) {
        //                     $item->update([
        //                         'status_level' => $approver->status_level+1
        //                     ]);
        //                 });

        //                 $kpiScore->update([
        //                     'status_level' => $approver->status_level+1
        //                 ]);

        //                 $mdrSummary->update([
        //                     'status_level' => $approver->status_level+1
        //                 ]);

        //                 foreach($status->mdrStatus as $ms) {
        //                     if ($approver->user_id == $ms->user_id) {
        //                         $ms->update([
        //                             'status' => 1,
        //                             'start_date' => date('Y-m-d'),
        //                             'status_desc' => 'APPROVED'
        //                         ]);
        //                     }

        //                     if ($ms->status_desc == 'RETURNED') {
        //                         $ms->update([
        //                             'start_date' => null,
        //                             'status_desc' => null
        //                         ]);
        //                     }
        //                 }

        //                 $user = User::where('id', $departmentData->user_id)->first();
        //                 $approver = auth()->user()->name;
        //                 $user->notify(new ApprovedNotification($user->name, $approver, $request->monthOf));

        //                 Alert::success('SUCCESS', 'Successfully Approved.');
        //                 return redirect()->to('for_approval');
        //             }
        //         }
        //         else {
        //             Alert::error('ERROR', 'Cannot approved the MDR.');
        //             return back();
        //         }
        //     }
        // }        
    }

    public function submitScores(Request $request, $id) {
        // dd($request->all());
        $mdrScore = MdrScore::findOrFail($id);
        $mdrScore->pd_scores = $request->process_improvement_scores;
        // $mdrScore->innovation_scores = $request->innovation_scores;
        $mdrScore->timeliness = $request->timeliness;
        $mdrScore->remarks = $request->remarks;

        $total_rating = $mdrScore->score + $mdrScore->pd_scores + $mdrScore->timeliness;

        $mdrScore->total_rating = $total_rating;
        $mdrScore->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();

        // if ($kpiScoreData) {
        //     $kpiScoreData->pd_scores = $request->pdScores;
        //     $kpiScoreData->innovation_scores = $request->innovationScores;
        //     $kpiScoreData->timeliness = $request->timelinessScores;
        //     $kpiScoreData->remarks = $request->remarks;

        //     $totalInnovationAndPIScores = 0.0;
        //     if ($kpiScoreData->innovation_scores == 0.0 && $kpiScoreData->pd_scores == 0.0) {
        //         $totalInnovationAndPIScores = 0.0;
        //     }
        //     else if (($kpiScoreData->innovation_scores == 0.5 && $kpiScoreData->pd_scores == 0.5) || ($kpiScoreData->innovation_scores == 0.5 || $kpiScoreData->innovation_scores == 0.0) && ($kpiScoreData->pd_scores == 0.5 || $kpiScoreData->pd_scores == 0.0)) {
        //         $totalInnovationAndPIScores = 0.5;
        //     }
        //     else if (($kpiScoreData->innovation_scores == 1.0 && $kpiScoreData->pd_scores == 1.0) || ($kpiScoreData->innovation_scores == 0.5 || $kpiScoreData->innovation_scores == 1.0) && ($kpiScoreData->pd_scores == 0.5 || $kpiScoreData->pd_scores == 1.0) || ($kpiScoreData->innovation_scores == 1.0 || $kpiScoreData->innovation_scores == 0.0) && ($kpiScoreData->pd_scores == 1.0 || $kpiScoreData->pd_scores == 0.0)) {
        //         $totalInnovationAndPIScores = 1.0;
        //     }

        //     $totalScores = $kpiScoreData->score + $totalInnovationAndPIScores + $kpiScoreData->timeliness;

        //     $kpiScoreData->total_rating = $totalScores;
        //     $kpiScoreData->save();

        //     Alert::success('SUCCESS', 'Successfully Updated.');
        //     return back();
        // }
    }

    // public function addInnovationRemarks(Request $request) {
    //     $innovationData = Innovation::findMany($request->innovation_id);
        
    //     if ($innovationData->isNotEmpty()) {
    //         foreach($innovationData as $key=>$i) {
    //             $i->remarks = $request->remarks[$key];
    //             $i->save();
    //         }

    //         Alert::success('SUCCESS', 'Successfully Updated.');
    //     }
    //     else {
    //         Alert::error('ERROR', 'The innovation is empty.');
    //     }
    //     // $innovationData->each(function($item, $key)use($request) {
    //     //     $item->update([
    //     //         'remarks' => $request->remarks[$key]
    //     //     ]);
    //     // });

    //     return back();
    // }

    public function addPdRemarks(Request $request, $id) {
        $process_improvement = ProcessDevelopment::findOrFail($id);
        $process_improvement->remarks = $request->remarks;
        $process_improvement->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();

        // $piData = ProcessDevelopment::findMany($request->pi_id);
        
        // if ($piData->isNotEmpty()) {
        //     foreach($piData as $key=>$pi) {
        //         $pi->remarks = $request->remarks[$key];
        //         $pi->save();
        //     }

        //     Alert::success('SUCCESS', 'Successfully Updated.');
        // }
        // else {
        //     Alert::error('ERROR', 'The process improvement is empty.');
        // }
    }
}
