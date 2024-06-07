<?php

namespace App\Http\Controllers\DeptHead;

use App\Approver\MdrSummary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentNodController extends Controller
{
    public function index(Request $request) {
        $mdrSummary = MdrSummary::with([
            'departments.user',
            'nteAttachments.users',
        ])
        ->where('rate', '<', 2.99)
        ->where('department_id', auth()->user()->department_id)
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
        
        return view('dept-head.department-for-nod',
            array(
                'yearAndMonth' => !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m'),
                'mdrSummary' => $mdrSummary
            )
        );
    }
}
