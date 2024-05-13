<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class DashboardController extends Controller
{
    public function index() {

        $totalUsers = User::count();

        $totalDepartments = Department::count();

        return view('admin.dashboard',
            array(
                'totalUsers' => $totalUsers,
                'totalDepartments' => $totalDepartments
            )
        );
    }
}
