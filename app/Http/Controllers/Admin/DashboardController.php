<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Approver\MdrSummary;
use App\DeptHead\Mdr;
use App\DeptHead\MdrScore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\App;
use stdClass;

class DashboardController extends Controller
{
    public function index(Request $request) {

        return view('admin.dashboard',
            
        );

    }

    public function printPdf(Request $request) {
        // $dataArray = array();
        // $dataArray['yearAndMonth'] = !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m');

        // $departmentList = Department::with([
        //     'mdrSummary' => function($q)use($request) {
        //         if (!empty($request->yearAndMonth)) {
        //             $q->where('year', date('Y', strtotime($request->yearAndMonth)))
        //                 ->where('month', date('m', strtotime($request->yearAndMonth)));
        //         }
        //         else {
        //             $q->where('year', date('Y'))
        //                 ->where('month', date('m'));
        //         }
        //     },
        // ])
        // ->get();
        
        // $mdrStatusArray = array();
        // foreach($departmentList as $data) {
        //     if(empty($request->department)) {
        //         $mdrStatusArray[$data->id] = [
        //             'action' => 'Not Yet Submitted',
        //             'status' => 'No Status Yet',
        //             'deadline' => "0000-00-00",
        //             'department' => $data->code.' - '.$data->name,
        //             'rate' => number_format(0.00, 2),
        //             'kpi' => number_format(0.00, 2),
        //             'innovation_scores' => number_format(0.0, 1),
        //             'pd_scores' => number_format(0.0, 1),
        //             'timeliness' => number_format(0.0, 1)
        //         ];
        //     }

        //     foreach($data->mdrSummary as $mdrSummaryData) {
        //         $mdrStatusArray[$mdrSummaryData->department_id] = [
        //             'action' => 'Submitted',
        //             'status' => $mdrSummaryData->status,
        //             'deadline' => $mdrSummaryData->deadline,
        //             'department' => $mdrSummaryData->departments->code .' - '. $mdrSummaryData->departments->name,
        //             'rate' => $mdrSummaryData->rate,
        //             'kpi' => $mdrSummaryData->kpiScores->score,
        //             'innovation_scores' => $mdrSummaryData->kpiScores->innovation_scores,
        //             'pd_scores' => $mdrSummaryData->kpiScores->pd_scores,
        //             'timeliness' => $mdrSummaryData->kpiScores->timeliness
        //         ];
        //     }
        // }

        // $dataArray['mdrSummary'] = collect($mdrStatusArray)->sortBy('rate');

        $departments = Department::get();
        $mdr_summary = MdrSummary::where(function($query)use($request) {
                if ($request->yearAndMonth)
                {
                    $query->where('yearAndMonth', $request->yearAndMonth);
                }
                else
                {
                    $query->where('yearAndMonth', date('Y-m'));
                }
            })
            ->get();

        $year_and_month = null;
        if ($request->yearAndMonth != null)
        {
            $year_and_month = $request->yearAndMonth;
        }
        else
        {
            $year_and_month = date('Y-m');
        }

        $mdr_score_array = [];
        foreach($departments as $department)
        {
            $mdr_score = MdrScore::with('mdrSummary')->where('department_id', $department->id)->where('yearAndMonth', $year_and_month)->first();
            $object = new stdClass();
            $object->name = $department->name;
            if ($mdr_score)
            {
                $object->status = optional($mdr_score->mdrSummary)->status;
                $object->deadline = optional($mdr_score->mdrSummary)->deadline;
                $object->scores = $mdr_score->score;
                $object->innovation_scores = $mdr_score->innovation_scores;
                $object->pd_scores = $mdr_score->pd_scores;
                $object->timeliness = $mdr_score->timeliness;
                $object->total_rating = $mdr_score->total_rating;
            }
            else
            {
                $object->status = null;
                $object->deadline = null;
                $object->scores = null;
                $object->innovation_scores = null;
                $object->pd_scores = null;
                $object->timeliness = null;
                $object->total_rating = null;
            }

            $mdr_score_array[] = $object;
        }
        
        $data_array = [];
        $data_array['mdr_summary'] = $mdr_score_array;
        $data_array['year_and_month'] = $year_and_month;
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.mdr-summary', $data_array)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('MDR Summary.pdf');
    }
}
