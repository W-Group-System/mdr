<?php

namespace App\Http\Controllers\HR;

use App\Approver\MdrSummary;
use App\HR\NodAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ForNodController extends Controller
{
    public function index() {
        if(auth()->user()->role == "Human Resources") {
            $mdrSummary = MdrSummary::with([
                'departments.user',
                'nodAttachments.users'
            ])
            ->where('rate', '<', 2.99)
            ->where('final_approved', 1)
            ->where('penalty_status', "For NOD")
            ->get();
        }

        if(auth()->user()->role == "Department Head") {
            $mdrSummary = MdrSummary::with([
                'departments.user',
                'nodAttachments.users'
            ])
            ->where('rate', '<', 2.99)
            ->where('final_approved', 1)
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
        if ($request->hasFile('files')) {
            $mdrSummary = MdrSummary::with('nodAttachments')->findOrFail($id);
            
            $files = $request->file('files');
            $fileName = time().'-'.$files->getClientOriginalName();
            $files->move(public_path('nod_attachments'), $fileName);

            if (empty($mdrSummary->nodAttachments)) {
                $nteFile = new NodAttachments;
                $nteFile->department_id = $request->departmentId;
                $nteFile->mdr_summary_id = $request->mdrSummaryId;
                $nteFile->user_id = auth()->user()->id;
                $nteFile->year = date('Y', strtotime($request->yearAndMonth));
                $nteFile->month = date('m', strtotime($request->yearAndMonth));
                $nteFile->filepath = 'nod_attachments/'.$fileName;
                $nteFile->filename = $fileName;
                $nteFile->save();
            }
            else {
                $nteFile = NodAttachments::findOrFail($mdrSummary->nodAttachments->id);
                $nteFile->user_id = auth()->user()->id;
                $nteFile->year = date('Y', strtotime($request->yearAndMonth));
                $nteFile->month = date('m', strtotime($request->yearAndMonth));
                $nteFile->filepath = 'nod_attachments/'.$fileName;
                $nteFile->filename = $fileName;
                $nteFile->save();
            }

            Alert::success('SUCCESS', 'Successfully Uploaded.');
            return back();
        }
        else {
            Alert::success('ERROR', 'You are not selecting a file.');
            return back();
        }
        
    }

    public function nodStatus(Request $request, $id) {
        $nteAttachments = NodAttachments::findOrFail($id);
        $nteAttachments->status = $request->status;
        $nteAttachments->acknowledge_by = $request->acknowledge_by;
        $nteAttachments->save();

        if ($request->status == "For PIP") {
            $mdrSummary = MdrSummary::findOrFail($request->mdr_summary_id);
            $mdrSummary->penalty_status = "For PIP";
            $mdrSummary->save();
        }
        
        Alert::success('SUCCESS', 'Successfully Submitted.');
        return back();
    }
}
