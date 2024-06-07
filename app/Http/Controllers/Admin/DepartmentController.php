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
            ->select('id', 'dept_code', 'dept_name', 'user_id', 'target_date', 'status')
            ->get();

        $user =  User::where('status', 1)->get();

        return view('admin.department',
            array(
                'departmentList' => $departmentList,
                'user' => $user
                // 'departmentHead' => $departmentHead,
                // 'approverList' => $approverList
            )
        );
    }

    public function addDepartments(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'departmentCode' => 'unique:departments,dept_code',
        ]);

        if($validator->fails()) {
            
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $dept = new Department;
            $dept->dept_code = $request->departmentCode;
            $dept->dept_name = $request->departmentName;
            $dept->user_id = $request->departmentHead;
            $dept->target_date = $request->targetDate;
            $dept->status = 1;
            $dept->save();

            Alert::success('SUCCESS', 'Successfully Added.');
            return back();
        }
    }

    public function updateDepartments(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'departmentCode' => 'unique:departments,dept_code, ' . $id,
        ]);

        if($validator->fails()) {
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $dept = Department::findOrFail($id);

            if ($dept) {
                $dept->dept_code = $request->departmentCode;
                $dept->dept_name = $request->departmentName;
                $dept->user_id = $request->departmentHead;
                $dept->target_date = $request->targetDate;
                $dept->save();

                $approver = Approve::where('department_id', $id)->delete();
                if(!empty($request->approver)) {
                    foreach($request->approver as $key => $value) {
                        $approver = new Approve;
                        $approver->department_id = $id;
                        $approver->user_id = $value;
                        $approver->status_level = $key+1;
                        $approver->save();
                    }
                }
            }

            Alert::success('SUCCESS', 'Successfully Updated.');
            return back();
        }

        
    }

    public function deactivate(Request $request, $id) {
        $departmentData = Department::findOrFail($id);
        $departmentData->status = $request->status;
        $departmentData->save();
        
        Alert::success('SUCCESS', 'Successfully Deactivated.');
        return back();
    }

    public function activate(Request $request, $id) {
        $departmentData = Department::findOrFail($id);
        $departmentData->status = $request->status;
        $departmentData->save();

        Alert::success('SUCCESS', 'Successfully Activated.');

        return back();
    }

    
}
