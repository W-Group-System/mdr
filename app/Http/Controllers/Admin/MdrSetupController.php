<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\MdrGroup;
use App\Admin\MdrSetup;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class MdrSetupController extends Controller
{
    public function index(Request $request) {
        $mdrSetup = MdrSetup::when($request->department, function($q)use($request) {
                $q->where('department_id', $request->department);
            })
            ->get();
        
        $departmentList = Department::select('id', 'name', 'code')->where('status',"Active")->get();
        $departmentGroupKpiList = MdrGroup::select('id', 'name')->where('status',"Active")->get();

        return view('admin.mdr-setup',
            array(
                'departmentList' => $departmentList,
                'departmentGroupKpiList' => $departmentGroupKpiList,
                'department' => $request->department,
                'mdrSetup' => $mdrSetup
            )
        );
    }

    public function addDepartmentKpi(Request $request) {

        $request->validate([
            'department' => 'required',
        ]);

        $mdrSetup = new MdrSetup;
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

        $mdrSetup = MdrSetup::findOrFail($id);
        $mdrSetup->department_id = $request->department;
        $mdrSetup->mdr_group_id = 1;
        $mdrSetup->name = $request->kpiName;
        $mdrSetup->target = $request->target;
        $mdrSetup->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deactivate($id) {
        $mdrSetup = MdrSetup::findOrFail($id);
        $mdrSetup->status = "Inactive";
        $mdrSetup->save();
        
        Alert::success("Successfully Deactivated")->persistent('Dismiss');
        return back();
    }

    public function activate($id) {
        $mdrSetup = MdrSetup::findOrFail($id);
        $mdrSetup->status = "Activate";
        $mdrSetup->save();
        
        Alert::success("Successfully Activated")->persistent('Dismiss');
        return back();
    }
}
