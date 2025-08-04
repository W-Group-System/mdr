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
        // dd($request->all());
        $innovation = new Innovation;
        $innovation->department_id = auth()->user()->department_id;
        $innovation->project_charter = $request->project_charter;
        $innovation->project_benefit = $request->project_benefit;
        $innovation->year = date('Y', strtotime($request->yearAndMonth));
        $innovation->month = date('m', strtotime($request->yearAndMonth));
        $innovation->deadline = generateSafeDeadline($request->yearAndMonth, auth()->user()->department->target_date);
        $innovation->save();

        $accomplishment_reports = $request->file('accomplishment_report');
        foreach($accomplishment_reports as $key => $accomplishment_report) {
            $fileName = time() . '_' . $accomplishment_report->getClientOriginalName();
            $accomplishment_report->move(public_path('innovation_attachments'),  $fileName);

            $innovationAttachments = new InnovationAttachments;
            $innovationAttachments->innovation_id = $innovation->id;
            $innovationAttachments->filepath = '/innovation_attachments/' . $fileName;
            $innovationAttachments->save();
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function delete(Request $request, $id) {
        // $innovationData = Innovation::findOrFail($id);
        // $innovationData->delete();

        // Alert::success('Successfully Deleted')->persistent('Dismiss');
        // return back();
    }

    public function update(Request $request, $id) {
        $innovation = Innovation::findOrFail($id);
        $innovation->department_id = auth()->user()->department_id;
        $innovation->project_charter = $request->project_charter;
        $innovation->project_benefit = $request->project_benefit;
        $innovation->year = date('Y', strtotime($request->yearAndMonth));
        $innovation->month = date('m', strtotime($request->yearAndMonth));
        $innovation->deadline = date('Y-m', strtotime("+1 month", strtotime($request->yearAndMonth))).'-'.auth()->user()->department->target_date;
        $innovation->save();

        if ($request->has('accomplishment_report'))
        {
            InnovationAttachments::where('innovation_id', $id)->delete();
            $accomplishment_reports = $request->file('accomplishment_report');
            foreach($accomplishment_reports as $key => $accomplishment_report) {
                $fileName = time() . '_' . $accomplishment_report->getClientOriginalName();
                $accomplishment_report->move(public_path('innovation_attachments'),  $fileName);
    
                $innovationAttachments = new InnovationAttachments;
                $innovationAttachments->innovation_id = $innovation->id;
                $innovationAttachments->filepath = '/innovation_attachments/' . $fileName;
                $innovationAttachments->save();
            }
        }

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }
}
