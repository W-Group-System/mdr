<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentGroup;
use App\DeptHead\OnGoingInnovation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OnGoingInnovationController extends Controller
{
    public function add(Request $request) {
        $departmentKpiGroupData = DepartmentGroup::select('id', 'name')
            ->where('id', 7)
            ->first();

        $validator = Validator::make($request->all(), [
            'innovationProjects' => 'required',
            'currentStatus' => 'required',
            'jobWorkNumber' => 'required',
            'startDate' => 'required',
            'targetDate' => 'required'
        ]);

        if ($validator->fails()) {
            
            return back()->with('ongoingInnovationErrors', $validator->errors()->all());
        }
        else {
            $ongoingInnovation = new OnGoingInnovation;
            $ongoingInnovation->department_id = auth()->user()->department_id;
            $ongoingInnovation->mdr_group_id = $departmentKpiGroupData->id;
            $ongoingInnovation->innovation_projects = $request->innovationProjects;
            $ongoingInnovation->current_status = $request->currentStatus;
            $ongoingInnovation->work_number = $request->jobWorkNumber;
            $ongoingInnovation->start_date = date('Y-m-d', strtotime($request->startDate));
            $ongoingInnovation->target_date = date('Y-m-d', strtotime($request->targetDate));
            $ongoingInnovation->date = date('Y-m-d');
            $ongoingInnovation->save();

            return back();
        }
    }

    public function update(Request $request, $id) {
        $ongoingInnovationData = OnGoingInnovation::findOrFail($id);

        if ($ongoingInnovationData) {
            $ongoingInnovationData->innovation_projects = $request->innovationProjects;
            $ongoingInnovationData->current_status = $request->currentStatus;
            $ongoingInnovationData->work_number = $request->jobWorkNumber;
            $ongoingInnovationData->start_date = date('Y-m-d', strtotime($request->startDate));
            $ongoingInnovationData->target_date = date('Y-m-d', strtotime($request->targetDate));
            $ongoingInnovationData->save();

            return back();
        }
    }

    public function delete($id) {
        $ongoingInnovationData = OnGoingInnovation::findOrFail($id);

        if ($ongoingInnovationData) {
            $ongoingInnovationData->delete();

            return back();
        }
    }
}
