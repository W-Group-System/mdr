<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index() {
        $departmentList = Department::select('id', 'dept_code', 'dept_name', 'dept_head_id', 'target_date')->get();

        $departmentHead = User::select('name', 'id')
            ->where('account_role', 2)
            ->get();

        return view('admin.department',
            array(
                'departmentList' => $departmentList,
                'departmentHead' => $departmentHead
            )
        );
    }

    public function addDepartments(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'departmentCode' => 'required|unique:departments,dept_code',
            'departmentName' => 'required',
            'departmentHead' => 'required',
            'targetDate' => 'required'
        ]);

        if($validator->fails()) {
            
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $dept = new Department;
            $dept->dept_code = $request->departmentCode;
            $dept->dept_name = $request->departmentName;
            $dept->dept_head_id = $request->departmentHead;
            $dept->target_date = $request->targetDate;
            $dept->save();

            return back();
        }
    }

    public function updateDepartments(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'departmentCode' => 'required|unique:departments,dept_code, ' . $id,
            'departmentName' => 'required',
            'departmentHead' => 'required',
            'targetDate' => 'required'
        ]);

        if($validator->fails()) {
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $dept = Department::findOrFail($id);

            if ($dept) {
                $dept->dept_code = $request->departmentCode;
                $dept->dept_name = $request->departmentName;
                $dept->dept_head_id = $request->departmentHead;
                $dept->target_date = $request->targetDate;
                $dept->save();
            }

            return back();
        }

        
    }

    public function deleteDepartments(Request $request, $id) {
        $departmentData = Department::findOrFail($id);

        if ($departmentData) {
            $departmentData->delete();

            return back();
        }
    }

    
}
