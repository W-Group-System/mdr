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
        $validator = Validator::make($request->all(), [
            'file' => 'required|max:2048|mimes:pdf'
        ]);

        if ($validator->fails()) {

            return back()->with('kpiErrors', $validator->errors()->all());
        }
        else {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('file'),  $fileName);

                $departmentData = Department::select('id', 'target_date')
                    ->where('id', auth()->user()->department_id)
                    ->first();

                $attachment = new Attachments;
                $attachment->department_id = $departmentData->id;
                $attachment->department_kpi_id = $id;
                $attachment->file_path = public_path('file') . '/' . $fileName;
                $attachment->file_name = $fileName;
                $attachment->year = date('Y');
                $attachment->month = date('m');
                $attachment->deadline = date('Y-m', strtotime("+1month")).'-'.$departmentData->target_date;
                $attachment->save();

                return back();
            }
            else {
                return back()->with('kpiErrors', 'You are not selecting a file');
            }
        }
    }

    public function deleteAttachments(Request $request) {
        $attachmentData = Attachments::findOrFail($request->id);

        if ($attachmentData) {
            $attachmentData->delete();
        }

    }
}
