<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Department;
use App\Admin\MdrGroup;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\MdrScore;
use App\DeptHead\ProcessDevelopment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function PHPSTORM_META\map;

class HistoryMdrController extends Controller
{
    public function index(Request $request) {
        $department_list = Department::get();
        $departmental_goals = DepartmentalGoals::where('department_id', $request->department)->where('yearAndMonth', $request->yearAndMonth)->get();
        $innovation = Innovation::where('department_id', $request->department)->where('yearAndMonth', $request->yearAndMonth)->get();
        $process_improvement = ProcessDevelopment::where('department_id', $request->department)->where('yearAndMonth', $request->yearAndMonth)->get();
        $mdr_score = MdrScore::where('department_id', $request->department)->where('yearAndMonth', $request->yearAndMonth)->get();

        return view('approver.history-mdr',
            array(
                'departmental_goals' => $departmental_goals,
                'innovation' => $innovation,
                'process_improvement' => $process_improvement,
                'department_list' => $department_list,
                'year_and_month' => $request->yearAndMonth,
                'mdr_score' => $mdr_score
            )
        );
    }
}
