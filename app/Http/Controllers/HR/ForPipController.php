<?php

namespace App\Http\Controllers\HR;

use App\Admin\DepartmentApprovers;
use App\Approver\MdrSummary;
use App\HR\PipAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\ApproverNotification;
use App\User;
use RealRashid\SweetAlert\Facades\Alert;

class ForPIPController extends Controller
{
    public function index() {
        if(auth()->user()->role=="Human Resources") {
            $mdrSummary = MdrSummary::with('pipAttachments')
                // ->where('rate', '<', 2.99)
                ->where('penalty_status', "For PIP")
                ->get();
        }

        if (auth()->user()->role == "Department Head" || auth()->user()->role == "Users") {
            $mdrSummary = MdrSummary::with('pipAttachments')
                // ->where('rate', '<', 2.99)
                ->whereHas('pipAttachments')
                ->where('penalty_status', "For PIP")
                ->where('department_id', auth()->user()->department_id)
                ->get();
        }
        
        return view('hr.for-pip', array('mdrSummary' => $mdrSummary));
    }
    
    public function uploadPip(Request $request, $id) {
        $request->validate([
            'files' => 'max:1042'
        ]);

        $files = $request->file('files');
        $fileName = time().'_'.$files->getClientOriginalName();
        $files->move(public_path('pip_attachments'), $fileName);

        $pipFile = new PipAttachments;
        $pipFile->department_id = $request->departmentId;
        $pipFile->mdr_summary_id = $request->mdrSummaryId;
        $pipFile->user_id = auth()->user()->id;
        $pipFile->yearAndMonth = date('Y-m', strtotime($request->yearAndMonth));
        $pipFile->filepath = '/pip_attachments/'.$fileName;
        $pipFile->save();
        
        Alert::success('Successfully Uploaded')->persistent('Dismiss');
        return back();

        // if ($request->hasFile('files')) {
        //     $mdrSummary = MdrSummary::with('pipAttachments')->findOrFail($id);
            
            
        //     else {
        //         $pipFile = PipAttachments::findOrFail($mdrSummary->pipAttachments->id);
        //         $pipFile->user_id = auth()->user()->id;
        //         $pipFile->year = date('Y', strtotime($request->yearAndMonth));
        //         $pipFile->month = date('m', strtotime($request->yearAndMonth));
        //         $pipFile->filepath = 'pip_attachments/'.$fileName;
        //         $pipFile->filename = $fileName;
        //         $pipFile->save();
        //     }

        //     if (auth()->user()->role == "Human Resources") {
        //         $departmentApprovers = DepartmentApprovers::where('department_id', $request->departmentId)->where('status_level', 1)->first();
        //         $approver = User::where('id', $departmentApprovers->user_id)->first();
        //         $hr = User::where('id', $pipFile->user_id)->first();
        //         $typeOfPenalties = "PIP File";
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

    public function pipStatus(Request $request, $id) {
        
        if (auth()->user()->role == "Department Head")
        {
            $files = $request->file('files');
            $fileName = time().'_'.$files->getClientOriginalName();
            $files->move(public_path('pip_attachments'), $fileName);

            $pipFile = PipAttachments::findOrFail($id);
            $pipFile->filepath = '/pip_attachments/'.$fileName;
            $pipFile->save();
        }
        else
        {
            $nteAttachments = PipAttachments::findOrFail($id);
            $nteAttachments->acknowledge_by = $request->acknowledge_by;
            $nteAttachments->save();
        }
        
        Alert::success('Successfully Submitted')->persistent('Dismiss');
        return back();
    }
}
