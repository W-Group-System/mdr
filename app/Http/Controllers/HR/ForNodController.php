<?php

namespace App\Http\Controllers\HR;

use App\Approver\MdrSummary;
use App\HR\NodAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ForNodController extends Controller
{
    public function index(Request $request) {
        $mdrSummary = MdrSummary::with([
            'departments.user',
            'nodAttachments.users'
        ])
        ->where('rate', '<', 2.99)
        ->where('final_approved', 1)
        ->where('penalty_status', 1);

        if (!empty($request->yearAndMonth)) {
            $mdrSummary = $mdrSummary->where('year', date('Y', strtotime($request->yearAndMonth)))
                                    ->where('month', date('m', strtotime($request->yearAndMonth)));
        }
        else {
            $mdrSummary = $mdrSummary->where('year', date('Y'))
                                    ->where('month', date('m'));
        }

        $mdrSummary = $mdrSummary->get();

        return view('hr.for-nod',
            array(
                'yearAndMonth' => !empty($request->yearAndMonth) ? $request->yearAndMonth : date('Y-m'),
                'mdrSummary' => $mdrSummary
            )
        );
    }

    public function uploadNod(Request $request) {
        if ($request->hasFile('files')) {
            $nodFile = NodAttachments::where('department_id', $request->departmentId)
                ->where('year', date('Y', strtotime($request->yearAndMonth)))
                ->where('month', date('m', strtotime($request->yearAndMonth)))
                ->first();

            if (!empty($nodFile->acknowledge_by)) {
                Alert::error('ERROR', "Can't upload NOD File");

                return back();
            }

            $files = $request->file('files');
            $fileName = time().'-'.$files->getClientOriginalName();
            $files->move(public_path('nod_attachments'), $fileName);

            if (!empty($nodFile)) {
                $nodFile->user_id = auth()->user()->id;
                $nodFile->filepath = 'nod_attachments/'.$fileName;
                $nodFile->save();

                Alert::success('SUCCESS', 'Uploaded successfully.');
                return back();
            }
            else {
                $nodFile = new NodAttachments;
                $nodFile->department_id = $request->departmentId;
                $nodFile->mdr_summary_id = $request->mdrSummaryId;
                $nodFile->user_id = auth()->user()->id;
                $nodFile->year = date('Y', strtotime($request->yearAndMonth));
                $nodFile->month = date('m', strtotime($request->yearAndMonth));
                $nodFile->filepath = 'nod_attachments/'.$fileName;
                $nodFile->save();

                // $user = User::where('department_id', $request->departmentId)
                //     ->where('account_role', 2)
                //     ->first();

                // $user->notify(new NteNotificationForDeptHead($nteFile->filepath, $user->name, $request->yearAndMonth));
    
                Alert::success('SUCCESS', 'Uploaded successfully.');
                return back();
            }
        }
        else {
            Alert::success('ERROR', 'You are not selecting a file.');
            return back();
        }
        
    }

    public function deleteNod($id) {
        $nodAttachment = NodAttachments::findOrFail($id);

        if ($nodAttachment) {
            $nodAttachment->delete();

            Alert::success('SUCCESS', 'Successfully Deleted.');
            return back();
        }
    }

    public function acknowledgeBy(Request $request) {
        $nodAttachments = NodAttachments::findOrFail($request->nodAttachmentId);
        $nodAttachments->acknowledge_by = $request->acknowledgeBy;
        $nodAttachments->save();

        Alert::success('SUCCESS', 'Successfully Acknowledge.');
        return back();
    }

    public function nodStatus(Request $request) {
        $mdrSummary = MdrSummary::with([
                'nodAttachments'
            ])
            ->where('id', $request->mdr_summary_id)
            ->first();
        
        $nodAttachment = $mdrSummary->nodAttachments;
        
        if (!empty($nodAttachment->status)) {
            
            Alert::error('ERROR', 'Error! The MDR Status is For NOD.');
        }
        else {
            if (isset($request->waivedValue)) {
                $nodAttachment->update([
                    'status' => $request->waivedValue
                ]);
    
                Alert::success('SUCCESS', 'Successfully Waived.');
            }
            else {
                $nodAttachment->update([
                    'status' => $request->forNodValue
                ]);

                $mdrSummary->update([
                    'penalty_status' => $mdrSummary->penalty_status+1
                ]);
    
                Alert::success('SUCCESS', 'Successfully For PIP.');
            }
        }
        
        return back();
    }
}
