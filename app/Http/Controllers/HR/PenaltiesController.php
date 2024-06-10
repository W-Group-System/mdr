<?php

namespace App\Http\Controllers\HR;

use App\Approver\MdrSummary;
use App\HR\NteAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NteNotificationForDeptHead;
use App\User;
use RealRashid\SweetAlert\Facades\Alert;

class PenaltiesController extends Controller
{
    public function index(Request $request) {
        $mdrSummary = MdrSummary::with([
                'departments.user',
                'nteAttachments.users',
            ])
            ->where('rate', '<', 2.99)
            ->where('final_approved', 1)
            ->whereNull('penalty_status')
            ->get();

        return view('hr.penalties', 
            array(
                'yearAndMonth' => !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m'),
                'mdrSummary' => $mdrSummary
            )
        );
    }

    public function uploadNte(Request $request, $id) {
        if ($request->hasFile('files')) {
            $mdrSummary = MdrSummary::with('nteAttachments')->findOrFail($id);

            $files = $request->file('files');
            $fileName = time().'-'.$files->getClientOriginalName();
            $files->move(public_path('nte_attachments'), $fileName);

            if (empty($mdrSummary->nteAttachments)) {
                $nteFile = new NteAttachments;
                $nteFile->department_id = $request->departmentId;
                $nteFile->mdr_summary_id = $request->mdrSummaryId;
                $nteFile->user_id = auth()->user()->id;
                $nteFile->year = date('Y', strtotime($request->yearAndMonth));
                $nteFile->month = date('m', strtotime($request->yearAndMonth));
                $nteFile->filepath = 'nte_attachments/'.$fileName;
                $nteFile->filename = $fileName;
                $nteFile->save();
            }
            else {
                $nteFile = NteAttachments::findOrFail($mdrSummary->nteAttachments->id);
                $nteFile->user_id = auth()->user()->id;
                $nteFile->year = date('Y', strtotime($request->yearAndMonth));
                $nteFile->month = date('m', strtotime($request->yearAndMonth));
                $nteFile->filepath = 'nte_attachments/'.$fileName;
                $nteFile->filename = $fileName;
                $nteFile->save();
            }

            $user = User::where('department_id', $request->departmentId)
                ->where('role', "Department Head")
                ->first();

            $user->notify(new NteNotificationForDeptHead($nteFile->filepath, $user->name, $request->yearAndMonth));
    
            Alert::success('SUCCESS', 'Uploaded successfully.');
            return back();
        }
        else {
            Alert::success('ERROR', 'You are not selecting a file.');
            return back();
        }
        
    }

    public function deleteNte($id) {
        
        $nteFileData = NteAttachments::findOrFail($id);
        
        if (!empty($nteFileData)) {
            $nteFileData->delete();

            Alert::success('SUCCESS', 'Successfully Deleted.');
            return back();
        }
    }

    public function nteStatus(Request $request, $id) {
        $nteAttachments = NteAttachments::findOrFail($id);
        $nteAttachments->status = $request->status;
        $nteAttachments->acknowledge_by = $request->acknowledge_by;
        $nteAttachments->save();

        if ($request->status == "For NOD") {
            $mdrSummary = MdrSummary::findOrFail($request->mdr_summary_id);
            $mdrSummary->penalty_status = "For NOD";
            $mdrSummary->save();
        }
        
        Alert::success('SUCCESS', 'Successfully Submitted.');
        return back();
    }
}
