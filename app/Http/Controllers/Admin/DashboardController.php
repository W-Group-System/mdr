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
            $mdrSummary = MdrSummary::query();

            if(!empty($request->yearAndMonth)) {
                $mdrSummary = $mdrSummary->where('year', date("Y", strtotime($request->yearAndMonth)))
                    ->where('month', date("m", strtotime($request->yearAndMonth)));
            }
            
            $mdrSummary = $mdrSummary
                ->orderBy('department_id', "ASC")
                ->get();

            $dashboardDataArray = array();
            $departmentArray = array();
            foreach($mdrSummary as $data) {
                $departmentArray[] = $data->departments->dept_code;
                $dashboardDataArray[] = $data->rate;
            }
            
            return view('admin.dashboard',
                array(
                    'departmentList' => $departmentArray,
                    'yearAndMonth' => $request->yearAndMonth,
                    'mdrSummary' => $mdrSummary,
                    'dashboardData' => $dashboardDataArray
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
