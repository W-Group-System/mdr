<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\Attachments;
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

        $month = array(
            "01" =>  'January',
            "02" => 'February',
            "03" => 'March',
            "04" => 'April',
            "05" => 'May',
            "06" => 'June',
            "07" => 'July',
            "08" => 'August',
            "09" => 'September',
            "10" => 'October',
            "11" => 'November',
            "12" => 'December'
        );

        $currentMonth = date('m');
        
        return view('dept-head.mdr',
            array(
                'departmentalGoalsList' => $departmentalGoalsList,
                'months' => $month,
                'currentMonth' => $currentMonth
            )
        );
    }

    public function create() {

        return view('dept-head.mdr-list');
    }

    public function submitKpi(Request $request) {
        $departmentalGoalsCount = DepartmentalGoals::count();
        
        $attachmentList = DepartmentalGoals::has('attachments')
            ->get()
            ->count();
        
        if ($departmentalGoalsCount != $attachmentList) {
            
            return back()->with('kpiErrors', ['Please attach a file in every KPI.']);
        }
        else {
            $validator = Validator::make($request->all(), [
                // 'actual[]' => 'array',
                'remarks[]' => 'array',
                'grade[]' => 'array',
                // 'actual.*' => 'required',
                'remarks.*' => 'required',
                'grade.*' => 'required'
            ], [
                // 'actual.*' => 'The actual field is required.',
                'remarks.*' => 'The remarks field is required.',
                'grade.*' => 'The grades field is required.'
            ]);
    
            if ($validator->fails()) {

                return back()->with('kpiErrors', $validator->errors()->all());
            } else {
                $departmentalGoalsList = DepartmentalGoals::findMany($request->departmental_goals_id);
                
                $targetDate = 0;
                foreach($departmentalGoalsList as $dept) {
                    $targetDate = $dept->departments->target_date;
                }

                $year = date('Y');
                $month = $request->month;
                $day = $targetDate;

                $date = $year.'-'.$month.'-'.$day;

                // $actual = $request->input('actual');
                $remarks = $request->input('remarks');
                $grades = $request->input('grade');
                
                $departmentalGoalsList->each(function($item, $index) use($remarks, $grades, $date) {
                    $item->update([
                        // 'actual' => $actual[$index],
                        'remarks' => $remarks[$index],
                        'grade' => $grades[$index],
                        'date' => $date
                    ]);
                });

                // $this->computeKpi($grades, $targetDate);
                
                return back();
            }
        }
    }

    public function computeKpi($grades, $targetDate) {
        $grade = collect($grades);

        $kpiValue = $grade->map(function($item, $key) {
            $value = $item / 100.00;

            return $value;
        });

        $kpiScore = $grade->map(function($item, $key) {
            $grades =  $item / 100.00 * 0.5;
            
            return $grades;
        });

        $value = number_format($kpiValue->sum(), 2);
        $rating = 3.00;
        $score = number_format($kpiScore->sum(), 2);

        
        
    }
}
