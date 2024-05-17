<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\DeptHead\Innovation;
use App\DeptHead\InnovationAttachments;
use App\DeptHead\KpiScore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class InnovationController extends Controller
{
    public function add(Request $request) {
        $department = Department::with('kpi_scores')
            ->where('id', auth()->user()->department_id)
            ->first();

        $validator = Validator::make($request->all(), [
            'innovationProjects' => 'required',
            'projectSummary' => 'required',
            'jobOrWorkNum' => 'required',
            'startDate' => 'required',
            'targetDate' => 'required',
            'actualDate' => 'required',
            'file' => 'required|array|max:2048'
        ], 
        [
            'file.max' => 'The max size of a file upload is 2MB only.'
        ]
        );

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $checkStatus = Innovation::where('status_level', 1)
                ->where('department_id', $department->id)
                ->where('year', date('Y', strtotime($request->monthOf)))
                ->where('month', date('m', strtotime($request->monthOf)))
                ->get();

            if ($checkStatus->isNotEmpty()) {

                return back()->with('errors', ['Failed. Because your MDR has been approved.']);
            }
            else {
                if ($request->hasFile('file')) {
                    
                    $innovation = new Innovation;
                    $innovation->department_group_id = $request->department_group_id;
                    $innovation->department_id = $department->id;
                    $innovation->projects = $request->innovationProjects;
                    $innovation->project_summary = $request->projectSummary;
                    $innovation->work_order_number = $request->jobOrWorkNum;
                    $innovation->start_date = date('Y-m-d', strtotime($request->startDate));
                    $innovation->target_date = date('Y-m-d', strtotime($request->targetDate));
                    $innovation->actual_date = date('Y-m-d', strtotime($request->actualDate));
                    $innovation->year = date('Y', strtotime($request->monthOf));
                    $innovation->month = date('m', strtotime($request->monthOf));
                    $innovation->deadline = date('Y-m', strtotime("+1month")).'-'.$department->target_date;
                    $innovation->remarks = $request->remarks;
                    $innovation->save();
    
                    $file = $request->file('file');
    
                    foreach($file as $key => $attachment) {
                        $fileName = time() . '-' . $attachment->getClientOriginalName();
                        $attachment->move(public_path('file'),  $fileName);
    
                        $innovationAttachments = new InnovationAttachments;
                        $innovationAttachments->department_id = $department->id;
                        $innovationAttachments->department_group_id = $request->department_group_id;
                        $innovationAttachments->innovation_id = $innovation->id;
                        $innovationAttachments->filepath = public_path('file') . '/' .$fileName;
                        $innovationAttachments->filename = $fileName;
                        $innovationAttachments->year = $innovation->year;
                        $innovationAttachments->month = $innovation->month;
                        $innovationAttachments->deadline = date('Y-m', strtotime("+1month")).'-'.$department->target_date;
                        $innovationAttachments->save();
                    }
    
                    $department->kpi_scores()
                        ->where('department_id', $department->id)
                        ->where('year',  date('Y', strtotime($request->monthOf)))
                        ->where('month',  date('m', strtotime($request->monthOf)))
                        ->update(['innovation_scores' => 1.0]);
    
                    Alert::success('SUCCESS', 'Successfully Added.');
                    return back();
                }
                else {
                    return back()->with('errors', ['Please attach a file before you submit the form.']);
                }
            }
            
        }
    }

    public function delete(Request $request, $id) {
        $department = Department::with('kpi_scores')
            ->where('id', $request->department_id)
            ->first();
        
        // $innovationData = $department->innovation()
        //     ->where('id', $id)
        //     ->first();
        
        // if (!empty($innovationData)) {
        //     $innovationData->delete();
        // }

        $innovationData = Innovation::findOrFail($id);
        if ($innovationData) {
            $innovationData->delete();
        }

        $innovationList = $department->innovation()
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->where('department_id', $request->department_id)
            ->get();

        if (count($innovationList) == 0) {
            $department->kpi_scores()
                ->where('department_id', $request->department_id)
                ->where('year', $request->year)
                ->where('month', $request->month)
                ->update(['innovation_scores' => 0.0]);
        }

        Alert::success('SUCCESS', 'Successfully Deleted.');
        return back();
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'innovationProjects' => 'required',
            'projectSummary' => 'required',
            'jobOrWorkNum' => 'required',
            'startDate' => 'required',
            'targetDate' => 'required',
            'actualDate' => 'required',
            'file' => 'array|max:2048'
        ], 
        [
            'file.max' => 'The max size of a file upload is 2MB only.'
        ]
        );

        if ($validator->fails()) {
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $department = Department::where('id', auth()->user()->department_id)->first();
                
            $innovation = Innovation::findOrFail($id);
            if ($innovation) {
                $innovation->projects = $request->innovationProjects;
                $innovation->project_summary = $request->projectSummary;
                $innovation->work_order_number = $request->jobOrWorkNum;
                $innovation->start_date = date('Y-m-d', strtotime($request->startDate));
                $innovation->target_date = date('Y-m-d', strtotime($request->targetDate));
                $innovation->actual_date = date('Y-m-d', strtotime($request->actualDate));
                $innovation->remarks = $request->remarks;
                $innovation->save();
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                foreach($file as $key => $attachment) {
                    $fileName = time() . '-' . $attachment->getClientOriginalName();
                    $attachment->move(public_path('file'),  $fileName);

                    $innovationAttachments = new InnovationAttachments;
                    $innovationAttachments->department_id = $department->id;
                    $innovationAttachments->department_group_id = $request->department_group_id;
                    $innovationAttachments->innovation_id = $innovation->id;
                    $innovationAttachments->filepath = 'file/' .$fileName;
                    $innovationAttachments->filename = $fileName;
                    $innovationAttachments->year = $innovation->year;
                    $innovationAttachments->month = $innovation->month;
                    $innovationAttachments->deadline = date('Y-m', strtotime("+1month")).'-'.$department->target_date;
                    $innovationAttachments->save();
                }

            }
            Alert::success('SUCCESS', 'Successfully Updated.');

            return back();

        }
    }

    public function deleteAttachments(Request $request) {
        
        $fileData = InnovationAttachments::where('id', $request->file_id)->first();

        if (!empty($fileData)) {
            $fileData->delete();

            return array('message' => 'Successfully Deleted');
        }
    }
}
