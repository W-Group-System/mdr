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
use App\DeptHead\MdrApprovers;
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
        $mdrSetup = MdrSetup::where('department_id', auth()->user()->department_id)->where('status', 'Active')->get();

        $departmentalGoals = DepartmentalGoals::where('department_id', auth()->user()->department_id)
            ->where('yearAndMonth', $request->yearAndMonth)
            ->get();

        $innovation = Innovation::where('department_id', auth()->user()->department_id)
            ->where('yearAndMonth', $request->yearAndMonth)
            ->get();

        $process_improvement = ProcessDevelopment::where('department_id', auth()->user()->department_id)
            ->where('yearAndMonth', $request->yearAndMonth)
            ->get();
        
        return view('dept-head.mdr',
            array(
                'mdrSetup' => $mdrSetup,
                // 'approver' => $approver,
                'yearAndMonth' => $request->yearAndMonth,
                'departmentalGoals' => $departmentalGoals,
                'innovation' => $innovation,
                'process_improvement' => $process_improvement
            )
        );
    }

    public function mdrView(Request $request) {
    //   $mdrScore = MdrScore::query();

    //   if (!empty($request->filterYearAndMonth)) {
    //     $mdrScore->where('year', date('Y', strtotime($request->filterYearAndMonth)))
    //       ->where('month', date('m', strtotime($request->filterYearAndMonth)));
    //   }

    //   $mdrScore = $mdrScore->where('department_id', auth()->user()->department_id)
    //     ->orderBy('month', 'DESC')
    //     ->get();

    //   $lastSubmittedMdr = MdrScore::where('department_id', auth()->user()->department_id)->orderBy('month', 'DESC')->first();
    //   $yearAndMonth = $lastSubmittedMdr->year.'-'.$lastSubmittedMdr->month;

        $department_approvers = DepartmentApprovers::where('department_id', auth()->user()->department_id)->get();
        $mdrScore = MdrScore::where('department_id', auth()->user()->department_id)->get();
        $mdrSummary = MdrSummary::where('department_id', auth()->user()->department_id)->orderBy('id', 'desc')->first();
        $mdrApprovers = MdrApprovers::where('department_id', auth()->user()->department_id)->get();

        return view('dept-head.mdr-list', array(
                'mdrScore' => $mdrScore,
                'yearAndMonth' => $mdrSummary != null ? $mdrSummary->yearAndMonth : '',
                'department_approvers' => $department_approvers,
                'mdrApprovers' => $mdrApprovers
            )
        );
    }

    // public function edit(Request $request) {
    //     $departmentalGoals = DepartmentalGoals::where('department_id', auth()->user()->department_id)
    //         ->where('yearAndMonth', $request->yearAndMonth)
    //         ->get();

    //     $innovation = Innovation::where('department_id', auth()->user()->department_id)
    //         ->where('yearAndMonth', $request->yearAndMonth)
    //         ->get();

    //     $process_improvement = ProcessDevelopment::where('department_id', auth()->user()->department_id)
    //         ->where('yearAndMonth', $request->yearAndMonth)
    //         ->get();

    //     $department_approvers = DepartmentApprovers::where('department_id', auth()->user()->department_id)->get();

    //     return view('dept-head.edit-mdr',
    //         array(
    //             'departmentalGoals' => $departmentalGoals,
    //             'department_approvers' => $department_approvers,
    //             'yearAndMonth' => $request->yearAndMonth,
    //             'innovation' => $innovation,
    //             'process_improvement' => $process_improvement
    //         )
    //     );
    // }

    public function approveMdr(Request $request) {
        $mdrSummary = MdrSummary::where('department_id', $request->department_id)
            ->where('yearAndMonth', $request->yearAndMonth)
            ->first();

        if ($mdrSummary == null)
        {
            $mdrSummary = new MdrSummary;
            $mdrSummary->department_id = $request->department_id;
            $mdrSummary->yearAndMonth = $request->yearAndMonth;
            $mdrSummary->deadline = date('Y-m', strtotime("+1 month", strtotime($request->yearAndMonth))).'-'. auth()->user()->department->target_date;
            $mdrSummary->submission_date = date('Y-m-d');
            $mdrSummary->status = "Pending";
            $mdrSummary->rate = null;
            $mdrSummary->level = 1;
            $mdrSummary->save();

            $department_approvers = DepartmentApprovers::where('department_id', auth()->user()->department_id)->get();
            foreach($department_approvers as $key=>$approvers)
            {
                $mdrApprovers = new MdrApprovers;
                $mdrApprovers->user_id = $approvers->user_id;
                $mdrApprovers->mdr_summary_id = $mdrSummary->id;
                $mdrApprovers->department_id =  $request->department_id;
                $mdrApprovers->level =  $key+1;
                if ($approvers->status_level == 1)
                {
                    $mdrApprovers->status = "Pending";
                    $mdrApprovers->start_date = date('Y-m-d');
                }
                else
                {
                    $mdrApprovers->status = "Waiting";
                }
                $mdrApprovers->start_date = date('Y-m-d');
                $mdrApprovers->save();
            }
        }
        else
        {
            $mdrSummary->rate = null;
            $mdrSummary->level = 1;
            $mdrSummary->save();

            $mdrApprovers = MdrApprovers::where('mdr_summary_id', $mdrSummary->id)->get();
            foreach($mdrApprovers as $approver)
            {
                if ($approver->level == 1)
                {
                    $approver->status = "Pending";
                    $approver->start_date = date('Y-m-d');
                }
                else
                {
                    $approver->status = "Waiting";
                }
                $approver->start_date = date('Y-m-d');
                $approver->save();
            }
        }

        DepartmentalGoals::where('department_id', $request->department_id)
            ->where('yearAndMonth', $request->yearAndMonth)
            ->update(['mdr_summary_id' => $mdrSummary->id]);

        Innovation::where('department_id', $request->department_id)
            ->where('yearAndMonth', $request->yearAndMonth)
            ->update(['mdr_summary_id' => $mdrSummary->id]);

        ProcessDevelopment::where('department_id', $request->department_id)
            ->where('yearAndMonth', $request->yearAndMonth)
            ->update(['mdr_summary_id' => $mdrSummary->id]);

        MdrScore::where('department_id', $request->department_id)->where('yearAndMonth', $request->yearAndMonth)->update(['mdr_summary_id' => $mdrSummary->id]);

        Alert::success('Successfully Approved')->persistent('Dismiss');
        return back();
    }

    public function submitMdr(Request $request) {
        
        $userData = User::where('department_id', auth()->user()->department_id)
            ->where('role', "Department Head")
            ->first();

        $userData->notify(new NotifyDeptHead($userData->name, $request->yearAndMonth));

        Alert::success('SUCCESS', 'The MDR is successfully submit.');
        return back();
    }
}
