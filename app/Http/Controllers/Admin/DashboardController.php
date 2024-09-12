<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Approver\MdrSummary;
use App\DeptHead\Mdr;
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
            // $mdr_summary = MdrSummary::get();

            return view('admin.dashboard',
              array(
                
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
