<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentKPI;
use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DepartmentalGoalsController extends Controller
{
    public function addActual(Request $request, $id) {
        
        $actualData = DepartmentalGoals::findOrFail($id);

        if ($actualData) {
            $actualData->actual = $request->actual;
            $actualData->save();

            return back();
        }
    }

    public function addRemarks(Request $request, $id) {
        $remarksData = DepartmentalGoals::findOrFail($id);

        if ($remarksData) {
            $remarksData->remarks = $request->remarks;
            $remarksData->save();

            return back();
        }
    }
    
    public function uploadAttachments(Request $request, $id) {
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
                $attachment->departmental_goals_id = $id;
                $attachment->file_path = public_path('file') . '/' . $fileName;
                $attachment->file_name = $fileName;
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
