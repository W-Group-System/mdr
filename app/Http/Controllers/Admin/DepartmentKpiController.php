<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use App\Admin\MdrGroup;
use App\DepartmentKpi;
use Illuminate\Support\Facades\DB; 
use App\DeptHead\DepartmentalGoals;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentKpiController extends Controller
{
    
    public function index(Request $request) 
    {
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
        $selectedDepartment = $request->department;
        
        $departmentList = Department::select('id', 'name', 'code')->where('status', "Active")->get();
  
        $department_kpis = DB::table('departments as dept')
            ->join('department_kpis as kpi', 'dept.id', '=', 'kpi.department_id')
            ->select(
                'dept.id as department_id',
                'dept.name as department_name',
                'dept.code as department_code',
                'kpi.year',
                'kpi.month',
                DB::raw('COUNT(kpi.id) as total_kpi')
            )
        ->where('kpi.status', 'Active')
        ->when($selectedDepartment, function ($query, $selectedDepartment) {
            $query->where('kpi.department_id', $selectedDepartment);
        })
        ->when($request->filled('month'), function ($query) use ($request) {
            $query->where('kpi.month', $request->month);
        })
        ->when($request->filled('year'), function ($query) use ($request) {
            $query->where('kpi.year', $request->year);
        })
        ->groupBy('dept.id', 'dept.name', 'dept.code', 'kpi.year', 'kpi.month')
        ->orderBy('dept.name', 'asc')
        ->orderBy('kpi.year', 'desc')
        ->orderBy('kpi.month', 'desc')
        ->get();

        return view('admin.department_kpi', [
            'departmentList' => $departmentList,
            'department' => $request->department,
            'department_kpis' => $department_kpis,
            'selectedMonth' => $request->filled('month') ? $request->month : $selectedMonth,
            'selectedYear' => $request->filled('year') ? $request->year : $selectedYear,
        ]);
    }

    // department kpi list
    public function list(Request $request)
    {
        $selectedMonth = $request->input('month', date('m'));
        $selectedYear = $request->input('year', date('Y'));
        $selectedDepartment = $request->department;

        // Show fill-up department column
        $departmentList = Department::select('id', 'name', 'code')
            ->where('status', 'Active')
            ->get();

        $department_kpis = DepartmentKpi::with('mdr_group', 'department')
            ->when($selectedDepartment, function ($query, $selectedDepartment) {
                $query->where('department_id', $selectedDepartment);
            }, function ($query) {
                return $query->whereRaw('1 = 0');
            })
            ->when($request->filled('month'), function ($query) use ($request) {
                $query->where('month', $request->month);
            })
            ->when($request->filled('year'), function ($query) use ($request) {
                $query->where('year', $request->year);
            })
            ->orderBy('department_id', 'asc')
            ->get();

        return view('admin.department_kpi_list', [
            'departmentList' => $departmentList,
            'department' => $request->department,
            'department_kpis' => $department_kpis,
            'selectedMonth' => $request->filled('month') ? $request->month : $selectedMonth,
            'selectedYear' => $request->filled('year') ? $request->year : $selectedYear,
        ]);
    }
    public function addBulkDepartmentKpi(Request $request)
    {
        //Get parameters
        $departmentId = $request->query('department');
        $year = $request->query('year');
        $month = $request->query('month');

        // Loop through the rows submitted from the table to update or create
        if ($request->has('name')) {
            foreach ($request->name as $index => $name) {
                $kpiId = $request->id[$index] ?? null;

                // Find existing record by ID or create a new instance
                $mdrSetup = $kpiId ? DepartmentKpi::find($kpiId) : new DepartmentKpi;

                if (!$mdrSetup) {
                    $mdrSetup = new DepartmentKpi;
                }

                // Assign URL parameters
                $mdrSetup->department_id = $departmentId;
                $mdrSetup->year = $year;
                $mdrSetup->month = $month;

                // Assign field inputs from the row
                $mdrSetup->mdr_group_id = 1;
                $mdrSetup->name = $name;
                $mdrSetup->target = $request->target[$index] ?? null;
                $mdrSetup->weight = $request->weight[$index] ?? null;
                $mdrSetup->attachment_description = $request->attachment_description[$index] ?? null;
                
                if (isset($request->status[$index])) {
                    $mdrSetup->status = $request->status[$index];
                }

                $mdrSetup->save();
            }
        }

        Alert::success('Successfully Added')->persistent('Dismiss');
        return back();
    }

    public function duplicateBulkDepartmentKpi(Request $request)
    {
        $departmentId = $request->query('department');
        
        // Get the chosen destination month and year from the modal dropdowns
        $targetMonth = $request->input('target_month');
        $targetYear = $request->input('target_year');

        if ($request->has('selected_kpis') && is_array($request->selected_kpis)) {
            foreach ($request->selected_kpis as $kpiId) {
                $originalKpi = DepartmentKpi::find($kpiId);

                if ($originalKpi) {
                    $newKpi = new DepartmentKpi();

                    // Assign selected target period parameters
                    $newKpi->department_id = $departmentId;
                    $newKpi->year = $targetYear;
                    $newKpi->month = $targetMonth;

                    // Copy attributes from the original KPI
                    $newKpi->mdr_group_id = $originalKpi->mdr_group_id ?? 1;
                    $newKpi->name = $originalKpi->name;
                    $newKpi->target = $originalKpi->target;
                    $newKpi->weight = $originalKpi->weight;
                    $newKpi->attachment_description = $originalKpi->attachment_description;
                    $newKpi->status = $originalKpi->status ?? 'Active';

                    $newKpi->save();
                }
            }

            Alert::success('Successfully Duplicated')->persistent('Dismiss');
        } else {
            Alert::warning('No KPIs selected for duplication')->persistent('Dismiss');
        }

        return redirect()->route('kpi.view', [
            'department' => $departmentId,
            'month' => str_pad($targetMonth, 2, '0', STR_PAD_LEFT),
            'year' => $targetYear,
        ]);
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
        $mdrSetup->month = $request->month;
        $mdrSetup->year = $request->year;
        $mdrSetup->weight = $request->weight;
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
        $mdrSetup->month = $request->month;
        $mdrSetup->year = $request->year;
        $mdrSetup->weight = $request->weight;
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
        $duplicate->month = $request->month;
        $duplicate->year = $request->year;
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
