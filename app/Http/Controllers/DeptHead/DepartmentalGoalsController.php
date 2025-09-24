<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\MdrSetup;
use App\Approver\MdrSummary;
use App\Comment;
use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\MdrScore;
use App\DeptHead\MdrStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Signer\Rsa;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentalGoalsController extends Controller
{
    public function create(Request $request) {
        // dd($request->all());
        // $request->validate([
        //     'file[]' => 'array',
        //     'file.*.*' => 'max:1024'
        // ]);
        
        foreach($request->department_kpi_id as $key=>$department_kpi_id)
        {
            $departmentalGoals = new DepartmentalGoals;
            $departmentalGoals->department_kpi_id = $department_kpi_id;
            $departmentalGoals->department_id = auth()->user()->department_id;
            $departmentalGoals->target = $request->target[$key];
            $departmentalGoals->actual = $request->actual[$key];
            $departmentalGoals->remarks = $request->remarks[$key];
            $departmentalGoals->year = date('Y', strtotime($request->yearAndMonth));
            $departmentalGoals->month = date('m', strtotime($request->yearAndMonth));
            $departmentalGoals->deadline = generateSafeDeadline($request->yearAndMonth, auth()->user()->department->target_date);
            $departmentalGoals->save();

            $attachments = $request->file('file')[$key];
    
            foreach ($attachments as $attachment) {
                $name = time() . '_' . $attachment->getClientOriginalName();
                $attachment->move(public_path('departmental_goals_files'), $name);
                $file_path = "/departmental_goals_files/" . $name;
    
                $mdrAttachments = new Attachments;
                $mdrAttachments->department_id = auth()->user()->department_id;
                $mdrAttachments->file_path = $file_path;
                $mdrAttachments->departmental_goals_id = $departmentalGoals->id;
                $mdrAttachments->save();
            }
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    public function update(Request $request) {
        $request->validate([
            'file[]' => 'array',
            'file.*.*' => 'max:1024'
        ]);

        $departmentalGoals = DepartmentalGoals::findMany($request->department_goals_id);
        foreach($departmentalGoals as $deptKey=>$dptGoals)
        {
            $dptGoals->actual = $request->actual[$deptKey];
            $dptGoals->remarks = $request->remarks[$deptKey];
            $dptGoals->target = $request->target[$deptKey];
            $dptGoals->save();

            if ($request->has('file') && isset($request->file('file')[$deptKey]))
            {
                $attachments = $request->file('file')[$deptKey];
                
                foreach ($attachments as $attachment) {
                    $name = time() . '_' . $attachment->getClientOriginalName();
                    $attachment->move(public_path('departmental_goals_files'), $name);
                    $file_path = "/departmental_goals_files/" . $name;
        
                    $mdrAttachments = new Attachments;
                    $mdrAttachments->department_id = auth()->user()->department_id;
                    $mdrAttachments->file_path = $file_path;
                    $mdrAttachments->departmental_goals_id = $dptGoals->id;
                    $mdrAttachments->save();
                }
            }
        }

        Alert::success('Successfully Updated')->persistent('Dismiss');
        return back();
    }

    public function comments(Request $request)
    {
        $comments = new Comment();
        $comments->departmental_goals_id = $request->departmental_goals_id;
        $comments->comment = $request->comment;
        $comments->user_id = auth()->user()->id;
        $comments->save();

        Alert::success('Successfully Commented')->persistent('Dismiss');
        return back();
    }
}
