<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Approver\MdrSummary;
use App\DeptHead\KpiScore;
use App\DeptHead\ProcessDevelopment;
use App\DeptHead\ProcessDevelopmentAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

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
            // $checkStatus = ProcessDevelopment::where('status_level', 1)
            //     ->where('year', date('Y', strtotime($request->monthOf)))
            //     ->where('month', date('m', strtotime($request->monthOf)))
            //     ->where('department_id', auth()->user()->department_id)
            //     ->get();

            $checkStatus = MdrSummary::where('year', date('Y', strtotime($request->monthOf)))
                ->where('month', date('m', strtotime($request->monthOf)))
                ->where('department_id', auth()->user()->department_id)
                ->where('status_level', "<>", 0)
                ->first();

            if (!empty($checkStatus)) {

                Alert::error('ERROR', 'Failed. Because your MDR has been approved.');
                return back();
            }
            else {
                if($request->hasFile('file')) {
                    $processDevelopment = new ProcessDevelopment;
                    $processDevelopment->department_id = $departmentData->id;
                    $processDevelopment->department_group_id = $request->pd_id;
                    $processDevelopment->description = $request->description;
                    $processDevelopment->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
                    $processDevelopment->status_level = 0;
                    $processDevelopment->year = date('Y', strtotime($request->monthOf));
                    $processDevelopment->month = date('m', strtotime($request->monthOf));
                    $processDevelopment->deadline = date('Y-m', strtotime('+1month')).'-'.$departmentData->target_date;
                    $processDevelopment->remarks = $request->remarks;
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
                        ->where('year', date('Y', strtotime($request->monthOf)))
                        ->where('month', date('m', strtotime($request->monthOf)))
                        ->update(['pd_scores' => 0.5]);

                    Alert::success('SUCCESS', 'Successfully Added.');
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
                    $processDevelopmentData->remarks = $request->remarks;
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

                Alert::success('SUCCESS', 'Successfully Updated.');
                return back();
            }
            else {
                $processDevelopmentData = ProcessDevelopment::findOrFail($id);
                
                if ($processDevelopmentData) {
                    $processDevelopmentData->description = $request->description;
                    $processDevelopmentData->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
                    $processDevelopmentData->remarks = $request->remarks;
                    $processDevelopmentData->save();
                }

                Alert::success('SUCCESS', 'Successfully Updated.');
                return back();
            }
        }   
    }

    public function delete(Request $request, $id) {
        $department = Department::with('kpi_scores', 'process_development')
            ->where('id', $request->department_id)
            ->first();
        
        $processDevelopmentData = ProcessDevelopment::findOrFail($id);

        if ($processDevelopmentData) {
            $processDevelopmentData->delete();
        }

        $processDevelopmentList = $department->process_development()
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('department_id', $request->department_id)
            ->get();

        if (count($processDevelopmentList) == 0) {
            $department->kpi_scores()
                ->where('department_id', $request->department_id)
                ->where('year', $request->year)
                ->where('month', $request->month)
                ->update(['pd_scores' => 0.0]);
        }

        Alert::success('SUCCESS', 'Successfully Deleted.');
        return back();
        
    }
}
