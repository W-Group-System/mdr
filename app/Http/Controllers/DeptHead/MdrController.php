<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\Attachments;
use App\DeptHead\BusinessPlan;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\Innovation;
use App\DeptHead\KpiScore;
use App\DeptHead\OnGoingInnovation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MdrController extends Controller
{
    public function index() {
        
        $departmentKpi = DepartmentGroup::with('departmentKpi', 'processDevelopment')
            ->get();

        return view('dept-head.mdr',
            array(
                'departmentKpi' => $departmentKpi,
            )
        );
    }

    public function create() {

        $mdrScoreList = Department::with('kpi_scores')
            ->where('id', auth()->user()->department_id)
            ->get();
            

        return view('dept-head.mdr-list', 
            array(
                'mdrScoreList' => $mdrScoreList
            )
        );
    }

    public function submitKpi(Request $request) {

        $checkIfHaveAttachments = DepartmentKPI::with('attachments')
            ->where('department_id', auth()->user()->department_id)
            ->get();

        $hasAttachments = $checkIfHaveAttachments->every(function($value, $key) {
            return $value->attachments->isNotEmpty();
        });

        if (!$hasAttachments) {
            
            return back()->with('kpiErrors', ['Please attach a file in every KPI.']);
        }
        else {
            $validator = Validator::make($request->all(), [
                'actual[]' => 'array',
                'remarks[]' => 'array',
                'grade[]' => 'array',
                'actual.*' => 'required',
                'remarks.*' => 'required',
                'grade.*' => 'required'
            ], [
                'actual.*' => 'The actual field is required.',
                'remarks.*' => 'The remarks field is required.',
                'grade.*' => 'The grades field is required.'
            ]);
    
            if ($validator->fails()) {

                return back()->with('kpiErrors', $validator->errors()->all());
            } else {
                
                $departmentalGoalsList = DepartmentalGoals::whereIn('department_kpi_id', $request->department_kpi_id)
                    ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $request->yearAndMonth)
                    ->get();

                if ($departmentalGoalsList->isEmpty()) {
                    
                    $departmentKpi = DepartmentKPI::whereIn('id', $request->department_kpi_id)->get();

                    $targetDate = 0;
                    foreach($departmentKpi as $dept) {
                        $targetDate = $dept->departments->target_date;
                    }
                    
                    foreach($departmentKpi as $key => $data) {
                        $deptGoals = new  DepartmentalGoals;
                        $deptGoals->department_id = $data->department_id;
                        $deptGoals->department_group_id = $data->department_group_id;
                        $deptGoals->department_kpi_id = $data->id;
                        $deptGoals->kpi_name = $data->name;
                        $deptGoals->target = $data->target;
                        $deptGoals->actual = $request->actual[$key];
                        $deptGoals->grade = $request->grade[$key];
                        $deptGoals->remarks = $request->remarks[$key];
                        $deptGoals->date = $request->yearAndMonth.'-'.$targetDate;
                        $deptGoals->save();
                    }

                }
                else {
                    $targetDate = 0;
                    foreach($departmentalGoalsList as $dept) {
                        $targetDate = $dept->departments->target_date;
                    }
    
                    $actual = $request->input('actual');
                    $remarks = $request->input('remarks');
                    $grades = $request->input('grade');
                    
                    $departmentalGoalsList->each(function($item, $index) use($remarks, $actual, $grades, $targetDate, $request) {
                        $item->update([
                            'actual' => $actual[$index],
                            'remarks' => $remarks[$index],
                            'grade' => $grades[$index],
                            'date' =>  $request->yearAndMonth.'-'.$targetDate
                        ]);
                    });
                }

                $date = $request->yearAndMonth.'-'.$targetDate;
                
                $this->computeKpi($grades, $date, $request->yearAndMonth);
                
                return back();
            }
        }
    }

    public function computeKpi($grades, $date) {
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
        
        $yearAndMonth = substr($date, 0, 7);
        $kpiScoreData = KpiScore::where('department_id', auth()->user()->department_id)
            ->where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), $yearAndMonth)
            ->first();

        if (!empty($kpiScoreData)) {
            $kpiScoreData->grade = $value;
            $kpiScoreData->rating = $rating;
            $kpiScoreData->score = $score;
            $kpiScoreData->save();
        }
        else {
            $kpiScore = new KpiScore;
            $kpiScore->department_id = auth()->user()->department_id;
            $kpiScore->grade = $value;
            $kpiScore->rating = $rating;
            $kpiScore->score = $score;
            $kpiScore->date = $date;
            $kpiScore->save();
        }
    }
}
