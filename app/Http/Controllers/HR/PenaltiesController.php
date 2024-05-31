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
            ->where('final_approved', 1);

        if (!empty($request->yearAndMonth)) {
            $mdrSummary = $mdrSummary->where('year', date('Y', strtotime($request->yearAndMonth)))
                                    ->where('month', date('m', strtotime($request->yearAndMonth)));
        }
        else {
            $mdrSummary = $mdrSummary->where('year', date('Y'))
                                    ->where('month', date('m'));
        }

        $mdrSummary = $mdrSummary->get();

        return view('hr.penalties', 
            array(
                'yearAndMonth' => !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m'),
                'mdrSummary' => $mdrSummary
            )
        );
    }

    public function uploadNte(Request $request) {
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $fileName = time().'-'.$files->getClientOriginalName();
            $files->move(public_path('nte_attachments'), $fileName);

            $nteFile = NteAttachments::where('department_id', $request->departmentId)
                ->where('year', date('Y', strtotime($request->yearAndMonth)))
                ->where('month', date('m', strtotime($request->yearAndMonth)))
                ->first();

            if (!empty($nteFile)) {
                $nteFile->user_id = auth()->user()->id;
                $nteFile->filepath = 'nte_attachments/'.$fileName;
                $nteFile->save();

                Alert::success('SUCCESS', 'Uploaded successfully.');
                return back();
            }
            else {
                $nteFile = new NteAttachments;
                $nteFile->department_id = $request->departmentId;
                $nteFile->mdr_summary_id = $request->mdrSummaryId;
                $nteFile->user_id = auth()->user()->id;
                $nteFile->year = date('Y', strtotime($request->yearAndMonth));
                $nteFile->month = date('m', strtotime($request->yearAndMonth));
                $nteFile->filepath = 'nte_attachments/'.$fileName;
                $nteFile->save();

                $user = User::where('department_id', $request->departmentId)
                    ->where('account_role', 2)
                    ->first();

                $user->notify(new NteNotificationForDeptHead($nteFile->filepath, $user->name, $request->yearAndMonth));
    
                Alert::success('SUCCESS', 'Uploaded successfully.');
                return back();
            }
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
}
