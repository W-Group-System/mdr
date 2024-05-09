<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentKPI;
use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DepartmentalGoalsController extends Controller
{
    public function uploadAttachments(Request $request, $id) {
        $departmentData = Department::where('id', auth()->user()->department_id)->first();

        $validator = Validator::make($request->all(), [
            'file' => 'required|max:2048'
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('file'),  $fileName);

                $attachment = new Attachments;
                $attachment->department_id = $departmentData->id;
                $attachment->department_kpi_id = $id;
                $attachment->file_path = public_path('file') . '/' . $fileName;
                $attachment->file_name = $fileName;
                $attachment->date = date('Y-m').'-'.$departmentData->target_date;
                $attachment->save();

                return back();
            }
            else {
                return back()->with('errors', 'You are not selecting a file');
            }
        }
    }

    public function deleteAttachments(Request $request) {
        
        $attachmentData = Attachments::where('id', $request->id)->first();

        if ($attachmentData) {
            $attachmentData->delete();
        }

    }
}
