<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\MdrGroup;
use App\DepartmentKpi;
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentKpiController extends Controller
{
    public function index(Request $request) {
        $selectedMonth = request('month') ?? date('F');
        $selectedYear = request('year') ?? date('Y');
        $selectedDepartment = $request->department;
        $departmentList = Department::select('id', 'name', 'code')->where('status',"Active")->get();

        $department_kpis = DepartmentKpi::with('mdr_group','department')
            ->when($selectedDepartment, function ($query, $selectedDepartment) {
                $query->where('department_id', $selectedDepartment);
            }, function ($query) {
                return $query->whereRaw('1 = 0');
            })
            // ->where('month', $selectedMonth)
            // ->where('year', $selectedYear)
            ->orderBy('department_id', 'asc')
            ->get();
        

        return view('admin.department_kpi',
            array(
                'departmentList' => $departmentList,
                'department' => $request->department,
                'department_kpis' => $department_kpis,
                'selectedMonth' => $selectedMonth,
                'selectedYear' => $selectedYear,
            )
        );
    }

    public function addDepartmentKpi(Request $request) {

        $request->validate([
            'department' => 'required',
            'attachment_description' => 'nullable|string|max:50',
        ]);

        $mdrSetup = new DepartmentKpi;
        $mdrSetup->department_id = $request->department;
        $mdrSetup->mdr_group_id = 1;
        $mdrSetup->name = $request->kpiName;
        $mdrSetup->target = $request->target;
        $mdrSetup->attachment_description = $request->attachment_description;
        $mdrSetup->status = "Active";
        // $mdrSetup->month = $request->month;
        // $mdrSetup->year = $request->year;
        $mdrSetup->save();

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

    public function updateDepartmentKpi(Request $request, $id) {
    
        $request->validate([
            'department' => 'required',
            'attachment_description' => 'nullable|string|max:50',
        ]);

        $mdrSetup = DepartmentKpi::findOrFail($id);
        $mdrSetup->department_id = $request->department;
        $mdrSetup->mdr_group_id = 1;
        $mdrSetup->name = $request->kpiName;
        $mdrSetup->target = $request->target;
        $mdrSetup->attachment_description = $request->attachment_description;
        // $mdrSetup->month = $request->month;
        // $mdrSetup->year = $request->year;
        $mdrSetup->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function duplicateDepartmentKpiSave(Request $request, $id)
    {
        $original = DepartmentKpi::findOrFail($id);

        $duplicate = new DepartmentKpi;
        $duplicate->department_id = $request->department;
        $duplicate->mdr_group_id = $original->mdr_group_id;
        $duplicate->name = $request->kpiName;
        $duplicate->target = $request->target;
        $duplicate->attachment_description = $request->attachment_description;
        // $duplicate->month = $request->month;
        // $duplicate->year = $request->year;
        $duplicate->status = 'Active';
        $duplicate->save();

        Alert::success('Successfully duplicated for next month!')->persistent('Dismiss');
        return back();
    }


    public function deactivate($id) {
        $mdrSetup = DepartmentKpi::findOrFail($id);
        $mdrSetup->status = "Inactive";
        $mdrSetup->save();
        
        Alert::success("Successfully Deactivated")->persistent('Dismiss');
        return back();
    }

    public function activate($id) {
        $mdrSetup = DepartmentKpi::findOrFail($id);
        $mdrSetup->status = "Active";
        $mdrSetup->save();
        
        Alert::success("Successfully Activated")->persistent('Dismiss');
        return back();
    }
}
