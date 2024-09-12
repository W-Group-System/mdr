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
        $mdrGroup->status = "Active";
        $mdrGroup->save();

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

    public function updateDepartmentGroups(Request $request, $id) {
        $mdrGroup = MdrGroup::findOrFail($id);
        $mdrGroup->name =  $request->departmentGroupName;
        $mdrGroup->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deactivate($id) {
        
        $mdrGroupData = MdrGroup::findOrFail($id);
        $mdrGroupData->status = "Inactive";
        $mdrGroupData->save();

        Alert::success('Successfully Deactivated')->persistent('Dismiss');
        return back();
    }

    public function activate($id) {
        
        $mdrGroupData = MdrGroup::findOrFail($id);
        $mdrGroupData->status = "Active";
        $mdrGroupData->save();

        Alert::success('Successfully Activated')->persistent('Dismiss');
        return back();
    }
}
