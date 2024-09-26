<?php

namespace App\Http\Controllers\HR;

use App\Admin\DepartmentApprovers;
use App\Approver\MdrSummary;
use App\HR\NodAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ApproverNotification;
use App\Notifications\NteNotificationForDeptHead;
use App\User;
use RealRashid\SweetAlert\Facades\Alert;

class ForNodController extends Controller
{
    public function index() {
        if(auth()->user()->role == "Human Resources") {
            $mdrSummary = MdrSummary::with([
                'departments.user',
                'nodAttachments.users'
            ])
            // ->where('rate', '<', 2.99)
            ->where('penalty_status', "For NOD")
            ->get();
        }

        if(auth()->user()->role == "Department Head" || auth()->user()->role == "Users") {
            $mdrSummary = MdrSummary::with([
                'departments.user',
                'nodAttachments.users'
            ])
            ->whereHas('nodAttachments')
            ->where('penalty_status', "For NOD")
            ->where('department_id', auth()->user()->department_id)
            ->get();
        }

        return view('hr.for-nod',
            array(
                'mdrSummary' => $mdrSummary
            )
        );
    }

    public function uploadNod(Request $request, $id) {
        // dd($request->all());
        $request->validate([
            'files' => 'max:1024'
        ]);

        $file = $request->file('files');
        $name = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('nod_attachments'),$name);

        $nodFile = new NodAttachments;
        $nodFile->department_id = $request->departmentId;
        $nodFile->mdr_summary_id = $request->mdrSummaryId;
        $nodFile->user_id = auth()->user()->id;
        $nodFile->yearAndMonth = date('Y-m', strtotime($request->yearAndMonth));
        $nodFile->filepath = '/nod_attachments/'.$name;
        $nodFile->save();

        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();

        // if ($request->hasFile('files')) {
        //     $mdrSummary = MdrSummary::with('nodAttachments')->findOrFail($id);
            
        //     $files = $request->file('files');
        //     $fileName = time().'-'.$files->getClientOriginalName();
        //     $files->move(public_path('nod_attachments'), $fileName);

        //     if (empty($mdrSummary->nodAttachments)) {
        //         $nodFile = new NodAttachments;
        //         $nodFile->department_id = $request->departmentId;
        //         $nodFile->mdr_summary_id = $request->mdrSummaryId;
        //         $nodFile->user_id = auth()->user()->id;
        //         $nodFile->year = date('Y', strtotime($request->yearAndMonth));
        //         $nodFile->month = date('m', strtotime($request->yearAndMonth));
        //         $nodFile->filepath = 'nod_attachments/'.$fileName;
        //         $nodFile->filename = $fileName;
        //         $nodFile->save();
        //     }
        //     else {
        //         $nodFile = NodAttachments::findOrFail($mdrSummary->nodAttachments->id);
        //         $nodFile->user_id = auth()->user()->id;
        //         $nodFile->year = date('Y', strtotime($request->yearAndMonth));
        //         $nodFile->month = date('m', strtotime($request->yearAndMonth));
        //         $nodFile->filepath = 'nod_attachments/'.$fileName;
        //         $nodFile->filename = $fileName;
        //         $nodFile->save();
        //     }

        //     if (auth()->user()->role == "Human Resources") {
        //         $departmentApprovers = DepartmentApprovers::where('department_id', $request->departmentId)->where('status_level', 1)->first();
        //         $approver = User::where('id', $departmentApprovers->user_id)->first();
        //         $hr = User::where('id', $nodFile->user_id)->first();
        //         $typeOfPenalties = "NOD File";
        //         $approver->notify(new ApproverNotification($approver->name, $request->yearAndMonth, $hr->name, $departmentApprovers->department->name, $typeOfPenalties));
        //     }

        //     Alert::success('SUCCESS', 'Successfully Uploaded.');
        //     return back();
        // }
        // else {
        //     Alert::success('ERROR', 'You are not selecting a file.');
        //     return back();
        // }
        
    }

    public function nodStatus(Request $request, $id) {
        if (auth()->user()->role == "Department Head")
        {
            $file = $request->file('files');
            $name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('nod_attachments'),$name);
            $filepath = '/nod_attachments/'.$name;

            $nod_attachments = NodAttachments::findOrFail($id);
            $nod_attachments->filepath = $filepath;
            $nod_attachments->save();
        }
        else
        {
            $nod_attachments = NodAttachments::findOrFail($id);
            $nod_attachments->status = $request->status;
            $nod_attachments->acknowledge_by = $request->acknowledge_by;
            $nod_attachments->save();
    
            $mdrSummary = MdrSummary::findOrFail($request->mdr_summary_id);
            $mdrSummary->penalty_status = $request->status;
            $mdrSummary->save();
        }
        
        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return back();
    }
}
