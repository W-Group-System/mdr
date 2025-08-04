<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use App\Admin\MdrGroup;
use App\Admin\MdrSetup;
use App\Approver\MdrSummary;
use App\DepartmentKpi;
use App\DeptHead\Attachments;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\Mdr;
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
        $department_kpis = DepartmentKpi::where('department_id', auth()->user()->department_id)->where('status', 'Active')->orderBy('name', 'asc')->get();
        $departmentalGoals = DepartmentalGoals::where('department_id', auth()->user()->department_id)->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))->get();
        $innovations = Innovation::where('department_id', auth()->user()->department_id)->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))->get();
        $mdr_groups = MdrGroup::get();
        
        return view('dept-head.mdr',
            array(
                'department_kpis' => $department_kpis,
                'yearAndMonth' => $request->yearAndMonth,
                'departmentalGoals' => $departmentalGoals,
                'innovations' => $innovations,
                'mdr_groups' => $mdr_groups
            )
        );
    }

    public function mdrView(Request $request) {
        $department_approvers = DepartmentApprovers::get();
        $mdr_year_exists = Mdr::where('department_id', auth()->user()->department_id)->orderBy('year', 'desc')->orderBy('month', 'desc')->first();
        $mdrs = Mdr::where('department_id', auth()->user()->department_id)->orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        return view('dept-head.department_mdr', array(
                'mdrs' => $mdrs,
                'year_and_month' => $mdr_year_exists ? $mdr_year_exists->year.'-'.$mdr_year_exists->month : '',
                'department_approvers' => $department_approvers,
                // 'mdrApprovers' => $mdrApprovers
            )
        );
    }

    public function edit(Request $request) {
        // dd($request->all());
        $departmentalGoals = DepartmentalGoals::where('department_id', auth()->user()->department_id)->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))->get();
        $innovations = Innovation::where('department_id', auth()->user()->department_id)->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))->get();

        // $process_improvement = ProcessDevelopment::where('department_id', auth()->user()->department_id)
        //     ->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))
        //     ->get();

        // $department_approvers = DepartmentApprovers::where('department_id', auth()->user()->department_id)->get();

        // $mdrSummary = MdrSummary::where('department_id', auth()->user()->department_id)->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))->first();

        return view('dept-head.edit-mdr',
            array(
                'departmentalGoals' => $departmentalGoals,
                // 'department_approvers' => $department_approvers,
                'yearAndMonth' => $request->yearAndMonth,
                'innovations' => $innovations,
                // 'process_improvement' => $process_improvement,
                // 'mdrSummary' => $mdrSummary
            )
        );
    }

    public function approveMdr(Request $request) {
        $mdrSummary = Mdr::where('department_id', $request->department_id)
            ->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))
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
            $mdrSummary->user_id = auth()->user()->id;
            $mdrSummary->save();

            $department_approvers = DepartmentApprovers::where('department_id', auth()->user()->department_id)->get();
            foreach($department_approvers as $key=>$approvers)
            {
                $mdrApprovers = new MdrApprovers;
                $mdrApprovers->user_id = $approvers->user_id;
                $mdrApprovers->mdr_id = $mdrSummary->id;
                $mdrApprovers->department_id =  $request->department_id;
                $mdrApprovers->level =  $key+1;
                if ($approvers->status_level == 1)
                {
                    $mdrApprovers->status = "Pending";
                    $mdrApprovers->updated_at = date('Y-m-d');
                }
                else
                {
                    $mdrApprovers->status = "Waiting";
                }
                $mdrApprovers->updated_at = date('Y-m-d');
                $mdrApprovers->save();
            }
        }
        else
        {
            $mdrSummary->rating = null;
            $mdrSummary->level = 1;
            $mdrSummary->save();

            $mdrApprovers = MdrApprovers::where('mdr_id', $mdrSummary->id)->get();
            foreach($mdrApprovers as $approver)
            {
                if ($approver->level == 1)
                {
                    $approver->status = "Pending";
                    $approver->updated_at = date('Y-m-d');
                }
                else
                {
                    $approver->status = "Waiting";
                }
                $approver->updated_at = date('Y-m-d');
                $approver->save();
            }
        }

        DepartmentalGoals::where('department_id', $request->department_id)
            ->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))
            ->update(['mdr_id' => $mdrSummary->id]);

        Innovation::where('department_id', $request->department_id)
            ->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))
            ->update(['mdr_id' => $mdrSummary->id]);

        // ProcessDevelopment::where('department_id', $request->department_id)
        //     ->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))
        //     ->update(['mdr_id' => $mdrSummary->id]);

        // MdrScore::where('department_id', $request->department_id)->where('year', date('Y', strtotime($request->yearAndMonth)))->where('month', date('m', strtotime($request->yearAndMonth)))->update(['mdr_id' => $mdrSummary->id]);

        $user = User::where('role', 'Department Head')->where('department_id', $request->department_id)->first();
        $user->notify(new NotifyDeptHead($user->name, $request->yearAndMonth));

        Alert::success('Successfully Approved')->persistent('Dismiss');
        return redirect('/mdr');
    }

    public function submitMdr(Request $request) {
        $mdrs = Mdr::with('departmentalGoals')->where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->first();
        $departmental_goals = DepartmentalGoals::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->first();
        
        if (empty($departmental_goals))
        {
            Alert::error('Please submit KPI first, before submitting the MDR')->persistent('Dismiss');
            return back();
        }

        if ($mdrs)
        {
            $mdrs->status = 'Pending';
            $mdrs->timeliness_approval = 'Yes';
            $mdrs->save();

            $mdr_approvers = MdrApprovers::where('mdr_id', $mdrs->id)->orderBy('level', 'asc')->get();
            foreach($mdr_approvers as $key=>$mdr_approver)
            {
                if ($key == 0)
                {
                    $mdr_approver->status = 'Pending';
                }
                else
                {
                    $mdr_approver->status = 'Waiting';
                }
                $mdr_approver->save();
            }
        }
        else
        {
            $mdrs = new Mdr;
            $mdrs->status = 'Pending';
            $mdrs->department_id = auth()->user()->department_id;
            $mdrs->year = date('Y', strtotime($request->year_and_month));
            $mdrs->month = date('m', strtotime($request->year_and_month));
            $mdrs->save();

            DepartmentalGoals::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->update(['mdr_id' => $mdrs->id]);
            Innovation::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->update(['mdr_id' => $mdrs->id]);

            $department_approvers = DepartmentApprovers::orderBy('status_level', 'asc')->where('status','Active')->get();
            foreach($department_approvers as $key=>$department_approver)
            {
                $dept_approver = new MdrApprovers;
                $dept_approver->mdr_id = $mdrs->id;
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

            $hasInnovation = Innovation::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->exists();

            if ($hasInnovation) {
                $mdrs->innovation_scores = 0.5;
                $mdrs->save();
            }

            // $userData = User::where('department_id', auth()->user()->department_id)
            //     ->where('role', "Department Head")
            //     ->first();

            // $userData->notify(new NotifyDeptHead($userData->name, $request->yearAndMonth));
        }

        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return redirect('mdr');
    }
}
