<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function PHPSTORM_META\map;

class HistoryMdrController extends Controller
{
    public function index(Request $request) {
        $departmentList = Department::get();

        $departmentData = Department::with([
            'departmentKpi',
            'kpi_scores' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('final_approved', 1);
            },
            'departmentalGoals' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('final_approved', 1);
            },
            'innovation' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('final_approved', 1);
            },
            'process_development' => function($q)use($request) {
                $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->where('final_approved', 1);
            },
        ])
        ->where('id', $request->department)
        ->first();

        return view('approver.history-mdr',
            array(
                'departmentList' => $departmentList,
                'department' => $request->department,
                'yearAndMonth' => $request->yearAndMonth,
                'data' => $departmentData
            )
        );
    }
}
