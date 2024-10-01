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
        $processDevelopment = new ProcessDevelopment;
        $processDevelopment->department_id = auth()->user()->department_id;
        $processDevelopment->mdr_group_id = 8;
        // $processDevelopment->description = $request->description;
        // $processDevelopment->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
        // $processDevelopment->remarks = $request->remarks;
        $processDevelopment->yearAndMonth = date('Y-m', strtotime($request->yearAndMonth));
        $processDevelopment->deadline = date('Y-m', strtotime('+1month', strtotime($request->yearAndMonth))).'-'.auth()->user()->department->target_date;
        $processDevelopment->activities = $request->activities;
        $processDevelopment->remarks = $request->benefits;
        $processDevelopment->dicr_number = $request->dicr_number;
        $processDevelopment->accomplished_date = $request->date_approved;
        $processDevelopment->save();

        $file = $request->file('file');
        foreach($file as $attachment) {
            $fileName = time() . '-' . $attachment->getClientOriginalName();
            $attachment->move(public_path('process_improvement_files'),  $fileName);

            $pdAttachments = new ProcessDevelopmentAttachments;
            $pdAttachments->process_development_id = $processDevelopment->id;
            $pdAttachments->filepath = '/process_improvement_files/' . $fileName;
            // $pdAttachments->filename = $fileName;
            $pdAttachments->save();  
        }

        processImprovementComputations("add", $processDevelopment->department_id, $request->yearAndMonth);

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request, $id) {
        // dd($request->all());
        $processDevelopmentData = ProcessDevelopment::findOrFail($id);
        $processDevelopmentData->activities = $request->activities;
        $processDevelopmentData->remarks = $request->benefits;
        $processDevelopmentData->dicr_number = $request->dicr_number;
        $processDevelopmentData->accomplished_date = $request->date_approved;
        $processDevelopmentData->save();

        if($request->has('file')) {
            $file = $request->file('file');

            $processDevelopmentAttachment = ProcessDevelopmentAttachments::where('process_development_id', $id)->delete();
            foreach($file as $attachment) {
                $fileName = time() . '_' . $attachment->getClientOriginalName();
                $attachment->move(public_path('process_improvement_files'),  $fileName);

                $processDevelopmentAttachment = new ProcessDevelopmentAttachments;
                $processDevelopmentAttachment->process_development_id = $processDevelopmentData->id;
                $processDevelopmentAttachment->filepath = '/process_improvement_files/' . $fileName;
                $processDevelopmentAttachment->save();
            }
        }

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function delete(Request $request, $id) {
        $processDevelopmentData = ProcessDevelopment::findOrFail($id);
        $processDevelopmentData->delete();
        
        // $department = Department::withCount([
        //     'process_development' => function($q)use($request) {
        //         $q->where('year', date('Y', strtotime($request->yearAndMonth)))
        //             ->where('month', date('m', strtotime($request->yearAndMonth)));
        //     },
        // ])
        // ->with([
        //     'kpi_scores' => function($q)use($request) {
        //         $q->where('year', date('Y', strtotime($request->yearAndMonth)))
        //             ->where('month', date('m', strtotime($request->yearAndMonth)))
        //             ->where('department_id', auth()->user()->department_id);
        //     }
        // ])
        // ->where('id', auth()->user()->department_id)
        // ->first();

        // $processImprovementCount = $department->process_development_count;
        // $mdrScore = $department->kpi_scores->first();
        
        // $mdrScoreData = MdrScore::findOrFail($mdrScore->id);
        // if ($processImprovementCount == 1) {
        //     $mdrScoreData->pd_scores = 0.5;
        //     $mdrScoreData->save();
        // }
        // if($processImprovementCount == 0) {
        //     $mdrScoreData->pd_scores = 0.0;
        //     $mdrScoreData->save();
        // }

        processImprovementComputations("delete", $request->department, $request->yearAndMonth);

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
        
    }

    // public function deletePdAttachments(Request $request) {
    //     $attachments = ProcessDevelopmentAttachments::findOrFail($request->file_id);

    //     if ($attachments) {
    //         $attachments->delete();

    //         return array('message' => 'Successfully Deleted.');
    //     }
    // }
}
