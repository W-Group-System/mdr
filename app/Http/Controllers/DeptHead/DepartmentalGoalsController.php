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
        $attachments = $request->file('file');

        foreach($attachments as $key=>$attachment)
        {
            $departmentalGoals = new DepartmentalGoals;
            $departmentalGoals->kpi_name = $request->name[$key];
            $departmentalGoals->department_id = auth()->user()->department_id;
            $departmentalGoals->target = $request->target[$key];
            $departmentalGoals->actual = $request->actual[$key];
            $departmentalGoals->grade = $request->grade[$key];
            $departmentalGoals->remarks = $request->remarks[$key];
            $departmentalGoals->yearAndMonth = $request->yearAndMonth;
            $departmentalGoals->deadline = date('Y-m', strtotime("+1 month", strtotime($request->yearAndMonth))).'-'.$request->target_date;
            $departmentalGoals->save();
            
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path('departmental_goals_files'), $name);
            $file_path = "/departmental_goals_files/" . $name;

            $mdrAttachments = new Attachments;
            $mdrAttachments->department_id = auth()->user()->department_id;
            $mdrAttachments->file_path = $file_path;
            $mdrAttachments->departmental_goals_id = $departmentalGoals->id;
            $mdrAttachments->save();
        }

        $this->computeKpi($request->grade, $request->target_date, $request->yearAndMonth);

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request, $id) {
        $departmentalGoals = DepartmentalGoals::findOrFail($id);
        $departmentalGoals->actual = $request->actual;
        $departmentalGoals->grade = $request->grade;
        $departmentalGoals->remarks = $request->remarks;
        if($request->has('file'))
        {
            $mdrAttachments = Attachments::where('departmental_goals_id', $id)->delete();

            $attachment = $request->file('file');
            $name = time().'_'.$attachment->getClientOriginalName();
            $attachment->move(public_path('departmental_goals_files'), $name);
            $filename = '/departmental_goals_files/'.$name;

            $mdrAttachments = new Attachments;
            $mdrAttachments->department_id = auth()->user()->department_id;
            $mdrAttachments->departmental_goals_id = $id;
            $mdrAttachments->file_path = $filename;
            $mdrAttachments->save();
        }

        $departmentalGoals->save();
        
        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function computeKpi($grades, $date, $yearAndMonth) {
        $grade = collect($grades);
        
        $kpiValue = $grade->map(function($item, $key) {

            if ($item > 100) {
                $item = 100;
            }

            $value = $item / 100.00;

            return $value;
        });
        
        $kpiScore = $grade->map(function($item, $key) {
            if ($item > 100) {
                $item = 100;
            }

            $grades =  $item / 100.00 * 0.5;
            
            return $grades;
        });
        
        $value = number_format($kpiValue->sum(), 2);
        $rating = 3.00;
        $score = number_format($kpiScore->sum(), 2);

        $deadline = date('Y-m', strtotime("+1 month", strtotime($yearAndMonth))).'-'.$date;
        $timeliness = 0;
        if ($deadline < date('Y-m-d'))
        {
            $timeliness = 0.0;
        }
        else 
        {
            $timeliness = 0.1;
        }
        
        $mdrScores = MdrScore::where('department_id', auth()->user()->department_id)->where('yearAndMonth', $yearAndMonth)->first();
        
        if ($mdrScores == null)
        {
            $mdrScores = new MdrScore;
            $mdrScores->department_id = auth()->user()->department_id;
            $mdrScores->grade = $value;
            $mdrScores->rating = $rating;
            $mdrScores->score = $score;
            $mdrScores->pd_scores = null;
            $mdrScores->innovation_scores = null;
            $mdrScores->timeliness = $timeliness;
            $mdrScores->yearAndMonth = $yearAndMonth;
            $mdrScores->remarks = null;
            $mdrScores->save();
        }
        else
        {
            $mdrScores->grade = $value;
            $mdrScores->rating = $rating;
            $mdrScores->score = $score;
            $mdrScores->pd_scores = null;
            $mdrScores->innovation_scores = null;
            $mdrScores->timeliness = $timeliness;
            $mdrScores->yearAndMonth = $yearAndMonth;
            $mdrScores->remarks = null;
            $mdrScores->save();
        }
    }
}
