<?php

namespace App\Http\Controllers;

use App\Admin\Department;
use App\DeptHead\Mdr;
use App\MdrReportRemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use RealRashid\SweetAlert\Facades\Alert;
use stdClass;

class MdrReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        $departments = Department::with('user')->where('status','Active')->get();
        $wli_reports_array = [];
        foreach($departments->where('company_id',3) as $department)
        {
            $mdr = Mdr::with('departments')->where('department_id', $department->id)->where('year', date('Y', strtotime($request->year_month)))->where('month', date('m', strtotime($request->year_month)))->orderBy('score','asc')->first();
            $object = new stdClass;
            $object->department = $department->name;
            $object->departments = $department;
            $object->head = isset($department->user->name) ? $department->user->name : null;
            $object->mdr = $mdr;
            $wli_reports_array[] = $object;
        }

        $whi_reports_array = [];
        foreach($departments->where('company_id',2) as $department)
        {
            $mdr = Mdr::with('departments')->where('department_id', $department->id)->where('year', date('Y', strtotime($request->year_month)))->where('month', date('m', strtotime($request->year_month)))->orderBy('score','asc')->first();
            $object = new stdClass;
            $object->department = $department->name;
            $object->departments = $department;
            $object->head = isset($department->user->name) ? $department->user->name : null;
            $object->mdr = $mdr;
            $whi_reports_array[] = $object;
        }
        // dd($wli_reports_array);
        return view('approver.history-mdr',
            array(
                'wli_reports_array' => $wli_reports_array,
                'whi_reports_array' => $whi_reports_array,
                'year_month' => $request->year_month
                // 'process_improvement' => $process_improvement,
                // 'departments' => $departments,
                // 'year_and_month' => $request->yearAndMonth,
                // 'mdr_score' => $mdr_score,
                // 'department_id' => $request->department,
                // 'dept_name' => $dept_name->name
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $mdr_reports_remarks = MdrReportRemark::where('department_id', $request->department_id)->where('year',date('Y', strtotime($request->year_and_month)))->where('month', date('m', strtotime($request->year_and_month)))->first();
        // dd($mdr_reports_remarks);
        if($mdr_reports_remarks)
        {
            $mdr_reports_remarks->remarks = $request->remarks;
            $mdr_reports_remarks->save();
        }
        else
        {
            $mdr_reports_remarks = new MdrReportRemark;
            $mdr_reports_remarks->department_id = $request->department_id;
            $mdr_reports_remarks->year = date('Y', strtotime($request->year_and_month));
            $mdr_reports_remarks->month = date('m', strtotime($request->year_and_month));
            $mdr_reports_remarks->remarks = $request->remarks;
            $mdr_reports_remarks->save();
        }

        Alert::success('Successfully Saved')->persistent('Dismiss');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
    public function export(Request $request,$year_month)
    {
        // dd($request->all());
        $data = [];
        $data['year_and_month'] = $year_month;

        $departments = Department::with('user')->where('status','Active')->get();
        $data['wli'] = [];
        foreach($departments->where('company_id',3) as $department)
        {
            $mdr = Mdr::with('departments')->where('department_id', $department->id)->where('year', date('Y', strtotime($year_month)))->where('month', date('m', strtotime($year_month)))->orderBy('score','asc')->first();
            $object = new stdClass;
            $object->department = $department->name;
            $object->departments = $department;
            $object->head = isset($department->user->name) ? $department->user->name : null;
            $object->mdr = $mdr;
            $data['wli'][] = $object;
        }

        $data['whi'] = [];
        foreach($departments->where('company_id',2) as $department)
        {
            $mdr = Mdr::with('departments')->where('department_id', $department->id)->where('year', date('Y', strtotime($year_month)))->where('month', date('m', strtotime($year_month)))->orderBy('score','asc')->first();
            $object = new stdClass;
            $object->department = $department->name;
            $object->departments = $department;
            $object->head = isset($department->user->name) ? $department->user->name : null;
            $object->mdr = $mdr;
            $data['whi'][] = $object;
        }
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('approver.mdr_report', ['data' => $data])
            ->setPaper('a3', 'landscape')->setWarnings(false);
            
        return $pdf->stream();

    }
}
