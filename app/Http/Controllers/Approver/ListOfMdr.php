<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Approve;
use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ListOfMdr extends Controller
{
    public function index(Request $request) {
        $departmentList = Department::get();

        $departmentData = Department::with('kpi_scores', 'departmentKpi', 'departmentalGoals', 'process_development', 'innovation', 'user', 'approver')
            ->where('id', $request->department)
            ->first();

        return view('approver.list-of-mdr', 
            array(
                'departmentList' => $departmentList , 
                'department' => $request->department,
                'yearAndMonth' => $request->yearAndMonth,
                // 'departmentKpiGroups' => $departmentKpi,
                'data' => $departmentData
            )
        );
    }

    public function returnMdr(Request $request) {
        $departmentData = Department::with('departmentalGoals', 'process_development', 'kpi_scores')
            ->where('id', $request->department_id)
            ->get();

        foreach($departmentData as $department) {

            $departmentalGoalsList = $department->departmentalGoals()
                ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
                ->where('status_level', 1)
                ->get();

            $processDevelopmentList = $department->process_development()
                ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
                ->where('status_level', 1)
                ->get();

            $kpiScore = $department->kpi_scores()
                ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
                ->where('status_level', 1)
                ->get();

            $innovation = $department->innovation()
                ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
                ->where('status_level', 1)
                ->get();

            if ($departmentalGoalsList->isNotEmpty() && $processDevelopmentList->isNotEmpty() && $kpiScore->isNotEmpty() && $innovation->isNotEmpty()) {
                $departmentalGoalsList->each(function($item, $key) {
                    $item->update([
                        'status_level' => 0
                    ]);
                });

                $processDevelopmentList->each(function($item, $key) {
                    $item->update([
                        'status_level' => 0
                    ]);
                });

                $kpiScore->each(function($item, $key) {
                    $item->update([
                        'status_level' => 0
                    ]);
                });

                $innovation->each(function($item, $key) {
                    $item->update([
                        'status_level' => 0
                    ]);
                });

                return back()->with('return', 'Successfully Return');
            }
            else {
                return back();
            }
        }
    }

    public function addRemarks(Request $request) {
        $departmentalGoalsList =  DepartmentalGoals::where('date', $request->date)
            ->where('department_id', $request->department_id)
            ->get();

        if ($departmentalGoalsList->isNotEmpty()) {
            $departmentalGoalsList->each(function($item, $key)use($request) {
                $item->update([
                    'remarks' => $request->remarks[$key]
                ]);
            });

            return back();
        }
        else {
            return back()->with('errors', ["Can not add remarks"]);
        }
    }

    public function approveMdr(Request $request) {

        // $departmentData = Department::with('departmentalGoals', 'process_development', 'kpi_scores', 'approver')
        //     ->where('id', $request->department_id)
        //     ->first();
        
        // foreach($departmentData as $department) {

        //     $departmentalGoalsList = $department->departmentalGoals()
        //         ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
        //         ->where('status_level', 1)
        //         ->get();

        //     $processDevelopmentList = $department->process_development()
        //         ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
        //         ->where('status_level', 1)
        //         ->get();

        //     $kpiScore = $department->kpi_scores()
        //         ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
        //         ->where('status_level', 1)
        //         ->get();

        //     $innovation = $department->innovation()
        //         ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->monthOf)
        //         ->where('status_level', 1)
        //         ->get();

        //     if ($departmentalGoalsList->isNotEmpty() && $processDevelopmentList->isNotEmpty() && $kpiScore->isNotEmpty() && $innovation->isNotEmpty()) {
        //         $departmentalGoalsList->each(function($item, $key) {
        //             $item->update([
        //                 'status_level' => 0
        //             ]);
        //         });

        //         $processDevelopmentList->each(function($item, $key) {
        //             $item->update([
        //                 'status_level' => 0
        //             ]);
        //         });

        //         $kpiScore->each(function($item, $key) {
        //             $item->update([
        //                 'status_level' => 0
        //             ]);
        //         });

        //         $innovation->each(function($item, $key) {
        //             $item->update([
        //                 'status_level' => 0
        //             ]);
        //         });

        //         return back()->with('return', 'Successfully Return');
        //     }
        //     else {
        //         return back();
        //     }
        // }
    }
}
