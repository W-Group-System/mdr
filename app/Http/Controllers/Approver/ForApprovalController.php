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
    public function index(Request $request) {

        $filter = $request->get('filter') ?? 'for-approval';
        $isAdmin = auth()->user()->role === 'Administrator';

        if ($isAdmin) {
            $mdrApprovers = MdrApprovers::with(['mdrRelationship', 'siblingApprovers'])
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $mdrApprovers = MdrApprovers::where('user_id', auth()->user()->id)
                ->whereHas('mdrRelationship', function ($query) {
                    $query->where('is_accepted', 'Accepted');
                })
                ->with(['mdrRelationship', 'siblingApprovers'])
                ->get();
        }
        $filteredMdrs = $mdrApprovers->filter(function ($mdr) use ($filter, $isAdmin) {
            $approvers = $mdr->siblingApprovers;
            $lastApprover = $approvers->sortByDesc('level')->first();

            $forApproval = false;
            $returned    = false;
            $approved    = false;

            if ($isAdmin) {
                $isPending   = $approvers->where('status', 'Pending')->isNotEmpty();
                $hasReturned = $approvers->where('status', 'Returned')->isNotEmpty();
                $forApproval = !$hasReturned && $isPending;
            } else {
                $isPending   = $approvers->where('user_id', auth()->id())
                                        ->where('status', 'Pending')->isNotEmpty();
                $hasReturned = $approvers->where('status', 'Returned')->isNotEmpty();
                $forApproval = !$hasReturned && $isPending;
            }

            if ($isAdmin) {
                $returned = $approvers->where('status', 'Returned')->isNotEmpty();
            } else {
                $isReturned  = $approvers->where('user_id', auth()->id())
                                        ->where('status', 'Pending')->isNotEmpty();
                $hasReturned = $approvers->where('status', 'Returned')->isNotEmpty();
                $returned    = $isReturned && $hasReturned;
            }

            if ($isAdmin) {
                $approved = $lastApprover && $lastApprover->status === 'Approved';
            } else {
                $approved = $mdr->status === 'Approved' && $mdr->user_id === auth()->id();
            }

            if ($filter === 'for-approval') return $forApproval;
            if ($filter === 'returned')     return $returned;
            if ($filter === 'approved')     return $approved;

            if ($filter === 'all') {
                return $forApproval || $returned || $approved;
            }

            return false;
        });

        return view('approver.for-approval-mdr', 
            array(
                'mdrApprovers' => $mdrApprovers,
                'filteredMdrs' => $filteredMdrs,
                'filter'   => $filter,
            )
        );
    }

    public function forAcceptance() {

        $mdr = Mdr::where('is_accepted', null)
                    ->where('status','!=', 'Returned')
                    ->orderBy('id', 'desc')->get();

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
