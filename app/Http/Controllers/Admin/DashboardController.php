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

class DashboardController extends Controller
{
    public function index(Request $request) {
        if (auth()->user()->role == "Administrator") {
            $totalUsers = User::count();
    
            $totalDepartments = Department::count();

            return view('admin.dashboard',
                array(
                    'totalUsers' => $totalUsers,
                    'totalDepartments' => $totalDepartments,
                )
            );
        } else if(auth()->user()->role == "Approver") {
            $departmentList = Department::with([
                'mdrSummary' => function($q)use($request) {
                    if (!empty($request->department) && !empty($request->yearAndMonth)) {
                        $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                            ->where('month', date('m', strtotime($request->yearAndMonth)))
                            ->where('department_id', !empty($request->department) ?  $request->department : '');
                    }
                    else if (!empty($request->yearAndMonth)) {
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

            $monthArray = array(
                '01' => 'January',
                '02' => 'February',
                '03' => 'March',
                '04' => 'April',
                '05' => 'May',
                '06' => 'June',
                '07' => 'July',
                '08' => 'August',
                '09' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            );

            $mdrStatusArray = array();
            $dashboardDataArray = array();
            $departmentArray = array();
            foreach($departmentList as $data) {
                if (empty($request->department)) {
                    $mdrStatusArray[$data->id] = [
                        'action' => 'Not Yet Submitted',
                        'status' => 'No Status Yet',
                        'deadline' => "0000-00-00",
                        'department' => $data->dept_code.' - '.$data->dept_name,
                        'rate' => number_format(0.00, 2),
                        'kpi' => number_format(0.00, 2),
                        'innovation_scores' => number_format(0.0, 1),
                        'pd_scores' => number_format(0.0, 1),
                        'timeliness' => number_format(0.0, 1)
                    ];
                }
                
                $dashboardDataArray[$data->dept_code] = 0.00;

                foreach($data->mdrSummary as $mdrSummaryData) {
                    $mdrStatusArray[$mdrSummaryData->department_id] = [
                        'action' => 'Submitted',
                        'status' => $mdrSummaryData->status,
                        'deadline' => $mdrSummaryData->deadline,
                        'department' => $mdrSummaryData->departments->dept_code .' - '. $mdrSummaryData->departments->dept_name,
                        'rate' => $mdrSummaryData->rate,
                        'kpi' => $mdrSummaryData->kpiScores->score,
                        'innovation_scores' => $mdrSummaryData->kpiScores->innovation_scores,
                        'pd_scores' => $mdrSummaryData->kpiScores->pd_scores,
                        'timeliness' => $mdrSummaryData->kpiScores->timeliness
                    ];

                    $dashboardDataArray[$mdrSummaryData->departments->dept_code] = $mdrSummaryData->rate;
                }
            }

            $mdrSummaryData = MdrSummary::where('department_id', $request->departmentValue);

            if(!empty($request->startYearAndMonth) && !empty($request->endYearAndMonth)) {
                $mdrSummaryData = $mdrSummaryData->whereBetween('year', [date('Y', strtotime($request->startYearAndMonth)), date('Y', strtotime($request->endYearAndMonth))])
                    ->whereBetween('month', [date('m', strtotime($request->startYearAndMonth)), date('m', strtotime($request->endYearAndMonth))]);
            }

            $mdrSummaryData = $mdrSummaryData->get();

            $barChartPerDeptArray = array();
            foreach($monthArray as $key=>$month) {
                $yearNow = date('Y');

                $barChartPerDeptArray[$key] = [
                    'month' => date('M', strtotime($yearNow.'-'.$key)),
                    'rate' => 0.0,
                    'status' => 'No MDR Submitted'
                ];

                foreach($mdrSummaryData as $data) {
                    $barChartPerDeptArray[$data->month] = [
                        'month' => date('M', strtotime($data->year.'-'.$data->month)),
                        'rate' => $data->rate,
                        'status' => $data->status
                    ];
                }

                ksort($barChartPerDeptArray);
            }

            $monthAndDataArray = array();
            $statusArray = array();
            foreach($barChartPerDeptArray as $data) {
                $monthAndDataArray[$data['month']] = $data['rate'];
                
                $statusArray[] = [
                    'month' => $data['month'],
                    'status' => $data['status']
                ];
            }

            return view('admin.dashboard',
                array(
                    'departmentValue' => $request->departmentValue,
                    'startYearAndMonth' => $request->startYearAndMonth,
                    'endYearAndMonth' => $request->endYearAndMonth,
                    'department'=> $request->department,
                    
                    'listOfDepartment' => $departmentList,
                    'departmentList' => $departmentArray,
                    'yearAndMonth' => !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m'),
                    'dashboardData' => $dashboardDataArray,
                    'mdrStatus' => collect($mdrStatusArray)->sortBy('rate'),
                    'monthAndData' => $monthAndDataArray,
                    'mdrSummaryStatusPerDept' => $statusArray,
                    'date' =>  !empty($request->startYearAndMonth) ? date('F Y', strtotime($request->startYearAndMonth)) : date('F Y')
                )
            );
        } else if (auth()->user()->role == "Department Head" || auth()->user()->role == "Users") {
            $mdrSummary = MdrSummary::where('department_id', auth()->user()->department_id);
            
            if (!empty($request->year)) {
                $mdrSummary = $mdrSummary->where('year', $request->year)->get();
            }
            else {
                $mdrSummary = $mdrSummary->where('year', date("Y"))->get();
            }

            $monthArray = array(
                '01' => 'January',
                '02' => 'February',
                '03' => 'March',
                '04' => 'April',
                '05' => 'May',
                '06' => 'June',
                '07' => 'July',
                '08' => 'August',
                '09' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            );

            $dashboardDataArray = array();
            
            foreach($monthArray as $key => $monthData) {
                $yearNow = date('Y');

                $dashboardDataArray[$key] = [
                    'month' => date('F', strtotime($yearNow.'-'.$key)),
                    'rate' => 0.0,
                    'status' => 'No MDR Submitted'
                ];

                foreach($mdrSummary as $data) {
                    $dashboardDataArray[$data->month] = [
                        'month' => date('F', strtotime($data->year.'-'.$data->month)),
                        'rate' => $data->rate,
                        'status' => $data->status
                    ];
                    ksort($dashboardDataArray);
                }
            }

            $dataArray = array();
            $statusArray = array();
            foreach($dashboardDataArray as $data) {
                $dataArray[$data['month']] = $data['rate'];
                
                $statusArray[] = [
                    'month' => $data['month'],
                    'status' => $data['status']
                ];
            }

            return view('admin.dashboard',
                array(
                    'data' => $dataArray,
                    'status' => $statusArray,
                    'years' => $request->year
                )
            );
        } else if(auth()->user()->role == "Human Resources") {
            $mdrSummary = MdrSummary::with(['departments.user'])
                ->where('rate', '<', 2.99)
                ->where('final_approved', 1);

            if (!empty($request->yearAndMonth)) {
                $mdrSummary = $mdrSummary->where('year', date('Y', strtotime($request->yearAndMonth)))
                                        ->where('month', date('m', strtotime($request->yearAndMonth)));
            }
            else {
                $mdrSummary = $mdrSummary->where('year', date('Y'))
                                        ->where('month', date('m'));
            }
    
            $mdrSummary = $mdrSummary->get();
            
            $barDataArray = array();
            foreach($mdrSummary as $data) {
                $barDataArray[$data->departments->dept_code] = $data->rate;
            }

            return view('admin.dashboard',
                array(
                    'mdrSummary' => $mdrSummary,
                    'yearAndMonth' => !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m'),
                    'barData' => $barDataArray
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
                    'department' => $data->dept_code.' - '.$data->dept_name,
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
                    'department' => $mdrSummaryData->departments->dept_code .' - '. $mdrSummaryData->departments->dept_name,
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
