<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentGroup;
use App\DeptHead\Innovation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InnovationController extends Controller
{
    public function add(Request $request) {
        $departmentKpiGroupData = DepartmentGroup::select('id', 'name')
            ->where('id', 5)
            ->first();

        $validator = Validator::make($request->all(), [
            'innovationProjects' => 'required',
            'projectSummary' => 'required',
            'jobOrWorkNum' => 'required',
            'startDate' => 'required',
            'targetDate' => 'required',
            'actualDate' => 'required'
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $innovation = new Innovation;
            $innovation->department_group_id = $departmentKpiGroupData->id;
            $innovation->department_id = auth()->user()->department_id;
            $innovation->projects = $request->innovationProjects;
            $innovation->project_summary = $request->projectSummary;
            $innovation->work_order_number = $request->jobOrWorkNum;
            $innovation->start_date = date('Y-m-d', strtotime($request->startDate));
            $innovation->target_date = date('Y-m-d', strtotime($request->targetDate));
            $innovation->actual_date = date('Y-m-d', strtotime($request->actualDate));
            $innovation->date = date('Y-m-d');
            $innovation->save();
            
            return back();
        }
    }

    public function delete($id) {
        $innovationData = Innovation::findOrFail($id);

        if ($innovationData) {
            $innovationData->delete();
        }

        return back();
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'innovationProjects' => 'required',
            'projectSummary' => 'required',
            'jobOrWorkNum' => 'required',
            'startDate' => 'required',
            'targetDate' => 'required',
            'actualDate' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $innovation = Innovation::findOrFail($id);
            if ($innovation) {
                $innovation->projects = $request->innovationProjects;
                $innovation->project_summary = $request->projectSummary;
                $innovation->work_order_number = $request->jobOrWorkNum;
                $innovation->start_date = date('Y-m-d', strtotime($request->startDate));
                $innovation->target_date = date('Y-m-d', strtotime($request->targetDate));
                $innovation->actual_date = date('Y-m-d', strtotime($request->actualDate));
                $innovation->save();
            }
            
            return back();
        }
    }
}
