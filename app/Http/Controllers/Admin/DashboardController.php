<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Approver\MdrSummary;
use App\DeptHead\Mdr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class DashboardController extends Controller
{
    public function index(Request $request) {
        if (auth()->user()->account_role == 0) {
            $totalUsers = User::count();
    
            $totalDepartments = Department::count();

            return view('admin.dashboard',
                array(
                    'totalUsers' => $totalUsers,
                    'totalDepartments' => $totalDepartments,
                )
            );
        } else if(auth()->user()->account_role == 1) {
            $departmentList = Department::with([
                'mdrSummary' => function($q)use($request) {
                    // if (!empty($request->department) && !empty($request->yearAndMonth)) {
                    //     $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    //         ->where('month', date('m', strtotime($request->yearAndMonth)))
                    //         ->where('department_id', $request->department)
                    //         ->orderBy('rate', 'DESC');
                    // }
                    // else {
                    //     $q->where('year', date('Y'))
                    //         ->where('month', date('m'))
                    //         ->orderBy('rate', 'DESC');
                    // }
                    if (!empty($request->startYearAndMonth) && !empty($request->endYearAndMonth) && !empty($request->department)) {
                        $q->whereBetween('year',[ date('Y', strtotime($request->startYearAndMonth)), date('Y', strtotime($request->endYearAndMonth))])
                            ->whereBetween('month', [date('m', strtotime($request->startYearAndMonth)), date('m', strtotime($request->endYearAndMonth))])
                            ->where('department_id', $request->department);
                    }
                    else if (!empty($request->startYearAndMonth) && !empty($request->endYearAndMonth)) {
                        $q->whereBetween('year',[ date('Y', strtotime($request->startYearAndMonth)), date('Y', strtotime($request->endYearAndMonth))])
                            ->whereBetween('month', [date('m', strtotime($request->startYearAndMonth)), date('m', strtotime($request->endYearAndMonth))]);
                    }
                    else {
                        $q->whereBetween('year', [date('Y'), date('Y')])
                            ->whereBetween('month',[ date('m'), date('m')]);
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
                if(empty($request->department) && empty($request->startYearAndMonth) && empty($request->endYearAndMonth)) {
                    $mdrStatusArray[$data->id] = [
                        'action' => 'Not Yet Submitted',
                        'status' => '',
                        'deadline' => "0000-00-00",
                        'department' => $data->dept_code.' - '.$data->dept_name,
                        'rate' => number_format(0.00, 2)
                    ];
                } elseif (!empty($request->startYearAndMonth) && !empty($request->endYearAndMonth)) {
                    $mdrStatusArray[$data->id] = [
                        'action' => 'Not Yet Submitted',
                        'status' => '',
                        'deadline' => "0000-00-00",
                        'department' => $data->dept_code.' - '.$data->dept_name,
                        'rate' => number_format(0.00, 2)
                    ];
                }
                

                $dashboardDataArray[$data->dept_code] = 0.00;

                foreach($data->mdrSummary as $mdrSummaryData) {
                    $mdrStatusArray[$mdrSummaryData->department_id] = [
                        'action' => 'Submitted',
                        'status' => $mdrSummaryData->status,
                        'deadline' => $mdrSummaryData->deadline,
                        'department' => $mdrSummaryData->departments->dept_code .' - '. $mdrSummaryData->departments->dept_name,
                        'rate' => $mdrSummaryData->rate
                    ];

                    $dashboardDataArray[$mdrSummaryData->departments->dept_code] = $mdrSummaryData->rate;
                }
            }
            
            $mdrSummaryData = MdrSummary::where('department_id', $request->department)
                ->whereBetween('year', [date('Y', strtotime($request->startYearAndMonth)), date('Y', strtotime($request->endYearAndMonth))])
                ->whereBetween('month', [date('m', strtotime($request->startYearAndMonth)), date('m', strtotime($request->endYearAndMonth))])
                ->get();

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
                    'departmentValue' => $request->department,
                    'startYearAndMonth' => $request->startYearAndMonth,
                    'endYearAndMonth' => $request->endYearAndMonth,
                    
                    'listOfDepartment' => $departmentList,
                    'departmentList' => $departmentArray,
                    // 'yearAndMonth' => $request->yearAndMonth,
                    'dashboardData' => $dashboardDataArray,
                    'mdrStatus' => collect($mdrStatusArray)->sortBy('rate'),
                    'monthAndData' => $monthAndDataArray,
                    'mdrSummaryStatusPerDept' => $statusArray,
                    'date' =>  !empty($request->startYearAndMonth) ? date('F Y', strtotime($request->startYearAndMonth)) : date('F Y')
                )
            );
        } else if (auth()->user()->account_role == 2) {
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
        }
        
        
    }
}
