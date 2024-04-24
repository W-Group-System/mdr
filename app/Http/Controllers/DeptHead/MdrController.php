<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\DepartmentKPI;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MdrController extends Controller
{
    public function index() {

        $month = date('m');

        $departmentalGoalsList = DepartmentalGoals::where('department_group_id', 1)
            ->where('department_id', auth()->user()->department_id)
            ->where('date', 'LIKE', '%'.$month.'%')
            ->get();

        $innovationList = Innovation::where('department_group_id', 5)
            ->where('department_id', auth()->user()->department_id)
            ->where('date', 'LIKE', '%'.$month.'%')
            ->get();
        
        return view('dept-head.mdr',
            array(
                'departmentalGoalsList' => $departmentalGoalsList,
                'innovationList' => $innovationList
            )
        );
    }

}
