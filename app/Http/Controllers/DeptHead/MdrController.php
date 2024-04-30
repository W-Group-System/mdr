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
        
        // $mdrs = [];

        return view('dept-head.mdr-list',
            array(
                
            )
        );
    }
    public function create() {

        $departmentalGoalsList = DepartmentGroup::with('departmentalGoals','innovations','businessPlans','ongoingInnovation')
            ->get();
        return view('dept-head.mdr',
            array(
                'departmentalGoalsList' => $departmentalGoalsList,
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
