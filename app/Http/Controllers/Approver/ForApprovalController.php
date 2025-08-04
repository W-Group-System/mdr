<?php

namespace App\Http\Controllers\Approver;

use App\Admin\DepartmentApprovers;
use App\Approver\MdrSummary;
use App\DeptHead\Mdr;
use App\DeptHead\MdrApprovers;
use App\DeptHead\MdrStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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

    public function forAcceptance() {

        $mdr = Mdr::orderBy('id', 'desc')->get();

        return view('approver.for_acceptance', 
            array(
                'mdrs' => $mdr,
            )
        );
    }
    public function timelinessApproval() {

        $mdr = Mdr::orderBy('id', 'desc')
        ->where('timeliness_approval', 'Yes')->get();

        return view('approver.timeliness_approval', 
            array(
                'mdrs' => $mdr,
            )
        );
    }
    public function approveTimeliness(Request $request, $id) {
        $timeliness = Mdr::findOrFail($id);
        $timeliness->timeliness_approval = "Approved";
        $timeliness->timeliness = 0.50;
        $timeliness->save();
        
        return response()->json([
            'message' => 'Successfully Approved',
            'redirect' => url('timeliness_approval') 
        ]);
    }
    public function disapproveTimeliness(Request $request, $id) {
        $timeliness = Mdr::findOrFail($id);
        $timeliness->timeliness_approval = "Disapproved";
        $timeliness->timeliness = 0.00;
        $timeliness->disapproved_timeliness = $request->remarks;;
        $timeliness->save();
        
        return response()->json([
            'message' => 'Successfully Dispproved',
            'redirect' => url('timeliness_approval') 
        ]);
    }
}
