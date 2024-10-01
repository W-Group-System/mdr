@extends('layouts.app')
@section('css')
<link href="{{asset('css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-md-12">
            <div class="mb-3 mt-3">
                {{-- @if(auth()->user()->role == "Administrator")
                @foreach ($mdrSummary->approvers->where('status', 'Pending') as $key => $approver)
                    @php
                        $mdr = $mdrSummary;
                    @endphp

                    <button class="btn btn-primary" data-toggle="modal" data-target="#view{{$mdr->id}}">
                        <i class="fa fa-eye"></i>
                        View Status
                    </button>

                    @include('approver.view_mdr_status')
                @endforeach
                @endif

                @if(auth()->user()->role == "Approver" || auth()->user()->role == "Department Head")
                @foreach ($mdrSummary->approvers->where('status', 'Pending')->where('user_id', auth()->user()->id) as $key => $approver)
                    @php
                        $mdr = $mdrSummary;
                    @endphp

                    <button class="btn btn-primary" data-toggle="modal" data-target="#view{{$mdr->id}}">
                        <i class="fa fa-eye"></i>
                        View Status
                    </button>

                    @include('approver.view_mdr_status')
                @endforeach
                @endif --}}
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    <p>MDR Scores</p>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered" >
                            <thead>
                                <tr>
                                    @if(auth()->user()->role != "Department Head" && auth()->user()->role != "Administrator")
                                    <th>Actions</th>
                                    @endif
                                    <th>Month</th>
                                    <th>KPI</th>
                                    <th>Process Improvement</th>
                                    {{-- <th>Innovation</th> --}}
                                    <th>Timeliness</th>
                                    <th>Rating</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary->mdrScore as $score)
                                <tr>
                                    @if(auth()->user()->role != "Department Head" && auth()->user()->role != "Administrator")
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target="#editScores{{$score->id}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                    </td>
                                    @endif
                                    <td>{{ date('F Y', strtotime($score->yearAndMonth)) }}</td>
                                    <td>@if($score->score != null){{ $score->score }}@else 0.0 @endif</td>
                                    <td>@if($score->pd_scores != null){{$score->pd_scores}}@else 0.0 @endif</td>
                                    {{-- <td>{{$score->innovation_scores}}</td> --}}
                                    <td>{{$score->timeliness}}</td>
                                    <td>{{ $score->total_rating }}</td>
                                    <td>@if($score->remarks != null){{$score->remarks}}@else N/A @endif</td>
                                </tr>
                                {{-- <form action="{{ url('submit_scores') }}" method="post" id="submitScoresForm"
                                    onsubmit="show()">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $score->id }}">
                                </form> --}}

                                @include('approver.edit_mdr_scores')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    <p>Departmental Goals</p>
                </div>
                <div class="ibox-content">
                    {{-- {{dd(auth()->user()->role)}} --}}
                    @if(auth()->user()->role != "Department Head" && auth()->user()->role != "Administrator")
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editKpi" style="margin-top: 3px;">
                        <i class="fa fa-pencil"></i>
                        Edit KPI
                    </button>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="departmentalGoals">
                            <thead>
                                <tr>
                                    {{-- <th>Actions</th> --}}
                                    <th>KPI</th>
                                    <th>Target</th>
                                    <th>Actual</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary->departmentalGoals as $dptGoals)
                                <tr>
                                    {{-- <td>
                                        <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#editKpi{{$dptGoals->id}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                    </td> --}}
                                    <td>{!! nl2br($dptGoals->kpi_name) !!}</td>
                                    <td>{!! nl2br($dptGoals->target) !!}</td>
                                    <td>{{ $dptGoals->actual }}</td>
                                    <td>
                                        {{$dptGoals->grade}}
                                    </td>
                                    <td>
                                        {{$dptGoals->remarks}}
                                    </td>
                                    <td>
                                        @foreach ($dptGoals->attachments as $key=>$file)
                                        <span>{{$key+1}}. </span>
                                        <a href="{{url($file->file_path)}}" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                        <br>
                                        @endforeach
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <p>Innovation</p>
                        <table class="table table-bordered" id="innovationTable">
                            <thead>
                                <tr>
                                    <th>Innovations / Projects</th>
                                    <th>Project Summary</th>
                                    <th>Job / Work Order Number</th>
                                    <th>Start Date</th>
                                    <th>Target Date of Completion</th>
                                    <th>Actual Date of Completion</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary->innovation as $innovationData)
                                <tr>
                                    <td>{{ $innovationData->projects }}</td>
                                    <td>{{ $innovationData->project_summary }}</td>
                                    <td>{{ $innovationData->work_order_number }}</td>
                                    <td>{{ date('F m, Y', strtotime($innovationData->start_date)) }}</td>
                                    <td>{{ date('F m, Y', strtotime($innovationData->target_date)) }}</td>
                                    <td>{{ date('F m, Y', strtotime($innovationData->actual_date)) }}</td>
                                    <td>
                                        {!! nl2br(e($innovationData->remarks)) !!}
                                    </td>
                                    <td>
                                        @foreach ($innovationData->innovationAttachments as $key=>$file)
                                        <span>{{ $key+1 }}. &nbsp;</span>
                                        <a href="{{ url($file->filepath) }}" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                        <br>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    <p>Process Improvement</p>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="processDevelopmentTable">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Description</th>
                                    <th>Accomplished Date</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary->processImprovement as $item)
                                <tr>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit{{$item->id}}">  
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                    </td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ date('F d, Y', strtotime($item->accomplished_date)) }}</td>
                                    <td>
                                        {!! nl2br(e($item->remarks)) !!}
                                    </td>
                                    <td>
                                        @foreach ($item->pdAttachments as $key=>$file)
                                        <div>
                                            <span>{{ $key+1 }}. &nbsp;</span>
                                            <a href="{{ url($file->filepath) }}" target="_blank">
                                                <i class="fa fa-file-pdf-o"></i>
                                            </a>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>

                                @include('approver.edit_process_improvement_remarks')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibx float-e-margins">
                <div class="ibox-title">
                    <b>MDR Status</b>
                </div>
                <div class="ibox-content">
                    @if(auth()->user()->role == "Administrator")
                        @foreach ($mdrSummary->approvers->where('status', 'Pending') as $key => $approver)
                            @php
                                $mdr = $mdrSummary;
                            @endphp
            
                            <form action="{{url('approver_mdr/'.$approver->id)}}" method="post" onsubmit="show()">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                MDR Status
                                            </div>
                                            <div class="panel-body">
                                                <div class='row text-center'>
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Approver</strong>
                                                    </div>
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Status</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Start Date</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Action Date</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Remarks</strong>
                                                    </div>
                                                </div>
                                                @foreach ($mdrSummary->approvers->where('mdr_summary_id', $mdr->id) as $approver)
                                                <div class="row text-center">
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->users->name}}
                                                    </div>
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->status}}
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->start_date}}
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        @if($approver->status == "Approved" || $approver->status == "Returned")
                                                        {{date('Y-m-d', strtotime($approver->updated_at))}}
                                                        @endif  
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->remarks}}
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        Actions :
                                        <select name="action" class="form-control cat">
                                            <option value="">Select Action</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Returned">Returned</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        Remarks :
                                        <textarea name="remarks" class="form-control" cols="30" rows="10" required></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        &nbsp;
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    @endif

                    @if(auth()->user()->role == "Approver" || auth()->user()->role == "Department Head" || auth()->user()->role == "Business Process Manager")
                        @foreach ($mdrSummary->approvers->where('status', 'Pending')->where('user_id', auth()->user()->id) as $key => $approver)
                            @php
                                $mdr = $mdrSummary;
                            @endphp

                            <form action="{{url('approver_mdr/'.$approver->id)}}" method="post" onsubmit="show()">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                MDR Status
                                            </div>
                                            <div class="panel-body">
                                                <div class='row text-center'>
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Approver</strong>
                                                    </div>
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Status</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Start Date</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Action Date</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Remarks</strong>
                                                    </div>
                                                </div>
                                                @foreach ($mdrSummary->approvers->where('mdr_summary_id', $mdr->id) as $approver)
                                                <div class="row text-center">
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->users->name}}
                                                    </div>
                                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->status}}
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->start_date}}
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        @if($approver->status == "Approved" || $approver->status == "Returned")
                                                        {{date('Y-m-d', strtotime($approver->updated_at))}}
                                                        @endif  
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->remarks}}
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        Actions :
                                        <select name="action" class="form-control cat">
                                            <option value="">Select Action</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Returned">Returned</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        Remarks :
                                        <textarea name="remarks" class="form-control" cols="30" rows="10" required></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        &nbsp;
                                        <button type="submit" class="btn btn-primary btn-sm btn-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('approver.edit_list_of_mdr')

@endsection

@push('scripts')
{{-- chosen --}}
<script src="{{asset('js/plugins/chosen/chosen.jquery.js')}}"></script>
{{-- datatable --}}
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $(".cat").chosen({width: "100%"})


        $('#processDevelopmentTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });

        $('#innovationTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });

        $('#departmentalGoals').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });

        $('#kpiScores').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
        
        // $("[name='grade[]']").keypress(function(event) {
        //     if (event.keyCode == 8) {
        //         return
        //     }

        //     if (event.keyCode < 48 || event.keyCode > 57) {
        //         event.preventDefault(); 
        //     }   
        // });

    })
</script>
@endpush