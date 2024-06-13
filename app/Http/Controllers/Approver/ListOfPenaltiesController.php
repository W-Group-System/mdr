<?php

namespace App\Http\Controllers\Approver;

use App\Approver\MdrSummary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListOfPenaltiesController extends Controller
{
    public function index() {
        if (auth()->user()->role == "Approver" || auth()->user()->role == "Human Resources") {
            $mdrSummary = MdrSummary::with([
                'departments',
                'nteAttachments',
                'nodAttachments',
                'pipAttachments'
            ])
            ->where('rate', '<', 2.99)
            ->where('final_approved', 1)
            ->whereNotNull('penalty_status')
            ->get();
        }

        if (auth()->user()->role == "Department Head") {
            $mdrSummary = MdrSummary::with([
                'departments',
                'nteAttachments',
                'nodAttachments',
                'pipAttachments'
            ])
            ->where('rate', '<', 2.99)
            ->where('final_approved', 1)
            ->where('department_id', auth()->user()->department_id)
            ->whereNotNull('penalty_status')
            ->get();
        }
        
        return view('approver.list-of-penalties',
            array(
                'mdrSummary' => $mdrSummary
            )
        );
    }
}
