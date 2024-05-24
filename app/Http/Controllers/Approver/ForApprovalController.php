<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Approve;
use App\Approver\MdrSummary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForApprovalController extends Controller
{
    public function index() {

        $approverList = Approve::where('user_id', auth()->user()->id)->get();

        foreach($approverList as $approve) {

            $mdrSummary = MdrSummary::with([
                'mdrStatus' => function($q) {
                    $q->where('status', 0);
                }
            ])
            ->where('status_level', $approve->status_level)
            ->where('final_approved', 0)
            ->get();
        }

        return view('approver.for-approval-mdr', array('mdrSummary' => $mdrSummary));
    }
}
