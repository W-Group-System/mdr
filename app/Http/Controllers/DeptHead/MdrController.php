<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentKPI;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MdrController extends Controller
{
    public function index() {

        $yearAndMonth = date('Y-m');

        $departmentalGoalsList = DepartmentalGoals::where('department_group_id', 1)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
            ->get();

        $innovationList = Innovation::where('department_group_id', 5)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
            ->get();

        $businessPlanList = BusinessPlan::where('department_group_id', 6)
            ->where('department_id', auth()->user()->department_id)
            ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
            ->get();
        
        return view('dept-head.mdr',
            array(
                'departmentalGoalsList' => $departmentalGoalsList,
                'innovationList' => $innovationList,
                'businessPlanList' => $businessPlanList
            )
        );
    }

}
