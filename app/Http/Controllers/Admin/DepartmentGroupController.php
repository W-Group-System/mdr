<?php

namespace App\Http\Controllers\Admin;

use App\Admin\DepartmentGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DepartmentGroupController extends Controller
{
    public function index() {

        $departmentGroupList = DepartmentGroup::all();

        return view('admin.department-group',
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

            $departmentGroup = new DepartmentGroup;
            $departmentGroup->name =  $request->departmentGroupName;
            $departmentGroup->save();

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

            $departmentGroup = DepartmentGroup::findOrFail($id);
            if ($departmentGroup) {
                $departmentGroup->name =  $request->departmentGroupName;
                $departmentGroup->save();
            }

            return back();
        }
    }

    public function deleteDepartmentGroups($id) {
        
        $departmentGroupData = DepartmentGroup::findOrFail($id);

        if ($departmentGroupData) {
            $departmentGroupData->delete();

            return back();
        } 
    }
}
