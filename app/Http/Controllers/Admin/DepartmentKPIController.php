<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DepartmentKPIController extends Controller
{
    public function index() {

        $departmentList = Department::select('id', 'dept_name')->get();

        $departmentKpi = DepartmentKPI::all();

        $departmentGroupKpiList = DepartmentGroup::all();

        return view('admin.department-kpi',
            array(
                'departmentList' => $departmentList,
                'departmentKpi' => $departmentKpi,
                'departmentGroupKpiList' => $departmentGroupKpiList
            )
        );
    }

    public function addDepartmentKpi(Request $request) {

        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'departmentGroupKpi' => 'required',
            'kpiName' => 'required',
            'target' => 'required'
        ]);

        if($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $departmentKpi = new DepartmentKPI;
            $departmentKpi->department_id = $request->department;
            $departmentKpi->department_group_id = $request->departmentGroupKpi;
            $departmentKpi->name = $request->kpiName;
            $departmentKpi->target = $request->target;
            $departmentKpi->save();

            $departmentalGoals = new DepartmentalGoals;
            $departmentalGoals->department_id = $request->department;
            $departmentalGoals->department_group_id = $request->departmentGroupKpi;
            $departmentalGoals->department_kpi_id = $departmentKpi->id;
            $departmentalGoals->kpi_name = $request->kpiName;
            $departmentalGoals->target = $request->target;
            $departmentalGoals->date = date('Y-m-d');
            $departmentalGoals->save();
            
            return back();
        }
    }

    public function updateDepartmentKpi(Request $request, $id) {
    
        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'departmentGroupKpi' => 'required',
            'kpiName' => 'required',
            'target' => 'required'
        ]);

        if($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $departmentKpi = DepartmentKPI::findOrFail($id);
            if ($departmentKpi) {
                $departmentKpi->department_id = $request->department;
                $departmentKpi->department_group_id = $request->departmentGroupKpi;
                $departmentKpi->name = $request->kpiName;
                $departmentKpi->target = $request->target;
                $departmentKpi->save();

                $month = date('m');
                $departmentalGoals = DepartmentalGoals::where('date', "LIKE", '%'.$month.'%')
                    ->where('department_kpi_id', $id)
                    ->first();

                if (!empty($departmentalGoals)) {
                    $departmentalGoals->kpi_name = $request->kpiName;
                    $departmentalGoals->target = $request->target;
                    $departmentalGoals->save();
                }
                else {
                    $departmentalGoals = new DepartmentalGoals;
                    $departmentalGoals->department_id = $request->department;
                    $departmentalGoals->department_group_id = $request->departmentGroupKpi;
                    $departmentalGoals->department_kpi_id = $departmentKpi->id;
                    $departmentalGoals->kpi_name = $request->kpiName;
                    $departmentalGoals->target = $request->target;
                    $departmentalGoals->date = date('Y-m-d');
                    $departmentalGoals->save();
                }
            }
            
            return back();
        }
    }
}
