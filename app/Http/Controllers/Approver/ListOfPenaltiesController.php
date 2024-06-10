<?php

namespace App\Http\Controllers\Approver;

use App\Approver\MdrSummary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ListOfPenaltiesController extends Controller
{
    public function index() {
        $mdrSummary = MdrSummary::with([
            'departments.user',
            'nodAttachments.users'
        ])
        ->where('rate', '<', 2.99)
        ->where('final_approved', 1)
        ->get();
        
        return view('approver.list-of-penalties',
            array(
                'mdrSummary' => $mdrSummary
            )
        );
    }
}
