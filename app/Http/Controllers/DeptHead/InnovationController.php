<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\MdrGroup;
use App\Approver\MdrSummary;
use App\DeptHead\Innovation;
use App\DeptHead\InnovationAttachments;
use App\DeptHead\MdrScore;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class InnovationController extends Controller
{
    public function add(Request $request) {
        $innovation = new Innovation;
        $innovation->department_id = auth()->user()->department_id;
        $innovation->mdr_group_id = 5;
        $innovation->projects = $request->innovationProjects;
        $innovation->project_summary = $request->projectSummary;
        $innovation->work_order_number = $request->jobOrWorkNum;
        $innovation->start_date = date('Y-m-d', strtotime($request->startDate));
        $innovation->target_date = date('Y-m-d', strtotime($request->targetDate));
        $innovation->actual_date = date('Y-m-d', strtotime($request->actualDate));
        $innovation->yearAndMonth = $request->yearAndMonth;
        $innovation->deadline = date('Y-m', strtotime("+1 month", strtotime($request->yearAndMonth))).'-'.auth()->user()->department->target_date;
        $innovation->remarks = $request->remarks;
        $innovation->save();

        $file = $request->file('file');

        foreach($file as $key => $attachment) {
            $fileName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path('innovation_attachments'),  $fileName);

            $innovationAttachments = new InnovationAttachments;
            $innovationAttachments->department_id = auth()->user()->department_id;
            $innovationAttachments->innovation_id = $innovation->id;
            $innovationAttachments->filepath = '/innovation_attachments/' . $fileName;
            $innovationAttachments->save();
        }

        // $innovationCount = Innovation::where('yearAndMonth', date('Y-m', strtotime($request->yearAndMonth)))
        //     ->where('department_id',  auth()->user()->department_id)
        //     ->count();
        
        // $mdrScore =

        // if ($innovationCount == 1) {
        //     $kpiScore->update([
        //         'innovation_scores' => 0.5,
        //     ]);
        // }
        // else {
        //     $kpiScore->update([
        //         'innovation_scores' => 1.0,
        //     ]);
        // }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function delete(Request $request, $id) {
        $innovationData = Innovation::findOrFail($id);
        $innovationData->delete();

        // $department = Department::withCount([
        //     'innovation' => function($q)use($request) {
        //         $q->where('year', date('Y', strtotime($request->yearAndMonth)))
        //             ->where('month', date('m', strtotime($request->yearAndMonth)));
        //     },
        //     'kpi_scores'
        // ])
        // ->where('id', auth()->user()->department_id)
        // ->first();

        // $kpiScore = $department->kpi_scores()
        //     ->where('year', date('Y', strtotime($request->yearAndMonth)))
        //     ->where('month', date('m', strtotime($request->yearAndMonth)))
        //     ->where('department_id', $department->id)
        //     ->first();

        // if ($department->innovation_count == 1) {
        //     $kpiScore->update([
        //         'innovation_scores' => 0.5
        //     ]);
        // }
        // else if ($department->innovation_count == 0) {
        //     $kpiScore->update([
        //         'innovation_scores' => 0.0
        //     ]);
        // }

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

        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request, $id) {
        $department = Department::where('id', auth()->user()->department_id)->first();
                
        $innovation = Innovation::findOrFail($id);
        $innovation->projects = $request->innovationProjects;
        $innovation->project_summary = $request->projectSummary;
        $innovation->work_order_number = $request->jobOrWorkNum;
        $innovation->start_date = date('Y-m-d', strtotime($request->startDate));
        $innovation->target_date = date('Y-m-d', strtotime($request->targetDate));
        $innovation->actual_date = date('Y-m-d', strtotime($request->actualDate));
        $innovation->remarks = $request->remarks;
        $innovation->save();

        if ($request->has('file')) {

            $innovationAttachments = InnovationAttachments::where('innovation_id', $id)->delete();

            $file = $request->file('file');
            foreach($file as $key => $attachment) {
                $fileName = time() . '_' . $attachment->getClientOriginalName();
                $attachment->move(public_path('innovation_attachments'),  $fileName);

                $innovationAttachments = new InnovationAttachments;
                $innovationAttachments->department_id = $department->id;
                $innovationAttachments->mdr_group_id = $request->mdr_group_id;
                $innovationAttachments->innovation_id = $innovation->id;
                $innovationAttachments->filepath = '/innovation_attachments/' .$fileName;
                $innovationAttachments->filename = $fileName;
                $innovationAttachments->year = $innovation->year;
                $innovationAttachments->month = $innovation->month;
                $innovationAttachments->deadline = $innovation->deadline;
                $innovationAttachments->save();
            }
        }

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    // public function deleteAttachments($id) {
    //     $fileData = InnovationAttachments::findOrFail($id);
    //     $fileData->delete();

    //     Alert::success('Successfully Deleted')->persistent('Dismiss');
    //     return back();
    // }
}
