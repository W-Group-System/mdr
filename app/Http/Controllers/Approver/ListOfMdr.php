<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ListOfMdr extends Controller
{
    public function index(Request $request) {
        $departmentList = Department::get();

        // $departmentKpi = DepartmentGroup::with('departmentKpi')->get();
        // $departmentKpi = DepartmentGroup::with(['departmentKpi' => function($query)use($request) {
        //     // dd($query);
        //     $query->where('department_id', $request->department);
        // }])->get();
        
        $departmentData = Department::with('kpi_scores', 'departmentKpi', 'departmentalGoals', 'process_development')
            ->where('id', $request->department)
            ->get();

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

            if (!empty($departmentalGoalsList) && !empty($processDevelopmentList) && !empty($kpiScore)) {
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

        if (!empty($departmentalGoalsList)) {
            $departmentalGoalsList->each(function($item, $key)use($request) {
                $item->update([
                    'remarks' => $request->remarks[$key]
                ]);
            });

            return back();
        }
    }

}
