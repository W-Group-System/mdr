<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Approver\MdrSummary;
use App\DeptHead\Innovation;
use App\DeptHead\MdrScore;
use App\DeptHead\ProcessDevelopment;
use App\DeptHead\ProcessDevelopmentAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\Process\Process;

class ProcessDevelopmentController extends Controller
{
    public function add(Request $request) {
        $departmentData = Department::with('kpi_scores', 'process_development')
            ->where('id',  auth()->user()->department_id)
            ->first();

        $kpiScore = $departmentData->kpi_scores()
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->where('department_id', $departmentData->id)
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
            $checkStatus = MdrSummary::where('year', date('Y', strtotime($request->yearAndMonth)))
                ->where('month', date('m', strtotime($request->yearAndMonth)))
                ->where('department_id', auth()->user()->department_id)
                ->where('status_level', "<>", 0)
                ->first();

            if (!empty($checkStatus)) {

                Alert::error('ERROR', 'Failed. Because your MDR has been approved.');
                return back();
            }
            else {
                if($request->hasFile('file')) {
                    if(empty($kpiScore)) {

                        Alert::error('ERROR', 'Please submit KPI first');
                        return back();
                    }

                    $processDevelopment = new ProcessDevelopment;
                    $processDevelopment->department_id = $departmentData->id;
                    $processDevelopment->mdr_group_id = $request->dptGroup;
                    $processDevelopment->description = $request->description;
                    $processDevelopment->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
                    $processDevelopment->status_level = 0;
                    $processDevelopment->year = date('Y', strtotime($request->yearAndMonth));
                    $processDevelopment->month = date('m', strtotime($request->yearAndMonth));
                    $processDevelopment->deadline = date('Y-m', strtotime('+1month', strtotime($request->yearAndMonth))).'-'.$departmentData->target_date;
                    $processDevelopment->remarks = $request->remarks;
                    $processDevelopment->save();
    
                    $file = $request->file('file');
                    foreach($file as $attachment) {
                        $fileName = time() . '-' . $attachment->getClientOriginalName();
                        $attachment->move(public_path('file'),  $fileName);

                        $pdAttachments = new ProcessDevelopmentAttachments;
                        $pdAttachments->pd_id = $processDevelopment->id;
                        $pdAttachments->filepath = 'file/' . $fileName;
                        $pdAttachments->filename = $fileName;
                        $pdAttachments->save();  
                    }
                    
                    $processDevelopmentCount = $departmentData->process_development()
                        ->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->where('month', date('m', strtotime($request->yearAndMonth)))
                        ->where('department_id',  $departmentData->id)
                        ->count();

                    // if ($innovationCount > 0 && $processDevelopmentCount > 0) {
                    //     $kpiScore->update([
                    //         'pd_scores' => 1.0,
                    //         // 'innovation_scores' => 1.0
                    //     ]);
                    // }
                    // else if ($innovationCount == 0 || $processDevelopmentCount > 0) {
                    //     $kpiScore->update([
                    //         'pd_scores' => 0.5,
                    //         // 'innovation_scores' => 0.5
                    //     ]);
                    // }
                    // else if ($innovationCount > 0 || $processDevelopmentCount == 0) {
                    //     $kpiScore->update([
                    //         'pd_scores' => 0.5,
                    //         // 'innovation_scores' => 0.5
                    //     ]);
                    // }

                    if ($processDevelopmentCount == 1) {
                        $kpiScore->update([
                            'pd_scores' => 0.5,
                        ]);
                    }
                    else {
                        $kpiScore->update([
                            'pd_scores' => 1.0,
                        ]);
                    }

                    Alert::success('SUCCESS', 'Successfully Added.');
                    return back();
                }
                else {
                    
                    Alert::error('ERROR', 'You are not selecting a file.');
                    return back();
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
                foreach($file as $attachment) {
                    $fileName = time() . '-' . $attachment->getClientOriginalName();
                    $attachment->move(public_path('file'),  $fileName);
    
                    $processDevelopmentAttachment = new ProcessDevelopmentAttachments;
                    $processDevelopmentAttachment->pd_id = $request->pd_id;
                    $processDevelopmentAttachment->filepath = 'file/' . $fileName;
                    $processDevelopmentAttachment->filename = $fileName;
                    $processDevelopmentAttachment->save();
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
        $processDevelopmentData = ProcessDevelopment::findOrFail($id);

        if ($processDevelopmentData) {
            $processDevelopmentData->delete();
        }
        
        $department = Department::withCount([
            'process_development' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)));
            },
            'kpi_scores'
        ])
        ->where('id', auth()->user()->department_id)
        ->first();

        $kpiScore = $department->kpi_scores()
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->where('department_id', auth()->user()->department_id)
            ->first();
        
        if ($department->process_development_count == 1) {
            $kpiScore->update([
                'pd_scores' => 0.5
            ]);
        }
        else if ($department->process_development_count == 0) {
            $kpiScore->update([
                'pd_scores' => 0.0
            ]);
        }

        // if ($department->innovation_count == 0 && $department->process_development_count == 0) {
        //     $kpiScore->update([
        //         'pd_scores' => 0.0,
        //         'innovation_scores' => 0.0
        //     ]);
        // }
        // else if ($department->innovation_count > 0 && $department->process_development_count == 0) {
        //     $kpiScore->update([
        //         'pd_scores' => 0.5,
        //         'innovation_scores' => 0.5
        //     ]);
        // } else if ($department->innovation_count == 0 && $department->process_development_count > 0) {
        //     $kpiScore->update([
        //         'pd_scores' => 0.5,
        //         'innovation_scores' => 0.5
        //     ]);
        // }

        Alert::success('SUCCESS', 'Successfully Deleted.');
        return back();
        
    }

    public function deletePdAttachments(Request $request) {
        $attachments = ProcessDevelopmentAttachments::findOrFail($request->file_id);

        if ($attachments) {
            $attachments->delete();

            return array('message' => 'Successfully Deleted.');
        }
    }
}
