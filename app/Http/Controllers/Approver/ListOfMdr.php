<?php

namespace App\Http\Controllers\Approver;

use App\AcceptanceHistory;
use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use App\Admin\MdrGroup;
use App\Admin\MdrSetup;
use App\Approver\MdrSummary;
use App\Approver\Warnings;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\Mdr;
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
use App\Notifications\NotifyDeptHead;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ListOfMdr extends Controller
{
    public function index($id) 
    {
        $mdrSummary = Mdr::with('departments','innovation','departmentalGoals')->findOrFail($id);
        
        return view('approver.list-of-mdr', 
            array(
                'mdrSummary' => $mdrSummary
            )
        );
    }

    public function addGradeAndRemarks(Request $request) 
    {
        // dd($request->all());
        $departmentalGoalsList = DepartmentalGoals::findMany($request->department_goals_id);

        // $total_weight = collect($request->weight)->sum();
        // if ($total_weight > 3.00)
        // {
        //     Alert::error('The total weight is greater than 3.00')->persistent('Dismiss');
        //     return back();
        // }
        // elseif($total_weight < 3.00)
        // {
        //     Alert::error('The total weight is less than 3.00')->persistent('Dismiss');
        //     return back();
        // }
        
        // $total_grades = collect($request->grade)->sum();
        // if ($total_grades > $total_weight)
        // {
        //     Alert::error('The total grades is not greater than total weight')->persistent('Dismiss');
        //     return back();
        // }

        foreach($departmentalGoalsList as $key=>$dptGoals)
        {
            $dptGoals->remarks = $request->remarks[$key];
            $dptGoals->grade = $request->grade[$key];
            $dptGoals->weight = $request->weight[$key];
            $dptGoals->save();
        }

        computeKpi($request->grade, $request->mdr_id);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function approveMdr(Request $request, $id) {
        // dd($request->all());
        $mdr_approvers = MdrApprovers::findOrFail($id);
        $mdr_approvers->status = $request->action;
        $mdr_approvers->remarks = $request->remarks;
        $mdr_approvers->save();

        $mdrSummary = Mdr::where('id', $mdr_approvers->mdrRelationship->id)->first();
        $nextMdrApprovers = MdrApprovers::where('mdr_id', $mdr_approvers->mdrRelationship->id)->whereIn('status', ['Waiting', 'Returned'])->orderBy('level', 'asc')->first();
        
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

                // if ($mdrSummary->score < 2.99)
                // {
                //     $warnings = Warnings::where('mdr_id',$mdrSummary->id)->first();
                //     if ($warnings == null)
                //     {
                //         $warnings = new Warnings;
                //         $warnings->department_id = $mdrSummary->department_id;
                //         $warnings->warning_level = 1;
                //         $warnings->mdr_summary_id = $mdrSummary->id;
                //         $warnings->save();
                //     }
                //     else
                //     {
                //         $warnings->warning_level = 2;
                //         $warnings->save();

                //         $mdrSummary->penalty_status = 'For NTE';
                //         $mdrSummary->save();
                //     }
                // }

                $user = User::where('role', "Human Resources")->get();
                $yearAndMonth = $mdrSummary->year.'-'.$mdrSummary->month;
                $department = $mdrSummary->departments->name;
                $rate = $mdrSummary->rate;

                foreach($user as $u) {
                    // $u->notify(new HRNotification($u->name, $yearAndMonth, $department, $rate));
                }
            }

            if (auth()->user()->role == "Department Head")
            {
                $approvers = User::where('id', $nextMdrApprovers->user_id)->first();
                // $approvers->notify(new EmailNotificationForApprovers($approvers, $mdrSummary->departments, $mdrSummary->yearAndMonth));
            }

            if (auth()->user()->role == "Approver" || auth()->user()->role == "Business Process Manager")
            {
                $user = User::where('department_id', $mdrSummary->department_id)->where('role', 'Department Head')->first();
                $yearAndMonth = $mdrSummary->yearAndMonth;
                // $user->notify(new ApprovedNotification($user->name, auth()->user()->name, $yearAndMonth));

                // if ($nextMdrApprovers) {
                //     $approvers = User::where('id', $nextMdrApprovers->user_id)->first();
                //     if ($approvers) {
                //         $approvers->notify(new EmailNotificationForApprovers(
                //             $approvers,
                //             $mdrSummary->departments,
                //             $mdrSummary->yearAndMonth
                //         ));
                //     }
                // }

                // $approvers = User::where('id', $nextMdrApprovers->user_id)->first();
                // $approver = $approvers->name;
                // $user->notify(new ApprovedNotification($user->name, $approver, $yearAndMonth));
                // $approvers->notify(new EmailNotificationForApprovers($approvers, $mdrSummary->departments, $mdrSummary->yearAndMonth));
            }

            Alert::success('Succesfully Approved')->persistent('Dismiss');
        }
        else
        {
            $returnToApprover = MdrApprovers::where('mdr_id', $mdr_approvers->mdrRelationship->id)->orderBy('level', 'asc')->get();
            $secondApprover = MdrApprovers::where('level', 2)->where('mdr_id', $mdr_approvers->mdrRelationship->id)->first();
            $firstApprover = MdrApprovers::where('level', 1)->where('mdr_id', $mdr_approvers->mdrRelationship->id)->first();
            
            if (auth()->user()->id == $firstApprover->user_id)
            {
                $mdrSummary->level = null;
                $mdrSummary->status = "Returned";
                $mdrSummary->is_accepted = null;
                $mdrSummary->save();
            }
            else
            {
                $mdrSummary->level = 1;
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
                    } else {
                        $mdrApprover->status = "Waiting";
                    }
                }
                else {
                    if ($key == 0) {
                        $mdrApprover->status = "Pending"; 
                    } elseif ($approver->user_id == auth()->user()->id){
                        $mdrApprover->status = "Returned";
                    } else {
                        $mdrApprover->status = "Waiting"; 
                    }
                }

                $mdrApprover->save();
            }

            // if (auth()->user()->role == "Approver" || auth()->user()->role == "Business Process Manager")
            // {
            //     $user = User::where('department_id', $mdrSummary->department_id)->where('role', 'Department Head')->first();
            //     $approvers = User::where('id', $mdr_approvers->user_id)->first();
            //     $approver = $approvers->name;
            //     $yearAndMonth = $mdrSummary->yearAndMonth;
            //     // $user->notify(new ReturnNotification($user->name, $yearAndMonth, $approver));
            // }

            Alert::success('Succesfully Returned')->persistent('Dismiss');
        }
        $history_logs = new AcceptanceHistory();
        $history_logs->user_id = auth()->user()->id;
        $history_logs->action = $request->action;
        $history_logs->remarks = $request->remarks;
        $history_logs->mdr_id = $mdrSummary->id;
        $history_logs->save();
        
        return redirect('for_approval');
    }

    public function submitScores(Request $request, $id) {
        // dd($request->all());
        $mdrScore = Mdr::findOrFail($id);
        $mdrScore->innovation_scores = $request->innovation_scores;
        $total_rating = $mdrScore->grade + $mdrScore->innovation_scores + $mdrScore->timeliness;
        $mdrScore->score = $total_rating;
        $mdrScore->save();

        $history_logs = new AcceptanceHistory();
        $history_logs->user_id = auth()->user()->id;
        $history_logs->action = "Edit Innovaton Score";
        $history_logs->remarks = $request->remarks;
        $history_logs->mdr_id = $mdrScore->id;
        $history_logs->save();
        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function acceptMdr(Request $request, $id) {
        $mdrSummary = Mdr::findOrFail($id);
        if($request->action === "Accept") 
        {
            if($mdrSummary->date_accepted === null) {
               $department_approvers = DepartmentApprovers::where('status','Active')->orderBy('status_level', 'asc')->get();
                foreach($department_approvers as $key=>$department_approver)
                {
                    
                    $dept_approver = new MdrApprovers;
                    $dept_approver->mdr_id = $mdrSummary->id;
                    $dept_approver->user_id = $department_approver->user_id;
                    $dept_approver->level = $key+1;
                    if ($key == 0)
                    {
                        $dept_approver->status = 'Pending';
                    }
                    else
                    {
                        $dept_approver->status = 'Waiting';
                    }
                    $dept_approver->save();
                }
                $mdrSummary->is_accepted = "Accepted";
                $mdrSummary->date_accepted = now();
                $fullTargetDate = getAdjustedTargetDate($mdrSummary->month, $mdrSummary->year, $mdrSummary->departments->target_date);
                if (now() > $fullTargetDate) 
                {
                    $mdrSummary->timeliness = 0;
                } 
                else 
                {
                    $mdrSummary->timeliness = 0.50;
                }
                $total_scores = floatval($mdrSummary->grade) + floatval($mdrSummary->timeliness) + floatval($mdrSummary->innovation_scores);
                $mdrSummary->score = $total_scores;
                $mdrSummary->save();

                $history_logs = new AcceptanceHistory();
                $history_logs->user_id = auth()->user()->id;
                $history_logs->action = $request->action;
                $history_logs->remarks = $request->remarks;
                $history_logs->mdr_id = $mdrSummary->id;
                $history_logs->save();     
            } else {
                $firstApprover = MdrApprovers::where('mdr_id', $mdrSummary->id)
                    ->orderBy('level', 'asc')
                    ->first();

                if ($firstApprover) {
                    $firstApprover->status = 'Pending';
                    $firstApprover->save();
                }
                $mdrSummary->is_accepted = "Accepted";
                $mdrSummary->date_accepted = now();
                $fullTargetDate = getAdjustedTargetDate($mdrSummary->month, $mdrSummary->year, $mdrSummary->departments->target_date);
                if (now() > $fullTargetDate) 
                {
                    $mdrSummary->timeliness = 0;
                } 
                else 
                {
                    $mdrSummary->timeliness = 0.50;
                }
                $total_scores = floatval($mdrSummary->grade) + floatval($mdrSummary->timeliness) + floatval($mdrSummary->innovation_scores);
                $mdrSummary->score = $total_scores;
                $mdrSummary->save();

                $history_logs = new AcceptanceHistory();
                $history_logs->user_id = auth()->user()->id;
                $history_logs->action = $request->action;
                $history_logs->remarks = $request->remarks;
                $history_logs->mdr_id = $mdrSummary->id;
                $history_logs->save();     

            }

            Alert::success('Successfully Accepted')->persistent('Dismiss');
        } 
        elseif($request->action === "AcceptLateApprove") 
        {
            $department_approvers = DepartmentApprovers::orderBy('status_level', 'asc')->get();
            foreach($department_approvers as $key=>$department_approver)
            {
                $dept_approver = new MdrApprovers;
                $dept_approver->mdr_id = $mdrSummary->id;
                $dept_approver->user_id = $department_approver->user_id;
                $dept_approver->level = $key+1;
                if ($key == 0)
                {
                    $dept_approver->status = 'Pending';
                }
                else
                {
                    $dept_approver->status = 'Waiting';
                }
                $dept_approver->save();
            }
            $mdrSummary->is_accepted = "accepted";
            $mdrSummary->save();
        } 
        elseif($request->action === "Returned") 
        {
            $mdrSummary->is_accepted = "Returned";
            $mdrSummary->status = "Returned";
            $mdrSummary->save();

            $history_logs = new AcceptanceHistory();
            $history_logs->user_id = auth()->user()->id;
            $history_logs->action = $request->action;
            $history_logs->remarks = $request->remarks;
            $history_logs->mdr_id = $mdrSummary->id;
            $history_logs->save();

            Alert::success('Successfully Returned')->persistent('Dismiss');
        } 
        elseif($request->action === "Timeliness Approval") 
        {
            $mdrSummary->timeliness_approval = "Yes";
            $mdrSummary->timeliness_remarks = $request->remarks;
            $mdrSummary->save();

            Alert::success('Successfully Approved')->persistent('Dismiss');
        }

        // DepartmentalGoals::where('department_id', $request->department_id)
        //     ->where('yearAndMonth', $request->yearAndMonth)
        //     ->update(['mdr_summary_id' => $mdrSummary->id]);

        // Innovation::where('department_id', $request->department_id)
        //     ->where('yearAndMonth', $request->yearAndMonth)
        //     ->update(['mdr_summary_id' => $mdrSummary->id]);

        // ProcessDevelopment::where('department_id', $request->department_id)
        //     ->where('yearAndMonth', $request->yearAndMonth)
        //     ->update(['mdr_summary_id' => $mdrSummary->id]);

        // $user = User::where('role', 'Department Head')->where('department_id', $request->department_id)->first();
        // $user->notify(new NotifyDeptHead($user->name, $request->yearAndMonth));

        return redirect('/for_acceptance');
    }
}
