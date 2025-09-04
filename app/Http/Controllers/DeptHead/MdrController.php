<?php

namespace App\Http\Controllers\DeptHead;

use App\AcceptanceHistory;
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
        $mdr = Mdr::findOrFail($request->mdr_id);

        return view('dept-head.edit-mdr',
            array(
                'departmentalGoals' => $departmentalGoals,
                'yearAndMonth' => $request->yearAndMonth,
                'innovations' => $innovations,
                'mdr' => $mdr
            )
        );
    }

    public function submitMdr(Request $request) 
    {
        $mdrs = Mdr::with('departmentalGoals')->where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->first();
        $departmental_goals = DepartmentalGoals::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->first();
        
        if (empty($departmental_goals))
        {
            Alert::error('Please submit KPI first, before submitting the MDR')->persistent('Dismiss');
            return back();
        }

        if ($mdrs)
        {
            if($mdrs->status === "Returned") {
                $mdrs->status = 'Pending';
                $mdrs->is_accepted = 'Accepted';
                $mdrs->level = 1;

                $innovations = Innovation::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->update(['mdr_id' => $mdrs->id]);

                $innovations = Innovation::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->get();
                if (count($innovations) > 0) {
                    $mdrs->innovation_scores = 0.5;
                }
                $mdr_approvers = MdrApprovers::where('mdr_id', $mdrs->id)->orderBy('level', 'asc')->get();
                // if (count($mdr_approvers) == 0)
                // {
                //     $mdrs->is_accepted = null;
                // }          
                // $mdrs->save();

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
                $history_logs = new AcceptanceHistory();
                $history_logs->user_id = auth()->user()->id;
                $history_logs->action = "Submitted";
                // $history_logs->remarks = $request->remarks;
                $history_logs->mdr_id = $mdrs->id;
                $history_logs->save();
            }
            $mdrs->status = 'Pending';
            // $mdrs->timeliness_approval = 'Yes';
            $innovations = Innovation::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->update(['mdr_id' => $mdrs->id]);

            $innovations = Innovation::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->get();
            if (count($innovations) > 0) {
                $mdrs->innovation_scores = 0.5;
            }
            $mdr_approvers = MdrApprovers::where('mdr_id', $mdrs->id)->orderBy('level', 'asc')->get();
            if (count($mdr_approvers) == 0)
            {
                $mdrs->is_accepted = null;
            }          
            $mdrs->save();

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
            $history_logs = new AcceptanceHistory();
            $history_logs->user_id = auth()->user()->id;
            $history_logs->action = "Submitted";
            // $history_logs->remarks = $request->remarks;
            $history_logs->mdr_id = $mdrs->id;
            $history_logs->save();
        }
        else
        {
            $mdrs = new Mdr;
            $mdrs->status = 'Pending';
            $mdrs->department_id = auth()->user()->department_id;
            $mdrs->year = date('Y', strtotime($request->year_and_month));
            $mdrs->month = date('m', strtotime($request->year_and_month));
            $mdrs->save();

            $history_logs = new AcceptanceHistory();
            $history_logs->user_id = auth()->user()->id;
            $history_logs->action = "Submitted";
            // $history_logs->remarks = $request->remarks;
            $history_logs->mdr_id = $mdrs->id;
            $history_logs->save();
            
            DepartmentalGoals::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->update(['mdr_id' => $mdrs->id]);
            $innovations = Innovation::where('year', date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->where('department_id', auth()->user()->department_id)->update(['mdr_id' => $mdrs->id]);

            if ($innovations) {
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
