<?php

namespace App\Http\Controllers\HR;

use App\Approver\MdrSummary;
use App\HR\PipAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ForPIPController extends Controller
{
    public function index() {
        if(auth()->user()->role=="Human Resources") {
            $mdrSummary = MdrSummary::with('pipAttachments')
                ->where('rate', '<', 2.99)
                ->where('final_approved', 1)
                ->where('penalty_status', "For PIP")
                ->get();
        }

        if (auth()->user()->role == "Department Head") {
            $mdrSummary = MdrSummary::with('pipAttachments')
                ->where('rate', '<', 2.99)
                ->where('final_approved', 1)
                ->where('penalty_status', "For PIP")
                ->where('department_id', auth()->user()->department_id)
                ->get();
        }
        
        return view('hr.for-pip', array('mdrSummary' => $mdrSummary));
    }
    
    public function uploadPip(Request $request, $id) {
        $request->validate([
            'files' => 'max:2048'
        ]);

        if ($request->hasFile('files')) {
            $mdrSummary = MdrSummary::with('pipAttachments')->findOrFail($id);
            
            $files = $request->file('files');
            $fileName = time().'-'.$files->getClientOriginalName();
            $files->move(public_path('pip_attachments'), $fileName);

            if (empty($mdrSummary->pipAttachments)) {
                $pipFile = new PipAttachments;
                $pipFile->department_id = $request->departmentId;
                $pipFile->mdr_summary_id = $request->mdrSummaryId;
                $pipFile->user_id = auth()->user()->id;
                $pipFile->year = date('Y', strtotime($request->yearAndMonth));
                $pipFile->month = date('m', strtotime($request->yearAndMonth));
                $pipFile->filepath = 'pip_attachments/'.$fileName;
                $pipFile->filename = $fileName;
                $pipFile->save();
            }
            else {
                $pipFile = PipAttachments::findOrFail($mdrSummary->pipAttachments->id);
                $pipFile->user_id = auth()->user()->id;
                $pipFile->year = date('Y', strtotime($request->yearAndMonth));
                $pipFile->month = date('m', strtotime($request->yearAndMonth));
                $pipFile->filepath = 'pip_attachments/'.$fileName;
                $pipFile->filename = $fileName;
                $pipFile->save();
            }

            Alert::success('SUCCESS', 'Successfully Uploaded.');
            return back();
        }
        else {
            Alert::success('ERROR', 'You are not selecting a file.');
            return back();
        }
    }

    public function pipStatus(Request $request, $id) {
        $nteAttachments = PipAttachments::findOrFail($id);
        // $nteAttachments->status = $request->status;
        $nteAttachments->acknowledge_by = $request->acknowledge_by;
        $nteAttachments->save();

        // if ($request->status == "For PIP") {
        //     $mdrSummary = MdrSummary::findOrFail($request->mdr_summary_id);
        //     $mdrSummary->penalty_status = "For PIP";
        //     $mdrSummary->save();
        // }
        
        Alert::success('SUCCESS', 'Successfully Submitted.');
        return back();
    }
}
