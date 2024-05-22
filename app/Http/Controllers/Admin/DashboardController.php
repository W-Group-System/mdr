<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Approver\MdrSummary;
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
                    $q->where('month',date('m', strtotime($request->yearAndMonth)))
                        ->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->orderBy('department_id', 'ASC');
                },
            ])
            ->orderBy('id', 'ASC')
            ->get();

            $mdrStatusArray = array();
            $dashboardDataArray = array();
            $departmentArray = array();
            foreach($departmentList as $data) {
                $mdrStatusArray[$data->id] = [
                    'action' => 'Not Yet Submitted',
                    'status' => '',
                    'deadline' => date('Y-m', strtotime("+1month", strtotime($request->yearAndMonth))).'-'.$data->target_date,
                    'department' => $data->dept_code.' - '.$data->dept_name,
                    'rate' => 0.00
                ];

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
            
            return view('admin.dashboard',
                array(
                    'departmentList' => $departmentArray,
                    'yearAndMonth' => $request->yearAndMonth,
                    'dashboardData' => $dashboardDataArray,
                    'mdrStatus' => $mdrStatusArray
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
            $months = array();
            foreach($mdrSummary as $data) {
                $months[] = $monthArray[$data->month];
                $dashboardDataArray[] = $data->rate;
            }

            return view('admin.dashboard',
                array(
                    'data' => $dashboardDataArray,
                    'month' => $months,
                    'years' => $request->year
                )
            );
        }
        
        
    }
}
