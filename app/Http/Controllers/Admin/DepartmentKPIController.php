<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentKPIController extends Controller
{
    public function index(Request $request) {
        $departmentList = Department::with('departmentKpi')
            ->select('id', 'dept_name')
            ->get();
        
        $departmentGroupKpiList = DepartmentGroup::get();

        return view('admin.department-kpi',
            array(
                'departmentList' => $departmentList,
                'departmentGroupKpiList' => $departmentGroupKpiList,
                'department' => $request->department
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

            Alert::success('SUCCESS', 'Successfully Added.');
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

                Alert::success('SUCCESS', 'Successfully Updated.');
                return back();
            }
            
        }
    }
}
