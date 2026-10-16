<?php

namespace App\Http\Controllers;

use App\Admin\Company;
use App\Admin\Department;
use App\Admin\DepartmentApprovers;
use App\Admin\DepartmentReviewer;
use App\DeptHead\Mdr;
use App\DeptHead\MdrApprovers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentApproverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department_approvers = DepartmentApprovers::with('user')->orderBy('status_level','asc')->get();
        $users = User::where('status','Active')->get();
        $companies = Company::get();
        return view('admin.department_approvers', 
            array(
                'department_approvers' => $department_approvers,
                'users' => $users,
                'companies' => $companies,
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'approver' => 'unique:department_approvers,user_id',
        ]);
        if ($request->level != 1) {
            $request->validate([
                'level' => [
                    Rule::unique('department_approvers', 'status_level')
                        ->where(function ($query) {
                            return $query->where('status', 'Active');
                        }),
                ],
            ]);
        }
        // $this->validate($request, [
        //     // 'level' => 'unique:department_approvers,status_level',
        //     'level' => [
        //         Rule::unique('department_approvers', 'status_level')
        //             ->where(function ($query) {
        //                 return $query->where('status', 'Active');
        //             }),
        //     ],
        //     'approver' => 'unique:department_approvers,user_id'
        // ]);

        $department_approvers = new DepartmentApprovers;
        $department_approvers->user_id = $request->approver;
        $department_approvers->status_level = $request->level;
        $department_approvers->status = 'Active';
        if ($request->level == 1) {
            $department_approvers->company_id = !empty($request->companies)
                ? implode(',', $request->companies)
                : null;
        } else {
            $department_approvers->company_id = null;
        }
        $department_approvers->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->validate($request, [
        //     'level' => [
        //         Rule::unique('department_approvers', 'status_level')
        //             ->where(function ($query) {
        //                 return $query->where('status', 'Active');
        //             }),
        //     ],
        //     'approver' => 'unique:department_approvers,user_id',
        //     'companies' => 'required_if:level,1'
        // ]);
        // dd($request->all(),$id);
        $department_approvers = DepartmentApprovers::findOrFail($id);

        $request->validate([
            'approver' => [
                Rule::unique('department_approvers', 'user_id')
                    ->ignore($department_approvers->id),
            ],
            'companies' => 'required_if:level,1|array',
        ]);
        if ($request->level != 1) {
            $request->validate([
                'level' => [
                    Rule::unique('department_approvers', 'status_level')
                        ->ignore($department_approvers->id)
                        ->where(function ($query) {
                            return $query->where('status', 'Active');
                        }),
                ],
            ]);
        }

        
        $department_approvers->user_id = $request->approver;
        $department_approvers->status_level = $request->level;
        if ($request->level == 1) {
            $department_approvers->company_id = !empty($request->companies)
                ? implode(',', $request->companies)
                : null;
        } else {
            $department_approvers->company_id = null;
        }
        $department_approvers->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function deactivate($id)
    {
        $department_approvers = DepartmentApprovers::findOrFail($id);
        $department_approvers->status = 'Inactive';
        $department_approvers->save();

        ## Delete as approver if deactivated 
        // $mdr_approvers = MdrApprovers::where('user_id', $department_approvers->user_id)
        //                 ->whereHas('mdrRelationship', function($query) {
        //                     $query->where('status', 'Pending')
        //                           ->orWhere('status', 'Returned');
        //                 })
        // ->get();
        // foreach ($mdr_approvers as $mdr_approver) {
        //     $mdr_id = $mdr_approver->mdr_id;

        //     $mdr_approver->delete();

        //     $remaining_approvers = MdrApprovers::where('mdr_id', $mdr_id)
        //                             ->orderBy('level', 'asc')
        //                             ->get();

        //     foreach ($remaining_approvers as $index => $ra) {
        //         $ra->level = $index + 1; 
        //         if ($index == 0) {
        //             $ra->status = 'Pending'; 
        //         } else {
        //             $ra->status = 'Waiting';
        //         }
        //         $ra->save();
        //     }
        // }
        Alert::success('Successfully Deactivated')->persistent('Dismiss');
        return back();
    }

    public function activate($id)
    {
        $department_approver = DepartmentApprovers::findOrFail($id);
        // $existingActive = DepartmentApprovers::where('status_level', $department_approvers->status_level)
        //     ->where('status', 'Active')
        //     ->where('id', '!=', $department_approvers->id)
        //     ->exists();
        // if ($existingActive) {
        //     Alert::error('Cannot activate. An active approver already exists for Level ' . $department_approvers->status_level)
        //         ->persistent('Dismiss');

        //     return back();
        // }
        if ($department_approver->status_level != 1) {
            $existingActive = DepartmentApprovers::where('status_level', $department_approver->status_level)
                ->where('status', 'Active')
                ->where('id', '!=', $department_approver->id)
                ->exists();

            if ($existingActive) {
                Alert::error(
                    'Cannot activate. An active approver already exists for Level ' . $department_approver->status_level
                )->persistent('Dismiss');

                return back();
            }
        }

        $department_approver->status = 'Active';
        $department_approver->save();

        Alert::success('Successfully Activated')->persistent('Dismiss');
        return back();
    }

    public function reviewers()
    {
        $department_reviewers = DepartmentReviewer::with('user')->get();
        $users = User::where('status','Active')->get();
        $departments = Department::get();
        return view('admin.department_reviewers', 
            array(
                'department_reviewers' => $department_reviewers,
                'users' => $users,
                'departments' => $departments,
            )
        );
    }
    public function storereviewer(Request $request)
    {
        $request->validate([
            'reviewer' => 'unique:department_reviewers,user_id',
        ]);

        $department_reviewers = new DepartmentReviewer;
        $department_reviewers->user_id = $request->reviewer;
        $department_reviewers->status = 'Active';
        $department_reviewers->department_id = !empty($request->departments)
                ? implode(',', $request->departments)
                : null;
        $department_reviewers->save();

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function updatereviewer(Request $request, $id)
    {
        $department_reviewer = DepartmentReviewer::findOrFail($id);

        $request->validate([
            'reviewer' => [
                Rule::unique('department_reviewers', 'user_id')
                    ->ignore($department_reviewer->id),
            ],
        ]);

        
        $department_reviewer->user_id = $request->reviewer;
        $department_reviewer->department_id = !empty($request->departments)
                ? implode(',', $request->departments)
                : null;
        $department_reviewer->save();

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function for_review() {

        $reviewer = DepartmentReviewer::where('user_id', auth()->id())
            ->where('status', 'Active')
            ->first();

        $mdrs = Mdr::whereNull('is_accepted')
            ->where('status', ['Reviewer']);
        

        $departmentIds = $reviewer->department_id
            ? explode(',', $reviewer->department_id)
            : [];

        $mdrs->whereHas('departments', function ($query) use ($departmentIds) {
            $query->whereIn('department_id', $departmentIds);
        });

        $mdr = $mdrs->orderByDesc('id')->get();

        return view('reviewer.for_review', 
            array(
                'mdrs' => $mdr,
            )
        );
    }
}
