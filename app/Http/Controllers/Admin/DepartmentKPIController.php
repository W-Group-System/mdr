<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
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
            }
            
            return back();
        }
    }
}
