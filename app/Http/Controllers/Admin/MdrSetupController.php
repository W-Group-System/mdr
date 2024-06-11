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
        if (!empty($request->department)) {
            $mdrSetup = MdrSetup::where('department_id',$request->department)->get()->sortBy('department_id');
        }
        else {
            $mdrSetup = MdrSetup::get()->sortBy('department_id')->sortByDesc('department_id');
        }
        
        $departmentList = Department::select('id', 'name')->where('status',1)->get();

        $departmentGroupKpiList = MdrGroup::select('id', 'name')->where('status',1)->get();

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
            'departmentGroupKpi' => 'required',
        ]);

        $mdrSetup = new MdrSetup;
        $mdrSetup->department_id = $request->department;
        $mdrSetup->mdr_group_id = $request->departmentGroupKpi;
        $mdrSetup->name = $request->kpiName;
        $mdrSetup->target = $request->target;
        $mdrSetup->status = 1;
        $mdrSetup->save();

        Alert::success('SUCCESS', 'Successfully Added.');
        return back();
    }

    public function updateDepartmentKpi(Request $request, $id) {
    
        $request->validate([
            'department' => 'required',
            'departmentGroupKpi' => 'required',
        ]);

        $mdrSetup = MdrSetup::findOrFail($id);
        $mdrSetup->department_id = $request->department;
        $mdrSetup->mdr_group_id = $request->departmentGroupKpi;
        $mdrSetup->name = $request->kpiName;
        $mdrSetup->target = $request->target;
        $mdrSetup->save();

        Alert::success('SUCCESS', 'Successfully Updated.');
        return back();
    }

    public function deactivate($id) {
        $mdrSetup = MdrSetup::findOrFail($id);
        $mdrSetup->status = 0;
        $mdrSetup->save();
        
        Alert::success('SUCCESS', "Successfully Deactivated.");
        return back();
    }

    public function activate($id) {
        $mdrSetup = MdrSetup::findOrFail($id);
        $mdrSetup->status = 1;
        $mdrSetup->save();
        
        Alert::success('SUCCESS', "Successfully Activated.");
        return back();
    }
}
