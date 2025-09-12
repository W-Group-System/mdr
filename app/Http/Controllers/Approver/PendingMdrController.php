<?php

namespace App\Http\Controllers\Approver;

use App\Admin\DepartmentApprovers;
use App\Admin\Department;
use App\Approver\MdrSummary;
use App\DeptHead\Innovation;
use App\DeptHead\Mdr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\App;

class PendingMdrController extends Controller
{
    public function index(Request $request) {

        $filter = $request->get('filter', 'all');
        $mdrs = Mdr::where('is_accepted', 'Accepted')->get();
        
        $filteredMdrs = $mdrs->filter(function ($mdr) use ($filter) {
            $approvers = $mdr->mdrApprover;
            $lastApprover = $approvers->sortByDesc('level')->first();
            $firstApprover = $approvers->sortBy('level')->first(); 

            if ($filter === 'pending') {
                return $lastApprover
                    && $lastApprover->status !== 'Approved'
                    && (
                        ($firstApprover && $firstApprover->status !== 'Pending')

                        || ($firstApprover && $firstApprover->status === 'Pending'
                            && !$approvers->where('status', 'Returned')->isNotEmpty())
                    );
            }

            if ($filter === 'returned') {
                return ($firstApprover && $firstApprover->status === 'Pending')
                    && $approvers->where('status', 'Returned')->isNotEmpty();
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

    public function printMdr(Request $request)
    {
        // dd($request->all());
        $mdrs = Mdr::with('departments.company', 'departmentalGoals.departmentKpi','departments.user')->where('year', $request->year)->where('month', $request->month)->where('department_id', $request->department)->first();
        $innovations = Innovation::where('year', $request->year)->where('month', $request->month)->where('department_id', $request->department)->first();
        $users = User::get();
        
        $data = [];
        $data['mdr'] = $mdrs;
        $data['innovations'] = $innovations;
        $data['users'] = $users;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('approver.print_mdr', ['data' => $data])
            ->setPaper('a4', 'portrait')->setWarnings(false);
            
        return $pdf->stream();
    }
}
