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
        
        $departmentalGoalsList = DepartmentGroup::with('departmentalGoals')
            ->get();
        
        return view('dept-head.mdr',
            array(
                'departmentalGoalsList' => $departmentalGoalsList,
            )
        );
    }

    public function create() {

        return view('dept-head.mdr-list');
    }
}
