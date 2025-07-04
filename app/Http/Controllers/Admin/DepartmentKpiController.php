<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\MdrGroup;
use App\DepartmentKpi;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentKpiController extends Controller
{
    public function index(Request $request) {
        $department_kpis = DepartmentKpi::with('mdr_group','department')
            ->where('department_id', $request->department)
            ->orderBy('department_id', 'asc')
            ->get();
        
        $departmentList = Department::select('id', 'name', 'code')->where('status',"Active")->get();

        return view('admin.department_kpi',
            array(
                'departmentList' => $departmentList,
                'department' => $request->department,
                'department_kpis' => $department_kpis
            )
        );
    }

    public function addDepartmentKpi(Request $request) {

        $request->validate([
            'department' => 'required',
        ]);

        $mdrSetup = new DepartmentKpi;
        $mdrSetup->department_id = $request->department;
        $mdrSetup->mdr_group_id = 1;
        $mdrSetup->name = $request->kpiName;
        $mdrSetup->target = $request->target;
        $mdrSetup->status = "Active";
        $mdrSetup->save();

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

    public function updateDepartmentKpi(Request $request, $id) {
    
        $request->validate([
            'department' => 'required',
        ]);

        $mdrSetup = DepartmentKpi::findOrFail($id);
        $mdrSetup->department_id = $request->department;
        $mdrSetup->mdr_group_id = 1;
        $mdrSetup->name = $request->kpiName;
        $mdrSetup->target = $request->target;
        $mdrSetup->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deactivate($id) {
        $mdrSetup = DepartmentKpi::findOrFail($id);
        $mdrSetup->status = "Inactive";
        $mdrSetup->save();
        
        Alert::success("Successfully Deactivated")->persistent('Dismiss');
        return back();
    }

    public function activate($id) {
        $mdrSetup = DepartmentKpi::findOrFail($id);
        $mdrSetup->status = "Activate";
        $mdrSetup->save();
        
        Alert::success("Successfully Activated")->persistent('Dismiss');
        return back();
    }
}
