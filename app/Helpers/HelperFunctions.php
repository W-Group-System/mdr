<?php

use App\DeptHead\Innovation;
use App\DeptHead\MdrApprovers;
use App\DeptHead\MdrScore;
use App\DeptHead\ProcessDevelopment;

function getOrdinal($number) {
    $number = (int) $number;

    if (in_array(($number % 100), [11, 12, 13])) {
        return $number . 'th';
    }

    switch ($number % 10) {
        case 1:
            return $number . 'st of the Month';
        case 2:
            return $number . 'nd of the Month';
        case 3:
            return $number . 'rd of the Month';
        default:
            return $number . 'th of the Month';
    }
}

function processImprovementComputations($action, $department, $yearAndMonth)
{
    $mdr_score = MdrScore::where('department_id', $department)->where('yearAndMonth', $yearAndMonth)->first();
    
    $processImprovementCount = ProcessDevelopment::where('yearAndMonth', $yearAndMonth)
        ->where('department_id', auth()->user()->department_id)
        ->count();
    
    if ($action == "add")
    {
        if ($processImprovementCount == 1)
        {
            $mdr_score->pd_scores = 0.5;
            $mdr_score->total_rating = 0.5;
        }
        else
        {
            $mdr_score->pd_scores = 1.0;
            $mdr_score->total_rating = 1.0;
        }

        $mdr_score->save();
    }

    if($action == "delete")
    {
        if ($processImprovementCount == 1)
        {
            $mdr_score->pd_scores = 0.5;
            $mdr_score->total_rating = 0.5;
        }
        else
        {
            $mdr_score->pd_scores = 0.0;
            $mdr_score->total_rating = 0.0;
        }

        $mdr_score->save();
    }
}

function computeKpi($grades, $date, $yearAndMonth, $department)
{
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
        $timeliness = 0.4;
    }
    
    $mdrScores = MdrScore::where('department_id', $department)->where('yearAndMonth', $yearAndMonth)->first();
    $mdrScores->grade = $value;
    $mdrScores->rating = $rating;
    $mdrScores->score = $score;
    
    $total_rating = $mdrScores->score + $mdrScores->pd_scores + $mdrScores->timeliness;
    
    $mdrScores->total_rating = $total_rating;
    $mdrScores->save();
    
}

function for_approval_count()
{
    $mdrApprovers = MdrApprovers::with('mdrSummary')->where('user_id', auth()->user()->id)->where('status', 'Pending')->get();

    if (auth()->user()->role == "Administrator")
    {
        $mdrApprovers = MdrApprovers::orderBy('id', 'desc')->get();
    }

    return $mdrApprovers;
}