<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\MdrGroup;
use App\DeptHead\BusinessPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BusinessPlanController extends Controller
{
    public function add(Request $request) {
        $departmentGroupKpi = MdrGroup::where('id', 6)->first();
        
        $validator = Validator::make($request->all(), [
            'activities' => 'required',
            'baseOnPlanned' => 'required',
            'proofOfCompletion' => 'required',
            'startDate' => 'required',
            'actualDate' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('bpErrors', $validator->errors()->all());
        }
        else {
            $businessPlan = new BusinessPlan;
            $businessPlan->department_id = auth()->user()->department_id;
            $businessPlan->mdr_group_id = $departmentGroupKpi->id;
            $businessPlan->activities = $request->activities;
            $businessPlan->isBasedOnPlanned = $request->baseOnPlanned;
            $businessPlan->proof_of_completion = $request->proofOfCompletion;
            $businessPlan->start_date = date('Y-m-d', strtotime($request->startDate));
            $businessPlan->end_date = date('Y-m-d', strtotime($request->actualDate));
            $businessPlan->date = date('Y-m-d');
            $businessPlan->save();

            return back();
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'activities' => 'required',
            'baseOnPlanned' => 'required',
            'proofOfCompletion' => 'required',
            'startDate' => 'required',
            'actualDate' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->with('bpErrors', $validator->errors()->all());
        }
        else {
            $businessPlan = BusinessPlan::findOrFail($id);
            if ($businessPlan) {
                $businessPlan->activities = $request->activities;
                $businessPlan->isBasedOnPlanned = $request->baseOnPlanned;
                $businessPlan->proof_of_completion = $request->proofOfCompletion;
                $businessPlan->start_date = date('Y-m-d', strtotime($request->startDate));
                $businessPlan->end_date = date('Y-m-d', strtotime($request->actualDate));
                $businessPlan->date = date('Y-m-d');
                $businessPlan->save();
            }

            return back();
        }
    }

    public function delete($id) {
        $businessPlanData = BusinessPlan::findOrFail($id);

        if ($businessPlanData) {
            $businessPlanData->delete();

            return back();
        }

    }
}
