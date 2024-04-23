<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentKPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MdrController extends Controller
{
    public function index() {

        $deparmentalGoalsList = DepartmentKPI::where('department_group_id', 1)->get();
        
        return view('dept-head.mdr',
            array(
                'departmentalGoalsList' => $deparmentalGoalsList
            )
        );
    }

    public function addActual(Request $request, $id) {

        $actualData = DepartmentKPI::findOrFail($id);

        if ($actualData) {
            $actualData->actual = $request->actual;
            $actualData->save();

            return back();
        }
    }

    public function addRemarks(Request $request, $id) {
        $remarksData = DepartmentKPI::findOrFail($id);

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

                $attachment = DepartmentKPI::findOrFail($id);

                if ($attachment) {
                    $attachment->file_path = public_path('file') . '/' . $fileName;
                    $attachment->save();
                }

                return back();
            }
            else {
                return back()->with('errors', 'You are not selecting a file');
            }
        }
    }

    public function previewAttachments(Request $request) {
        $previewData = DepartmentKPI::findOrFail($request->id);

        $filePath = $previewData->file_path;

        if (!empty($filePath)) {

            return back();
        }
    }
}
