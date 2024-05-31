<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Approver\MdrSummary;
use App\DeptHead\Innovation;
use App\DeptHead\InnovationAttachments;
use App\DeptHead\KpiScore;
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
        $department = Department::with('kpi_scores', 'innovation', 'process_development')
            ->where('id', auth()->user()->department_id)
            ->first();

        $kpiScore = $department->kpi_scores()
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->where('department_id', $department->id)
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
                if ($request->hasFile('file')) {
                    if (empty($kpiScore)) {

                        Alert::error('ERROR', 'Please submit KPI first');
                        return back();
                    }

                    $innovation = new Innovation;
                    $innovation->department_group_id = $request->department_group_id;
                    $innovation->department_id = $department->id;
                    $innovation->projects = $request->innovationProjects;
                    $innovation->project_summary = $request->projectSummary;
                    $innovation->work_order_number = $request->jobOrWorkNum;
                    $innovation->start_date = date('Y-m-d', strtotime($request->startDate));
                    $innovation->target_date = date('Y-m-d', strtotime($request->targetDate));
                    $innovation->actual_date = date('Y-m-d', strtotime($request->actualDate));
                    $innovation->year = date('Y', strtotime($request->yearAndMonth));
                    $innovation->month = date('m', strtotime($request->yearAndMonth));
                    $innovation->deadline = date('Y-m', strtotime("+1month", strtotime($request->yearAndMonth))).'-'.$department->target_date;
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
                        $innovationAttachments->filepath = 'file/' . $fileName;
                        $innovationAttachments->filename = $fileName;
                        $innovationAttachments->year = $innovation->year;
                        $innovationAttachments->month = $innovation->month;
                        $innovationAttachments->deadline = date('Y-m', strtotime("+1month")).'-'.$department->target_date;
                        $innovationAttachments->save();
                    }

                    $innovationCount = $department->innovation()
                        ->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->where('month', date('m', strtotime($request->yearAndMonth)))
                        ->where('department_id',  $department->id)
                        ->count();

                    $processDevelopmentCount = $department->process_development()
                        ->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->where('month', date('m', strtotime($request->yearAndMonth)))
                        ->where('department_id',  $department->id)
                        ->count();
                    
                    if ($innovationCount > 0 && $processDevelopmentCount > 0) {
                        $kpiScore->update([
                            'pd_scores' => 1.0,
                            'innovation_scores' => 1.0
                        ]);
                    }
                    else if ($innovationCount > 0 || $processDevelopmentCount == 0) {
                        $kpiScore->update([
                            'pd_scores' => 0.5,
                            'innovation_scores' => 0.5
                        ]);
                    }
                    else if ($innovationCount == 0 || $processDevelopmentCount > 0) {
                        $kpiScore->update([
                            'pd_scores' => 0.5,
                            'innovation_scores' => 0.5
                        ]);
                    }

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
        $innovationData = Innovation::findOrFail($id);

        if ($innovationData) {
            $innovationData->delete();
        }

        $department = Department::withCount([
            'innovation' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', '05');
            },
            'process_development' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', '05');
            },
            'kpi_scores'
        ])
        ->where('id', auth()->user()->department_id)
        ->first();

        $kpiScore = $department->kpi_scores()
            ->where('year', date('Y', strtotime($request->yearAndMonth)))
            ->where('month', date('m', strtotime($request->yearAndMonth)))
            ->where('department_id', $department->id)
            ->first();

        if ($department->innovation_count == 0 && $department->process_development_count == 0) {
            $kpiScore->update([
                'pd_scores' => 0.0,
                'innovation_scores' => 0.0
            ]);
        }
        else if ($department->innovation_count > 0 && $department->process_development_count == 0) {
            $kpiScore->update([
                'pd_scores' => 0.5,
                'innovation_scores' => 0.5
            ]);
        } else if ($department->innovation_count == 0 && $department->process_development_count > 0) {
            $kpiScore->update([
                'pd_scores' => 0.5,
                'innovation_scores' => 0.5
            ]);
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
                    $innovationAttachments->deadline = $innovation->deadline;
                    $innovationAttachments->save();
                }

            }
            Alert::success('SUCCESS', 'Successfully Updated.');

            return back();

        }
    }

    public function deleteAttachments(Request $request) {
        
        $fileData = InnovationAttachments::findOrFail($request->file_id);
        
        if (!empty($fileData)) {
            $fileData->delete();

            return array('message' => 'Successfully Deleted');
        }
    }
}
