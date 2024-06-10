<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\MdrSetup;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class MdrSetupController extends Controller
{
    public function index(Request $request) {
        $departmentKpi = MdrSetup::where('department_id', $request->department)->get();
        
        $departmentList = Department::select('id', 'name')->get();

        $departmentGroupKpiList = DepartmentGroup::select('id', 'name')->get();

        return view('admin.department-kpi',
            array(
                'departmentList' => $departmentList,
                'departmentGroupKpiList' => $departmentGroupKpiList,
                'department' => $request->department,
                'departmentKpi' => $departmentKpi
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
            $departmentKpi = new MdrSetup;
            $departmentKpi->department_id = $request->department;
            $departmentKpi->mdr_group_id = $request->departmentGroupKpi;
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
            $departmentKpi = MdrSetup::findOrFail($id);
            if ($departmentKpi) {
                $departmentKpi->department_id = $request->department;
                $departmentKpi->mdr_group_id = $request->departmentGroupKpi;
                $departmentKpi->name = $request->kpiName;
                $departmentKpi->target = $request->target;
                $departmentKpi->save();

                Alert::success('SUCCESS', 'Successfully Updated.');
                return back();
            }
            
        }
    }

    public function deleteDepartmentKpi(Request $request, $id) {
        
        $checkIfExist = DepartmentalGoals::select('mdr_group_id')->where('mdr_group_id', $id)->get();

        if ($checkIfExist->isNotEmpty()) {

            Alert::error('ERROR', "Can't Delete KPI because it's already used.");
        }
        else {
            
            $departmentKpi = MdrSetup::findOrFail($id);
            if($departmentKpi) {
                $departmentKpi->delete();
            }

            Alert::success('SUCCESS', "Successfully Deleted.");
        }

        return back();
    }
}
