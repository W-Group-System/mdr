<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Approve;
use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentController extends Controller
{
    public function index() {
        $departmentList = Department::with('approver')
            ->select('id', 'dept_code', 'dept_name', 'dept_head_id', 'target_date')
            ->get();

        $departmentHead = User::select('name', 'id')
            ->where('account_role', 2)
            ->get();

        $approverList = User::select('name', 'id')
            ->where('account_role', 1)
            ->get();

        return view('admin.department',
            array(
                'departmentList' => $departmentList,
                'departmentHead' => $departmentHead,
                'approverList' => $approverList
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

            Alert::success('SUCCESS', 'Successfully Added.');
            return back();
        }
    }

    public function updateDepartments(Request $request, $id) {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'departmentCode' => 'required|unique:departments,dept_code, ' . $id,
            'departmentName' => 'required',
            // 'departmentHead' => 'required',
            'targetDate' => 'required',
            'approver' => 'required'
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

                $approver = Approve::where('department_id', $id)->delete();
                
                foreach($request->approver as $key => $value) {
                    $approver = new Approve;
                    $approver->department_id = $id;
                    $approver->user_id = $value;
                    $approver->status_level = $key+1;
                    $approver->save();
                }
            }

            Alert::success('SUCCESS', 'Successfully Updated.');
            return back();
        }

        
    }

    public function deleteDepartments(Request $request, $id) {
        $departmentData = Department::findOrFail($id);

        if ($departmentData) {
            $departmentData->delete();

            Alert::success('SUCCESS', 'Successfully Deleted.');
            return back();
        }
    }

    
}
