<?php

use App\DeptHead\Innovation;
use App\DeptHead\Mdr;
use App\DeptHead\MdrApprovers;
use App\DeptHead\MdrScore;
use App\DeptHead\ProcessDevelopment;

use App\Module;
use App\Submodule;
use App\UserAccessModule;

use Carbon\Carbon;


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

function computeKpi($grades,$id)
{
    $grade = collect($grades);
    $rating = 3.00;
    $value = round($grade->sum(), 2);
    $score = round($grade->sum(), 2);
    
    $mdr = Mdr::findOrFail($id);
    $mdr->grade = $value;
    $mdr->rating = $rating;
    $mdr->score = $score;
    $mdr->save();
}

function for_approval_count()
{
    // $mdrApprovers = MdrApprovers::with('mdrSummary')->where('user_id', auth()->user()->id)->where('status', 'Pending')->get();

    // if (auth()->user()->role == "Administrator")
    // {
    //     $mdrApprovers = MdrApprovers::orderBy('id', 'desc')->get();
    // }

    // return $mdrApprovers;
    return [];
}

function department_deadline()
{
    
}


function check_access($module_name,$action)
{
    $module = Module::where('module_name', $module_name)->first();
    if (empty($module))
    {
        $submodule = Submodule::where('submodule_name', $module_name)->first();
        $user_access_module = UserAccessModule::where($action,'on')->where('submodule_id', $submodule->id)->where('user_id', auth()->user()->id)->first();
    }
    else
    {
        $user_access_module = UserAccessModule::where($action,'on')->where('module_id', $module->id)->where('user_id', auth()->user()->id)->first();
    }
    
    if ($user_access_module)
    {
        return true;
    }

    return false;
}

function getAdjustedTargetDate($month, $year, $targetDay)
{
    $mdrDate = DateTime::createFromFormat('!m Y', $month . ' ' . $year);

    $nextMonth = $mdrDate->modify('+1 month');

    $targetDay = str_pad($targetDay, 2, '0', STR_PAD_LEFT);
    $fullTargetDate = DateTime::createFromFormat('Y-m-d', $nextMonth->format("Y-m") . '-' . $targetDay);

    $dayOfWeek = $fullTargetDate->format('w');
    if ($dayOfWeek == 6) {
        $fullTargetDate->modify('+2 days'); 
    } elseif ($dayOfWeek == 0) {
        $fullTargetDate->modify('+1 day');
    }

    return $fullTargetDate;
}

function generateSafeDeadline(string $yearAndMonth, int $targetDay): string
{
    $baseDate = Carbon::createFromFormat('Y-m', $yearAndMonth)->startOfMonth();
    $nextMonth = $baseDate->copy()->addMonth();
    $lastDay = $nextMonth->copy()->endOfMonth()->day;
    $safeDay = min($targetDay, $lastDay);
    return Carbon::createFromDate($nextMonth->year, $nextMonth->month, $safeDay)->toDateString();
}

