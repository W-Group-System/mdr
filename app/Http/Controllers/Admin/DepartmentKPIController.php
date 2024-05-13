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
        $departmentList = Department::select('id', 'dept_name')->get();

        $departmentKpi = DepartmentKPI::where('department_id', $request->department)
            ->get();
        
        $departmentGroupKpiList = DepartmentGroup::get();

        return view('admin.department-kpi',
            array(
                'departmentList' => $departmentList,
                'departmentKpi' => $departmentKpi,
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

            $departmentalGoals = new DepartmentalGoals;
            $departmentalGoals->department_id = $request->department;
            $departmentalGoals->department_group_id = $request->departmentGroupKpi;
            $departmentalGoals->department_kpi_id = $departmentKpi->id;
            $departmentalGoals->kpi_name = $request->kpiName;
            $departmentalGoals->target = $request->target;
            $departmentalGoals->date = date('Y-m').'-'.$departmentKpi->departments->target_date;
            $departmentalGoals->save();
            
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

                Alert::success('SUCCESS', 'Successfully Updated.');
                $departmentKpi->save();

                $departmentalGoals = DepartmentalGoals::where('department_kpi_id', $id)->first();
                
                if (!empty($departmentalGoals)) {
                    $departmentalGoals->kpi_name = $request->kpiName;
                    $departmentalGoals->target = $request->target;
                    $departmentalGoals->date = date('Y-m').'-'.$departmentKpi->departments->target_date;
                    $departmentalGoals->save();
                }
                // else {
                //     $departmentalGoals = new DepartmentalGoals;
                //     $departmentalGoals->department_id = $request->department;
                //     $departmentalGoals->department_group_id = $request->departmentGroupKpi;
                //     $departmentalGoals->department_kpi_id = $departmentKpi->id;
                //     $departmentalGoals->kpi_name = $request->kpiName;
                //     $departmentalGoals->target = $request->target;
                //     $departmentalGoals->date = date('Y-m-d');
                //     $departmentalGoals->save();
                // }
            }
            
            return back();
        }
    }
}
