<?php

namespace App\Http\Controllers\Approver;

use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use App\Approver\MdrSummary;
use App\DeptHead\Mdr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PendingMdrController extends Controller
{
    public function index(Request $request) {

        $filter = $request->get('filter', 'all');
        $mdrs = Mdr::where('is_accepted', 'Accepted')->get();
        
        $filteredMdrs = $mdrs->filter(function ($mdr) use ($filter) {
            $approvers = $mdr->mdrApprover;
            $lastApprover = $approvers->sortByDesc('level')->first();

            if ($filter === 'pending') {
                return !$approvers->where('status', 'Returned')->isNotEmpty()
                    && $lastApprover
                    && $lastApprover->status !== 'Approved';
            }

            if ($filter === 'returned') {
                return $approvers->where('status', 'Returned')->isNotEmpty();
            }

            if ($filter === 'approved') {
                return $lastApprover && $lastApprover->status === 'Approved';
            }

            return true; 
        });
        return view('approver.pending-mdr', [
            'mdrs' => $mdrs,
            'filteredMdrs' => $filteredMdrs,
            'filter' => $filter
        ]);
    }
}
