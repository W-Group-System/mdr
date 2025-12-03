<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Company;
use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentController extends Controller
{
    public function index() {
        $departmentList = Department::get();

        $user =  User::where('status', "Active")->get();
        $companies =  Company::where('status', "Active")->get();

        return view('admin.department',
            array(
                'departmentList' => $departmentList,
                'user' => $user,
                'targetDate' => $this->targetDate(),
                'companies' => $companies,
            )
        );
    }

    public function addDepartments(Request $request) {
        $request->validate([
            'departmentCode' => 'unique:departments,code',
        ]);

        $dept = new Department;
        $dept->code = $request->departmentCode;
        $dept->name = $request->departmentName;
        $dept->user_id = $request->departmentHead;
        $dept->target_date = $request->targetDate;
        $dept->company_id = $request->company;
        $dept->status = "Active";
        $dept->save();

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

    public function updateDepartments(Request $request, $id) {
        $request->validate([
            'departmentCode' => 'unique:departments,code, ' . $id,
        ]);

        $dept = Department::findOrFail($id);
        $dept->code = $request->departmentCode;
        $dept->name = $request->departmentName;
        $dept->user_id = $request->departmentHead;
        $dept->target_date = $request->targetDate;
        $dept->company_id = $request->company;
        $dept->save();

        if ($request->has('approver'))
        {
            $approver = DepartmentApprovers::where('department_id', $id)->delete();
            foreach($request->approver as $key => $value) {
                $approver = new DepartmentApprovers;
                $approver->department_id = $id;
                $approver->user_id = $value;
                $approver->status_level = $key+1;
                $approver->save();
            }
        }

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function deactivate(Request $request, $id) {
        $departmentData = Department::findOrFail($id);
        $departmentData->status = "Deactivate";
        $departmentData->save();
        
        Alert::success('Successfully Deactivated')->persistent('Dismiss');
        return back();
    }

    public function activate(Request $request, $id) {
        $departmentData = Department::findOrFail($id);
        $departmentData->status = "Active";
        $departmentData->save();

        Alert::success('Successfully Activated')->persistent('Dismiss');

        return back();
    }

    public function targetDate() {
        return [
            '01' => "1st of the month",
            '02' => "2nd of the month",
            '03' => "3rd of the month",
            '04' => "4th of the month",
            '05' => "5th of the month",
            '06' => "6th of the month",
            '07' => "7th of the month",
            '08' => "8th of the month",
            '09' => "9th of the month",
            '10' => "10th of the month",
            '11' => "11th of the month",
            '12' => "12th of the month",
            '13' => "13th of the month",
            '14' => "14th of the month",
            '15' => "15th of the month",
            '16' => "16th of the month",
            '17' => "17th of the month",
            '18' => "18th of the month",
            '19' => "19th of the month",
            '20' => "20th of the month",
            '21' => "21st of the month",
            '22' => "22nd of the month",
            '23' => "23rd of the month",
            '24' => "24th of the month",
            '25' => "25th of the month",
            '26' => "26th of the month",
            '27' => "27th of the month",
            '28' => "28th of the month",
            '29' => "29th of the month",
            '30' => "30th of the month",
            '31' => "31st of the month"
        ];
    }
}
