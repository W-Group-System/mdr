<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ListOfMdr extends Controller
{
    public function index(Request $request) {
        $departmentList = Department::get();

        $departmentKpiGroup = DepartmentGroup::with('departmentalGoals')->get();

        return view('approver.list-of-mdr', 
            array(
                'departmentList' => $departmentList , 
                'department' => $request->department,
                'yearAndMonth' => $request->yearAndMonth,
                'departmentKpiGroup' => $departmentKpiGroup
            )
        );
    }

    
}
