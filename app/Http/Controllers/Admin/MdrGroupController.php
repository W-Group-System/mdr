<?php

namespace App\Http\Controllers\Admin;

use App\Admin\MdrGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class MdrGroupController extends Controller
{
    public function index() {

        $departmentGroupList = MdrGroup::all();

        return view('admin.mdr-group',
            array(
                'departmentGroupList' => $departmentGroupList
            )
        );
    }

    public function addDepartmentGroups(Request $request) {
        $mdrGroup = new MdrGroup;
        $mdrGroup->name =  $request->departmentGroupName;
        $mdrGroup->status = 1;
        $mdrGroup->save();

        Alert::success('SUCCESS', 'Successfully Added.');
        return back();
    }

    public function updateDepartmentGroups(Request $request, $id) {
        $mdrGroup = MdrGroup::findOrFail($id);
        $mdrGroup->name =  $request->departmentGroupName;
        $mdrGroup->save();

        Alert::success('SUCCESS', 'Successfully Updated.');
        return back();
    }

    public function deactivate($id) {
        
        $mdrGroupData = MdrGroup::findOrFail($id);
        $mdrGroupData->status = 0;
        $mdrGroupData->save();

        Alert::success('SUCCESS', 'Successfully Deactivated.');
        return back();
    }

    public function activate($id) {
        
        $mdrGroupData = MdrGroup::findOrFail($id);
        $mdrGroupData->status = 1;
        $mdrGroupData->save();

        Alert::success('SUCCESS', 'Successfully Activated.');
        return back();
    }
}
