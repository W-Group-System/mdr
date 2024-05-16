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
            // $departmentList = Department::with('mdrSummary')->get();

            $mdrSummary = MdrSummary::query();

            if(!empty($request->yearAndMonth)) {
                $mdrSummary = $mdrSummary->where('year', date("Y", strtotime($request->yearAndMonth)));
                $mdrSummary = $mdrSummary->where('month', date("m", strtotime($request->yearAndMonth)));
            }

            $mdrSummary = $mdrSummary->get();

            return view('admin.dashboard',
                array(
                    // 'departmentList' => $departmentList,
                    'yearAndMonth' => $request->yearAndMonth,
                    'mdrSummary' => $mdrSummary,
                )
            );
        }
        
        return view('admin.dashboard');
        
    }
}
