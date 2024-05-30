<?php

namespace App\Http\Controllers\HR;

use App\Approver\MdrSummary;
use App\HR\NteAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class PenaltiesController extends Controller
{
    public function index(Request $request) {
        $mdrSummary = MdrSummary::with(['departments.user'])
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
        // dd($request->all());

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $fileName = time().'-'.$files->getClientOriginalName();
            $files->move(public_path('nte_attachments'), $fileName);
            
            $nteFile = new NteAttachments;
            $nteFile->department_id = $request->departmentId;
            $nteFile->mdr_summary_id = $request->mdrSummaryId;
            $nteFile->year = date('Y', strtotime($request->yearAndMonth));
            $nteFile->month = date('m', strtotime($request->yearAndMonth));
            $nteFile->filepath = 'nte_attachments/'.$fileName;
            $nteFile->save();

            Alert::success('SUCCESS', 'success');
            return back();
        }
        
    }
}
