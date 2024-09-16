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

        if(auth()->user()->role == "Administrator")
        {
            $total_users = User::get();
            $total_dept = Department::get();

            return view('admin.dashboard',
                array(
                    // 'months' => $months
                    'total_users' => $total_users,
                    'total_dept' => $total_dept
                )
            );
        }

        if (auth()->user()->role == "Department Head")
        {
            $months = [];
            for ($m=1; $m<=12; $m++) {
                $object = new \stdClass();
                $object->y =date('M-Y', mktime(0,0,0,$m, 1, date('Y')));
                $object->mdr_status = MdrSummary::select('rate')->where('department_id', auth()->user()->department_id)->where('yearAndMonth', date('Y-m', mktime(0,0,0,$m,1,date('Y'))))->pluck('rate')->toArray();
                $months[$m-1] = $object;
            }

            return view('admin.dashboard',
                array(
                    'months' => $months
                )
            );
        }

        if(auth()->user()->role == "Approver")
        {
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

            $mdr_score_array = [];
            foreach($departments as $department)
            {
                $mdr_score = MdrScore::with('mdrSummary')->where('department_id', $department->id)->where('yearAndMonth', $request->yearAndMonth)->first();
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

            $summary_kpi = [];
            foreach($departments as $dept)
            {
                $object = new \stdClass();
                $object->d = $dept->code;
                $object->mdr_status = MdrSummary::select('rate')->where('department_id', $dept->id)
                    ->where(function($q)use($request) {
                        if ($request->yearAndMonth == null)
                        {
                            $q->where('yearAndMonth', date('Y-m'));
                        }
                        else
                        {
                            $q->where('yearAndMonth', $request->yearAndMonth);
                        }
                    })
                    ->get()
                    ->pluck('rate')
                    ->toArray();
                
                $summary_kpi[] = $object;
            }
            
            $months = [];
            for ($m=1; $m<=12; $m++) {
                $object = new \stdClass();
                $object->y =date('M-Y', mktime(0,0,0,$m, 1, $request->years1));
                $object->year1 = date('Y-m', mktime(0,0,0,$m, 1, $request->years1));
                $object->mdr_status = MdrSummary::select('rate')->where('department_id', $request->departmentValue)->where('yearAndMonth', $object->year1)->pluck('rate')->toArray();
                $months[$m-1] = $object;
            }
            
            $months_array = [];
            for ($m=1; $m<=12; $m++) {
                $object = new \stdClass();
                $object->y =date('M-Y', mktime(0,0,0,$m, 1, $request->years2));
                $object->year2 = date('Y-m', mktime(0,0,0,$m, 1, $request->years2));
                $object->mdr_status = MdrSummary::select('rate')->where('department_id', $request->departmentValue)->where('yearAndMonth', $object->year2)->pluck('rate')->toArray();
                $months_array[$m-1] = $object;
            }
            
            $start_year = date('Y') - 20;
            $current_year = date('Y');

            $year_array = [];
            for($year = $start_year; $year <= $current_year; $year++)
            {
                $year_array[] = $year;
            }
            
            return view('admin.dashboard',
                array(
                    'yearAndMonth' => $request->yearAndMonth,
                    'mdr_summary' => $mdr_summary,
                    'mdr_score_array' => $mdr_score_array,
                    'summary_kpi' => $summary_kpi,
                    'departments' => $departments,
                    'departmentValue' => $request->departmentValue,
                    'months' => $months,
                    'months_array' => $months_array,
                    'years' => $year_array,
                    'year1' => $request->years1,
                    'year2' => $request->years2
                )  
            );
        }

    }

    public function printPdf(Request $request) {
        $dataArray = array();
        $dataArray['yearAndMonth'] = !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m');

        $departmentList = Department::with([
            'mdrSummary' => function($q)use($request) {
                if (!empty($request->yearAndMonth)) {
                    $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->where('month', date('m', strtotime($request->yearAndMonth)));
                }
                else {
                    $q->where('year', date('Y'))
                        ->where('month', date('m'));
                }
            },
        ])
        ->get();
        
        $mdrStatusArray = array();
        foreach($departmentList as $data) {
            if(empty($request->department)) {
                $mdrStatusArray[$data->id] = [
                    'action' => 'Not Yet Submitted',
                    'status' => 'No Status Yet',
                    'deadline' => "0000-00-00",
                    'department' => $data->code.' - '.$data->name,
                    'rate' => number_format(0.00, 2),
                    'kpi' => number_format(0.00, 2),
                    'innovation_scores' => number_format(0.0, 1),
                    'pd_scores' => number_format(0.0, 1),
                    'timeliness' => number_format(0.0, 1)
                ];
            }

            foreach($data->mdrSummary as $mdrSummaryData) {
                $mdrStatusArray[$mdrSummaryData->department_id] = [
                    'action' => 'Submitted',
                    'status' => $mdrSummaryData->status,
                    'deadline' => $mdrSummaryData->deadline,
                    'department' => $mdrSummaryData->departments->code .' - '. $mdrSummaryData->departments->name,
                    'rate' => $mdrSummaryData->rate,
                    'kpi' => $mdrSummaryData->kpiScores->score,
                    'innovation_scores' => $mdrSummaryData->kpiScores->innovation_scores,
                    'pd_scores' => $mdrSummaryData->kpiScores->pd_scores,
                    'timeliness' => $mdrSummaryData->kpiScores->timeliness
                ];
            }
        }

        $dataArray['mdrSummary'] = collect($mdrStatusArray)->sortBy('rate');
        
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('pdf.mdr-summary', $dataArray)
            ->setPaper('a3', 'landscape');

        return $pdf->stream('MDR Summary.pdf');
    }
}
