<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\DeptHead\KpiScore;
use App\DeptHead\ProcessDevelopment;
use App\DeptHead\ProcessDevelopmentAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProcessDevelopmentController extends Controller
{
    public function add(Request $request) {
        
        $departmentData = Department::with('kpi_scores')
            ->where('id',  auth()->user()->department_id)
            ->first();

        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'accomplishedDate' => 'required',
            'file' => 'required|max:2048'
        ]);

        if($validator->fails()) {

            return back()->with('pdError', $validator->errors()->all());
        }
        else {
            $checkStatus = ProcessDevelopment::where('status_level', 1)
                ->where('date', $request->monthOf . '-' . $departmentData->target_date)
                ->where('department_id', auth()->user()->department_id)
                ->get();

            if ($checkStatus->isNotEmpty()) {

                return back()->with('pdError', ['Failed. Because your MDR has been approved.']);
            }
            else {
                if($request->hasFile('file')) {
                    $processDevelopment = new ProcessDevelopment;
                    $processDevelopment->department_id = auth()->user()->department_id;
                    $processDevelopment->department_group_id = $request->pd_id;
                    $processDevelopment->description = $request->description;
                    $processDevelopment->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
                    $processDevelopment->status_level = 0;
                    $processDevelopment->date = $request->monthOf . '-' . $departmentData->target_date;
                    $processDevelopment->save();
    
                    $file = $request->file('file');
                    $fileName = time() . '-' . $file->getClientOriginalName();
                    $file->move(public_path('file'),  $fileName);
    
                    $attachment = new ProcessDevelopmentAttachments;
                    $attachment->pd_id = $processDevelopment->id;
                    $attachment->filepath = public_path('file') . '/' . $fileName;
                    $attachment->filename = $fileName;
                    $attachment->save();
    
                    $departmentData->kpi_scores()
                        ->where('department_id', $departmentData->id)
                        ->where('date', $request->monthOf.'-'.$departmentData->target_date)
                        ->update(['pd_scores' => 0.5]);

                    return back();
                }
                else {
                    
                    return back()->with('pdError', 'You are not selecting a file.');
                }
            }
        }   
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'accomplishedDate' => 'required',
            'file' => 'max:2048'
        ]);
        
        if($validator->fails()) {

            return back()->with('pdError', $validator->errors()->all());
        }
        else {
            if($request->hasFile('file')) {
                $processDevelopmentData = ProcessDevelopment::findOrFail($id);

                if ($processDevelopmentData) {
                    $processDevelopmentData->description = $request->description;
                    $processDevelopmentData->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
                    $processDevelopmentData->save();
                }

                $file = $request->file('file');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('file'),  $fileName);

                $attachment = ProcessDevelopmentAttachments::where('pd_id', $id)->first();

                if (!empty($attachment)) {
                    $attachment->filepath = public_path('file') . '/' . $fileName;
                    $attachment->filename = $fileName;
                    $attachment->save();
                }

                return back();
            }
            else {
                $processDevelopmentData = ProcessDevelopment::findOrFail($id);

                if ($processDevelopmentData) {
                    $processDevelopmentData->description = $request->description;
                    $processDevelopmentData->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
                    $processDevelopmentData->save();
                }

                return back();
            }
        }   
    }

    public function delete(Request $request, $id) {
        // dd($request->all());
        // $processDevelopmentData = ProcessDevelopment::findOrFail($id);
        
        // if ($processDevelopmentData) {

        //     $processDevelopmentData->delete();

        //     return back();
        // }

        $department = Department::with('kpi_scores', 'process_development')
            ->where('id', $request->department_id)
            ->first();
        
        $processDevelopmentData = $department->process_development()
            ->where('id', $id)
            ->first();

        if (!empty($processDevelopmentData)) {
            $processDevelopmentData->delete();
        }

        $processDevelopmentList = $department->process_development()
            ->where('date', $request->date)
            ->where('department_id', $request->department_id)
            ->get();

        if (count($processDevelopmentList) == 0) {
            $department->kpi_scores()
                ->where('department_id', $request->department_id)
                ->where('date', $request->date)
                ->update(['pd_scores' => 0.0]);
        }

        return back();
        
    }
}
