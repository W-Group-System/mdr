<?php

namespace App\Http\Controllers\Approver;

use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use App\Approver\MdrSummary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PendingMdrController extends Controller
{
    public function index() {

        $mdrSummary = MdrSummary::with('mdrStatus')
            ->where('status_level', '<>', 0)
            ->orderBy('final_approved', 'ASC')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();
        
        return view('approver.pending-mdr',
            array(
                'mdrSummary' => $mdrSummary
            )
        );
    }
}
