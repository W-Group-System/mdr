<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\MdrSetup;
use App\Approver\MdrSummary;
use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\MdrScore;
use App\DeptHead\MdrStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentalGoalsController extends Controller
{
    public function create(Request $request) {
        // dd($request->all());
        $request->validate([
            'file[]' => 'array',
            'file.*.*' => 'max:1024'
        ]);
        
        foreach($request->department_kpi_id as $key=>$department_kpi_id)
        {
            $departmentalGoals = new DepartmentalGoals;
            $departmentalGoals->department_kpi_id = $department_kpi_id;
            $departmentalGoals->department_id = auth()->user()->department_id;
            // $departmentalGoals->target = $request->target[$deptKey];
            $departmentalGoals->actual = $request->actual[$key];
            $departmentalGoals->remarks = $request->remarks[$key];
            $departmentalGoals->year = date('Y', strtotime($request->yearAndMonth));
            $departmentalGoals->month = date('m', strtotime($request->yearAndMonth));
            $departmentalGoals->deadline = date('Y-m', strtotime("+1 month", strtotime($request->yearAndMonth))).'-'. auth()->user()->department->target_date;
            $departmentalGoals->save();

            $attachments = $request->file('file')[$key];
    
            foreach ($attachments as $attachment) {
                $name = time() . '_' . $attachment->getClientOriginalName();
                $attachment->move(public_path('departmental_goals_files'), $name);
                $file_path = "/departmental_goals_files/" . $name;
    
                $mdrAttachments = new Attachments;
                $mdrAttachments->department_id = auth()->user()->department_id;
                $mdrAttachments->file_path = $file_path;
                $mdrAttachments->departmental_goals_id = $departmentalGoals->id;
                $mdrAttachments->save();
            }
        }

        // $this->computeKpi($request->grade, $request->target_date, $request->yearAndMonth);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request) {
        $request->validate([
            'file[]' => 'array',
            'file.*.*' => 'max:1024'
        ]);

        $departmentalGoals = DepartmentalGoals::findMany($request->department_goals_id);
        foreach($departmentalGoals as $deptKey=>$dptGoals)
        {
            $dptGoals->actual = $request->actual[$deptKey];
            $dptGoals->remarks = $request->remarks[$deptKey];
            $dptGoals->save();

            if ($request->has('file') && isset($request->file('file')[$deptKey]))
            {
                $attachments = $request->file('file')[$deptKey];
                
                foreach ($attachments as $attachment) {
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path('departmental_goals_files'), $name);
                    $file_path = "/departmental_goals_files/" . $name;
        
                    $mdrAttachments = new Attachments;
                    $mdrAttachments->department_id = auth()->user()->department_id;
                    $mdrAttachments->file_path = $file_path;
                    $mdrAttachments->departmental_goals_id = $dptGoals->id;
                    $mdrAttachments->save();
                }
            }
        }

        // $this->computeKpi($request->grade, $request->target_date, $request->yearAndMonth);
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function computeKpi($grades, $date, $yearAndMonth) {
        // $grade = collect($grades);
        
        // $kpiValue = $grade->map(function($item, $key) {

        //     if ($item > 100) {
        //         $item = 100;
        //     }

        //     $value = $item / 100.00;

        //     return $value;
        // });
        
        // $kpiScore = $grade->map(function($item, $key) {
        //     if ($item > 100) {
        //         $item = 100;
        //     }

        //     $grades =  $item / 100.00 * 0.5;
            
        //     return $grades;
        // });
        
        // $value = number_format($kpiValue->sum(), 2);
        // $rating = 3.00;
        // $score = number_format($kpiScore->sum(), 2);
        
        $deadline = date('Y-m', strtotime("+1 month", strtotime($yearAndMonth))).'-'.$date;
        $timeliness = 0;
        if ($deadline < date('Y-m-d'))
        {
            $timeliness = 0.0;
        }
        else 
        {
            $timeliness = 0.4;
        }
        
        $mdrScores = MdrScore::where('department_id', auth()->user()->department_id)->where('yearAndMonth', $yearAndMonth)->first();
        
        if ($mdrScores == null)
        {
            $mdrScores = new MdrScore;
            $mdrScores->department_id = auth()->user()->department_id;
            // $mdrScores->grade = $value;
            // $mdrScores->rating = $rating;
            // $mdrScores->score = $score;
            $mdrScores->grade = null;
            $mdrScores->rating = null;
            $mdrScores->score = null;
            $mdrScores->pd_scores = null;
            $mdrScores->innovation_scores = null;
            $mdrScores->timeliness = $timeliness;
            $mdrScores->yearAndMonth = $yearAndMonth;
            $mdrScores->remarks = null;
            $mdrScores->save();
        }
        else
        {
            // $mdrScores->grade = $value;
            // $mdrScores->rating = $rating;
            // $mdrScores->score = $score;
            $mdrScores->grade = null;
            $mdrScores->rating = null;
            $mdrScores->score = null;
            // $mdrScores->pd_scores = null;
            // $mdrScores->innovation_scores = null;
            $mdrScores->timeliness = $timeliness;
            $mdrScores->yearAndMonth = $yearAndMonth;
            // $mdrScores->remarks = null;
            $mdrScores->save();
        }
    }
}
