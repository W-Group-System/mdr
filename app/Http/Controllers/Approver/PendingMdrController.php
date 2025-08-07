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

        $mdrSummary = MdrSummary::orderBy('yearAndMonth', 'DESC')->get();
        
        return view('approver.pending-mdr',
            array(
                'mdrSummary' => $mdrSummary
            )
        );
    }
}
