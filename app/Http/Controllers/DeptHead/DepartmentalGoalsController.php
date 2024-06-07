<?php

namespace App\Http\Controllers\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentKPI;
use App\Approver\MdrSummary;
use App\DeptHead\Attachments;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\KpiScore;
use App\DeptHead\MdrStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class DepartmentalGoalsController extends Controller
{
    public function create(Request $request) {
        $departmentKpi = DepartmentKPI::with([
                'attachments' => function($q)use($request) {
                    $q->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->where('month', date('m', strtotime($request->yearAndMonth)));
                }
            ])
            ->where('department_id', auth()->user()->department_id)
            ->whereIn('id', $request->mdr_setup_id)
            ->get();
        
        $hasAttachments = $departmentKpi->every(function($value, $key) {
            return $value->attachments->isNotEmpty();
        });

        if (!$hasAttachments) {
            
            Alert::error("ERROR", "Please attach a file in every KPI.");
            return back();
        }
        else {
            $validator = Validator::make($request->all(), [
                'actual[]' => 'array',
                // 'remarks[]' => 'array',
                'grade[]' => 'array',
                'actual.*' => 'required',
                // 'remarks.*' => 'required',
                'grade.*' => 'required'
            ], [
                'actual.*' => 'The actual field is required.',
                // 'remarks.*' => 'The remarks field is required.',
                'grade.*' => 'The grades field is required.'
            ]);
    
            if ($validator->fails()) {

                return back()->with('kpiErrors', $validator->errors()->all());
            } else {
                $checkStatus = DepartmentalGoals::where('status_level', "<>", 0)
                    ->where('department_id', auth()->user()->department_id)
                    ->where('year', date('Y', strtotime($request->yearAndMonth)))
                    ->where('month', date('m', strtotime($request->yearAndMonth)))
                    ->get();
                
                if ($checkStatus->isNotEmpty()) {

                    Alert::error("ERROR", "Failed. Because your MDR has been approved.");
                    return back();
                }
                else {
                    $departmentalGoalsList = DepartmentalGoals::whereIn('mdr_setup_id', $request->mdr_setup_id)
                        ->where('year', date('Y', strtotime($request->yearAndMonth)))
                        ->where('month', date('m', strtotime($request->yearAndMonth)))
                        ->get();
                    
                    if ($departmentalGoalsList->isNotEmpty()) {
                        $targetDate = 0;
                        $deadlineDate = "0000-00-00";
                        foreach($departmentalGoalsList as $dept) {
                            $targetDate = $dept->departments->target_date;
                            $deadlineDate = $dept->deadline;
                        }
                        
                        $actual = $request->input('actual');
                        $remarks = $request->input('remarks');
                        $grades = $request->input('grade');
                        
                        $departmentalGoalsList->each(function($item, $index) use($actual, $grades, $request, $remarks, $targetDate) {
                            $item->update([
                                'actual' => $actual[$index],
                                'remarks' => $remarks[$index],
                                'grade' => $grades[$index],
                            ]);
                        });

                        $date = $request->yearAndMonth;
                        
                        $this->computeKpi($grades, $date, $deadlineDate);
                    }
                    else {
                        $targetDate = 0;
                        foreach($departmentKpi as $dept) {
                            $targetDate = $dept->departments->target_date;
                        }
                        
                        foreach($departmentKpi as $key => $data) {
                            $deptGoals = new  DepartmentalGoals;
                            $deptGoals->department_id = $data->department_id;
                            $deptGoals->mdr_group_id = $data->mdr_group_id;
                            $deptGoals->mdr_setup_id = $data->id;
                            $deptGoals->kpi_name = $data->name;
                            $deptGoals->target = $data->target;
                            $deptGoals->actual = $request->actual[$key];
                            $deptGoals->grade = $request->grade[$key];
                            $deptGoals->remarks = $request->remarks[$key];
                            $deptGoals->year = date('Y', strtotime($request->yearAndMonth));
                            $deptGoals->month = date('m', strtotime($request->yearAndMonth));
                            $deptGoals->deadline = date('Y-m', strtotime("+1month", strtotime($deptGoals->year.'-'.$deptGoals->month))).'-'.$targetDate;
                            $deptGoals->status_level = 0;
                            $deptGoals->save();
                        }
    
                        $date = $request->yearAndMonth;
                        $deadlineDate = $deptGoals->deadline;
    
                        $this->computeKpi($request->grade, $date, $deadlineDate);
                    }

                    Alert::success('SUCCESS', 'Your KPI is submitted.');
                    return back();
                }
            }
        }
    }

    // public function update(Request $request) {
    //     $checkIfHaveAttachments = DepartmentKPI::with([
    //             'attachments' => function($q)use($request) {
    //                 $q->where('year', date('Y', strtotime($request->yearAndMonth)))
    //                     ->where('month', date('m', strtotime($request->yearAndMonth)));
    //             }
    //         ])
    //         ->where('department_id', auth()->user()->department_id)
    //         ->get();
            
    //     $hasAttachments = $checkIfHaveAttachments->every(function($value, $key) {
    //         return $value->attachments->isNotEmpty();
    //     });

    //     if (!$hasAttachments) {
            
    //         Alert::error("ERROR", "Please attach a file in every KPI.");
    //         return back();
    //     }
    //     else {
    //         $validator = Validator::make($request->all(), [
    //             'actual[]' => 'array',
    //             // 'remarks[]' => 'array',
    //             'grade[]' => 'array',
    //             'actual.*' => 'required',
    //             // 'remarks.*' => 'required',
    //             'grade.*' => 'required'
    //         ], [
    //             'actual.*' => 'The actual field is required.',
    //             // 'remarks.*' => 'The remarks field is required.',
    //             'grade.*' => 'The grades field is required.'
    //         ]);
    
    //         if ($validator->fails()) {

    //             return back()->with('kpiErrors', $validator->errors()->all());
    //         } else {
    //             $checkStatus = DepartmentalGoals::where('status_level', "<>", 0)
    //                 ->where('department_id', auth()->user()->department_id)
    //                 ->where('year', date('Y', strtotime($request->yearAndMonth)))
    //                 ->where('month', date('m', strtotime($request->yearAndMonth)))
    //                 ->get();
                
    //             if ($checkStatus->isNotEmpty()) {

    //                 Alert::error("ERROR", "Failed. Because your MDR has been approved.");
    //                 return back();
    //             }
    //             else {
    //                 $departmentalGoalsList = DepartmentalGoals::whereIn('mdr_setup_id', $request->mdr_setup_id)
    //                     ->where('year', date('Y', strtotime($request->yearAndMonth)))
    //                     ->where('month', date('m', strtotime($request->yearAndMonth)))
    //                     ->get();
                    
    //                 $targetDate = 0;
    //                 $deadlineDate = "0000-00-00";
    //                 foreach($departmentalGoalsList as $dept) {
    //                     $targetDate = $dept->departments->target_date;
    //                     $deadlineDate = $dept->deadline;
    //                 }
    
    //                 $actual = $request->input('actual');
    //                 $remarks = $request->input('remarks');
    //                 $grades = $request->input('grade');
                    
    //                 $departmentalGoalsList->each(function($item, $index) use($actual, $grades, $request, $remarks, $targetDate) {
    //                     $item->update([
    //                         'actual' => $actual[$index],
    //                         'remarks' => $remarks[$index],
    //                         'grade' => $grades[$index],
    //                     ]);
    //                 });

    //                 $date = $request->yearAndMonth;
                    
    //                 $this->computeKpi($grades, $date, $deadlineDate);

    //                 Alert::success('SUCCESS', 'Your KPI is submitted.');
    //                 return back();
    //             }
    //         }
    //     }
    // }

    public function computeKpi($grades, $date, $deadlineDate) {
        $grade = collect($grades);

        $kpiValue = $grade->map(function($item, $key) {

            if ($item > 100) {
                $item = 100;
            }

            $value = $item / 100.00;

            return $value;
        });
        
        $kpiScore = $grade->map(function($item, $key) {
            if ($item > 100) {
                $item = 100;
            }

            $grades =  $item / 100.00 * 0.5;
            
            return $grades;
        });
        
        $value = number_format($kpiValue->sum(), 2);
        $rating = 3.00;
        $score = number_format($kpiScore->sum(), 2);
        
        // $kpiScoreData = KpiScore::where('department_id', auth()->user()->department_id)
        //     ->where('year', date('Y', strtotime($date)))
        //     ->where('month', date('m', strtotime($date)))
        //     ->first();

        $mdrSummary = MdrSummary::with(['mdrStatus', 'kpiScores', 'departments.approver'])
            ->where('department_id', auth()->user()->department_id)
            ->where('year', date('Y', strtotime($date)))
            ->where('month', date('m', strtotime($date)))
            ->first();

        if(empty($mdrSummary)) {
            $mdrSummary = new MdrSummary;
            $mdrSummary->department_id =auth()->user()->department_id;
            $mdrSummary->user_id = auth()->user()->id;
            $mdrSummary->deadline = $deadlineDate;
            $mdrSummary->submission_date = date('Y-m-d');
            $mdrSummary->status = $deadlineDate >= date('Y-m-d') ? 'On-Time' : 'Delayed';
            $mdrSummary->year = date('Y', strtotime($date));
            $mdrSummary->month = date('m', strtotime($date));
            $mdrSummary->save();
        }

        $kpiScoreData = $mdrSummary->kpiScores;
        
        if (!empty($kpiScoreData)) {
            $kpiScoreData->grade = $value;
            $kpiScoreData->rating = $rating;
            $kpiScoreData->score = $score;
            $kpiScoreData->save();
        }
        else {
            $kpiScore = new KpiScore;
            $kpiScore->department_id = auth()->user()->department_id;
            $kpiScore->grade = $value;
            $kpiScore->rating = $rating;
            $kpiScore->score = $score;
            $kpiScore->year = date('Y', strtotime($date));
            $kpiScore->month = date('m', strtotime($date));
            $kpiScore->deadline = $deadlineDate;
            $kpiScore->mdr_summary_id = $mdrSummary->id;
            $kpiScore->save();
        }

        $mdrStatus = $mdrSummary->mdrStatus()
            ->where('mdr_summary_id', $mdrSummary->id)
            ->get();

        if ($mdrStatus->isEmpty()) {
            foreach($mdrSummary->departments->approver as $data) {
                $mdrStatus = new MdrStatus;
                $mdrStatus->user_id = $data->user_id;
                $mdrStatus->mdr_summary_id = $mdrSummary->id;
                $mdrStatus->status = 0;
                $mdrStatus->save();
            }
        }
        // else {
        //     foreach($mdrStatus as $status) {
        //         $status->delete();
        //     }
            
        //     foreach($departmentData->approver as $data) {
        //         $mdrStatus = new MdrStatus;
        //         $mdrStatus->user_id = $data->user_id;
        //         $mdrStatus->mdr_summary_id = $mdrSummary->id;
        //         $mdrStatus->status = 0;
        //         $mdrStatus->save();
        //     }
        // }
        
    }
    
    public function uploadAttachments(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'file[]' => 'max:2048|mimes:pdf,jpg,png,jpeg'
        ]);

        if ($validator->fails()) {
            
            return back()->with('kpiErrors', $validator->errors()->all());
        }
        else {
            if ($request->hasFile('file')) {
                $departmentData = Department::select('id', 'target_date')
                    ->where('id', auth()->user()->department_id)
                    ->first();

                $files = $request->file('file');

                $filePathArray = array();
                foreach($files as $file) {
                    $fileName = time() . '-' . $file->getClientOriginalName();
                    $file->move(public_path('file'),  $fileName);

                    $attachment = new Attachments;
                    $attachment->department_id = $departmentData->id;
                    $attachment->mdr_setup_id = $id;
                    $attachment->file_path = 'file/' . $fileName;
                    $attachment->file_name = $fileName;
                    $attachment->year = date('Y', strtotime($request->yearAndMonth));
                    $attachment->month = date('m', strtotime($request->yearAndMonth));
                    $attachment->deadline = date('Y-m', strtotime("+1month", strtotime($attachment->year.'-'.$attachment->month))).'-'.$departmentData->target_date;
                    $attachment->save();

                    $filePathArray[$attachment->id] = $attachment->file_path;
                }

                return response()->json([
                    'id' => $id,
                    'file' => count($request->file('file')),
                    'filePath' => $filePathArray,
                ]);
            }
            else {
                return back()->with('kpiErrors', 'You are not selecting a file');
            }
        }
    }

    public function deleteAttachments(Request $request) {
        $attachmentData = Attachments::findOrFail($request->id);

        if ($attachmentData) {
            $attachmentData->delete();
        }

    }
}
