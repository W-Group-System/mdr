<?php

namespace App\Http\Controllers\Approver;

use App\Admin\DepartmentApprovers;
use App\Approver\MdrSummary;
use App\DeptHead\MdrApprovers;
use App\DeptHead\MdrStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForApprovalController extends Controller
{
    public function index() {

        $mdrApprovers = MdrApprovers::with('mdrSummary')->where('user_id', auth()->user()->id)->get();

        if (auth()->user()->role == "Administrator")
        {
            $mdrApprovers = MdrApprovers::orderBy('id', 'desc')->get();
        }

        return view('approver.for-approval-mdr', 
            array(
                'mdrApprovers' => $mdrApprovers,
            )
        );
    }
}
