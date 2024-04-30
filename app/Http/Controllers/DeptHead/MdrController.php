<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\OnGoingInnovation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MdrController extends Controller
{
    public function index() {
        
        $departmentalGoalsCount = $this->departmentalGoalsCount();

        $innovationCount = $this->innovationCount();

        $businessPlanCount = $this->businessPlanCount();

        $ongoingInnovationCount = $this->ongoingInnovationCount();

        $departmentalGoalsList = DepartmentGroup::with('departmentalGoals')
            ->has('departmentalGoals')
            ->get();

        $innovationList = DepartmentGroup::with('innovations')
            ->has('innovations')
            ->get();

        $businessPlanList = DepartmentGroup::with('businessPlans')
            ->has('businessPlans')
            ->get();

        $ongoingInnovationList = DepartmentGroup::with('ongoingInnovation')
            ->has('ongoingInnovation')
            ->get();

        return view('dept-head.mdr',
            array(
                'departmentalGoalsList' => $departmentalGoalsList,
                'innovationList' => $innovationList,
                'businessPlanList' => $businessPlanList,
                'ongoingInnovationList' => $ongoingInnovationList,

                'departmentalGoalsCount' => $departmentalGoalsCount,
                'innovationCount' => $innovationCount,
                'businessPlanCount' => $businessPlanCount,
                'ongoingInnovationCount' => $ongoingInnovationCount
            )
        );
    }

    public function departmentalGoalsCount() {
        $yearAndMonth = date('Y-m');

        $departmentalGoalsCount = DepartmentalGoals::where('department_group_id', 1)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
            ->count();

        return $departmentalGoalsCount;
    }

    public function innovationCount() {
        $yearAndMonth = date('Y-m');

        $innovationCount = Innovation::where('department_group_id', 5)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
            ->count();

        return $innovationCount;
    }

    public function businessPlanCount() {
        $yearAndMonth = date('Y-m');

        $businessPlanCount = BusinessPlan::where('department_group_id', 6)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
            ->count();

        return $businessPlanCount;
    }

    public function ongoingInnovationCount() {
        $yearAndMonth = date('Y-m');

        $ongoingInnovationCount = OnGoingInnovation::where('department_group_id', 7)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
            ->get();

        return $ongoingInnovationCount;
    }
}
