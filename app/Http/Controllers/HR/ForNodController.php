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
                $nodFile = new NodAttachments;
                $nodFile->department_id = $request->departmentId;
                $nodFile->mdr_summary_id = $request->mdrSummaryId;
                $nodFile->user_id = auth()->user()->id;
                $nodFile->year = date('Y', strtotime($request->yearAndMonth));
                $nodFile->month = date('m', strtotime($request->yearAndMonth));
                $nodFile->filepath = 'nod_attachments/'.$fileName;
                $nodFile->filename = $fileName;
                $nodFile->save();
            }
            else {
                $nodFile = NodAttachments::findOrFail($mdrSummary->nodAttachments->id);
                $nodFile->user_id = auth()->user()->id;
                $nodFile->year = date('Y', strtotime($request->yearAndMonth));
                $nodFile->month = date('m', strtotime($request->yearAndMonth));
                $nodFile->filepath = 'nod_attachments/'.$fileName;
                $nodFile->filename = $fileName;
                $nodFile->save();
            }

            if (auth()->user()->role == "Human Resources") {
                $departmentApprovers = DepartmentApprovers::where('department_id', $request->departmentId)->where('status_level', 1)->first();
                $approver = User::where('id', $departmentApprovers->user_id)->first();
                $hr = User::where('id', $nodFile->user_id)->first();
                $typeOfPenalties = "NOD File";
                $approver->notify(new ApproverNotification($approver->name, $request->yearAndMonth, $hr->name, $departmentApprovers->department->name, $typeOfPenalties));
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

        $mdrSummary = MdrSummary::findOrFail($request->mdr_summary_id);
        $mdrSummary->penalty_status = $request->status;
        $mdrSummary->save();
        
        Alert::success('SUCCESS', 'Successfully Submitted.');
        return back();
    }
}
