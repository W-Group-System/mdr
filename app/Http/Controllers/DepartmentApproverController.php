<?php

namespace App\Http\Controllers;

use App\Admin\DepartmentApprovers;
use App\User;
use Illuminate\Http\Request;
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

        return view('admin.department_approvers', 
            array(
                'department_approvers' => $department_approvers,
                'users' => $users
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
        $this->validate($request, [
            'level' => 'unique:department_approvers,status_level',
            'approver' => 'unique:department_approvers,user_id'
        ]);

        $department_approvers = new DepartmentApprovers;
        $department_approvers->user_id = $request->approver;
        $department_approvers->status_level = $request->level;
        $department_approvers->status = 'Active';
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
        // dd($request->all(),$id);
        $department_approvers = DepartmentApprovers::findOrFail($id);
        $department_approvers->user_id = $request->approver;
        $department_approvers->status_level = $request->level;
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

        Alert::success('Successfully Deactivated')->persistent('Dismiss');
        return back();
    }

    public function activate($id)
    {
        $department_approvers = DepartmentApprovers::findOrFail($id);
        $department_approvers->status = 'Active';
        $department_approvers->save();

        Alert::success('Successfully Activated')->persistent('Dismiss');
        return back();
    }
}
