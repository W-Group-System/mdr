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
        
        $validator = Validator::make($request->all(), [
            'departmentGroupName' => 'required'
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {

            $mdrGroup = new MdrGroup;
            $mdrGroup->name =  $request->departmentGroupName;
            $mdrGroup->save();

            Alert::success('SUCCESS', 'Successfully Added.');
            return back();
        }
    }

    public function updateDepartmentGroups(Request $request, $id) {
        
        $validator = Validator::make($request->all(), [
            'departmentGroupName' => 'required'
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {

            $mdrGroup = MdrGroup::findOrFail($id);
            if ($mdrGroup) {
                $mdrGroup->name =  $request->departmentGroupName;
                $mdrGroup->save();
            }

            Alert::success('SUCCESS', 'Successfully Updated.');
            return back();
        }
    }

    public function deleteDepartmentGroups($id) {
        
        $departmentGroupData = MdrGroup::findOrFail($id);

        if ($departmentGroupData) {
            $departmentGroupData->delete();

            Alert::success('SUCCESS', 'Successfully Deleted.');
            return back();
        } 
    }
}
