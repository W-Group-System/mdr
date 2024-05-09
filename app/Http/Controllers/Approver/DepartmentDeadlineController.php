<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DepartmentDeadlineController extends Controller
{
    public function index() {

        $departmentList = Department::get();

        return view('approver.department-deadline',
            array(
                'departmentList' => $departmentList
            )
        );
    }
    
    // public function edit(Request $request) {
    //     $date = $request->yearAndMonth.'-'.$request->targetDate;
        
    //     $departmentData = Department::with('departmentalGoals', 'process_development', 'kpi_scores')
    //         ->where('id', $request->department_id)
    //         ->get();
        
    //     foreach($departmentData as $department) {

    //         $departmentalGoalsList = $department->departmentalGoals()
    //             ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->yearAndMonth)
    //             ->get();
            
    //         $processDevelopmentList = $department->process_development()
    //             ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->yearAndMonth)
    //             ->get();

    //         $kpiScore = $department->kpi_scores()
    //             ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->yearAndMonth)
    //             ->get();

    //         if (!empty($departmentalGoalsList) && !empty($processDevelopmentList) && !empty($kpiScore)) {
    //             $departmentalGoalsList->each(function($item, $key)use($date) {
    //                 $item->update([
    //                     'date' => $date
    //                 ]);
    //             });

    //             $processDevelopmentList->each(function($item, $key)use($date) {
    //                 $item->update([
    //                     'date' => $date
    //                 ]);
    //             });

    //             $kpiScore->each(function($item, $key)use($date) {
    //                 $item->update([
    //                     'date' => $date
    //                 ]);
    //             });

    //             return back()->with('approve', 'The MDR is successfully approved');
    //         }
    //         else {
    //             return back();
    //         }
            
    //     }
    // }
}
