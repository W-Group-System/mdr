@extends('layouts.app')
@section('css')
<link href="{{asset('css/plugins/chosen/bootstrap-chosen.css')}}" rel="stylesheet">
<link href="{{ asset('css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

@endsection

@section('content')
<div class="wrapper wrapper-content animated">
    <div class="row">
        <h1 class="text-center">{{$mdrSummary->departments->name}}</h1>
        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    <h5>MDR Scores</h5>
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
                                    <th>Operational Objectives</th>
                                    {{-- <th>Process Improvement</th> --}}
                                    <th>Innovation</th>
                                    <th>Timeliness</th>
                                    <th>Rating</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if(auth()->user()->role != "Department Head" && auth()->user()->role != "Administrator")
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target="#editScores{{$mdrSummary->id}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                    </td>
                                    @endif
                                    <td>{{ date('F', mktime(0, 0, 0, $mdrSummary->month, 1)) }}</td>
                                    <td>@if($mdrSummary->grade != null){{ $mdrSummary->grade }}@else 0.00 @endif</td>
                                    {{-- <td>@if($mdrSummary->pd_scores != null){{$mdrSummary->pd_scores}}@else 0.0 @endif</td> --}}
                                    <td>@if($mdrSummary->innovation_scores != null){{$mdrSummary->innovation_scores}}@else 0.00 @endif</td>
                                    {{-- <td>{{$score->innovation_scores}}</td> --}}
                                    <td>{{$mdrSummary->timeliness}}</td>
                                    <td>{{ $mdrSummary->score }}</td>
                                    <td>@if($mdrSummary->remarks != null){{$mdrSummary->remarks}}@endif</td>
                                </tr>

                                @include('approver.edit_mdr_scores')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    <h5>Departmental Goals</h5>
                </div>
                <div class="ibox-content">
                    {{-- {{dd(auth()->user()->role)}} --}}
                    {{-- @if(auth()->user()->role != "Department Head" && auth()->user()->role != "Administrator") --}}
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editKpi" style="margin-top: 3px;">
                        <i class="fa fa-pencil"></i>
                        Add Grade
                    </button>
                    {{-- @endif --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="departmentalGoals">
                            <thead>
                                <tr>
                                    {{-- <th>Actions</th> --}}
                                    <th>KPI</th>
                                    <th>Target</th>
                                    <th>Actual</th>
                                    <th>Weight</th>
                                    <th>Score</th>
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
                                    <td>{!! nl2br($dptGoals->departmentKpi->name) !!}
                                        <p class="m-t-md"><a href="javascript:void(0)" data-toggle="modal" data-target="#comments{{ $dptGoals->id }}"><i class="fa fa-comments"></i> {{ count($dptGoals->comments) }} Comments</a></p>
                                    </td>
                                    <td>{!! nl2br($dptGoals->departmentKpi->target) !!}</td>
                                    <td>{{ $dptGoals->actual }}</td>
                                    <td>
                                        {{number_format($dptGoals->weight,2)}}
                                    </td>
                                    <td>
                                        {{number_format($dptGoals->grade,2)}}
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

                                    @include('comments')
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
                    <h5>Innovation</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="innovationTable">
                            <thead>
                                <tr>
                                    <th>Project Charter</th>
                                    <th>Project Benefit</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary->innovation as $innovationData)
                                <tr>
                                    <td>{{ $innovationData->project_charter }}</td>
                                    <td>{{ $innovationData->project_benefit }}</td>
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
        </div>

        {{-- <div class="col-lg-12">
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
        </div> --}}
        @php
            $fullTargetDate = getAdjustedTargetDate($mdrSummary->month, $mdrSummary->year, $mdrSummary->departments->target_date);
        @endphp
        <div class="col-lg-12">
            <div class="ibx float-e-margins">
                <div class="ibox-title">
                    <h5>Actions</h5>
                </div>
                <div class="ibox-content">
                    @if($mdrSummary->is_accepted == null)
                        <form action="{{url('accept_mdr/'.$mdrSummary->id)}}" method="post" onsubmit="show()">
                            @csrf

                            <div class="row">
                                @if ($mdrSummary->timeliness_approval == "Yes") 
                                    <div class="col-md-12">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                MDR Status @if ($mdrSummary->timeliness_approval === "Yes") : <strong>This Request is For Timeliness Approval</strong> @endif
                                            </div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Reason</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $mdrSummary->timeliness_remarks }}</td>
                                                        <td>
                                                            <button type="button" class="btn  btn-primary approveTimelinessRequest" data-url="{{ url('approveTime', $mdrSummary->id) }}"><i class="fa fa-thumbs-up"></i></button>
                                                            <button type="button" class="btn  btn-danger disapproveTimelinessRequest" data-url="{{ url('disapproveTime', $mdrSummary->id) }}"><i class="fa fa-thumbs-down"></i></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                @if ($mdrSummary->timeliness_approval != "Yes")
                                    <div class="col-md-4">
                                        Actions :
                                        <select name="action" class="form-control cat">
                                            <option value="">Select Action</option>
                                            @if (($mdrSummary->timeliness_approval === "Approved") || ($mdrSummary->timeliness_approval === "Disapproved"))
                                            <option value="AcceptLateApprove">Accept</option>
                                            <option value="Returned">Return</option>
                                            @else    
                                            <option value="Accept">Accept</option>
                                            <option value="Returned">Return</option>
                                            @if (now() > $fullTargetDate)
                                            <option value="Timeliness Approval">Timeliness Approval</option>
                                            @endif
                                            @endif
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
                                @endif
                            </div>
                        </form>
                    @endif

                    {{-- @if(auth()->user()->role == "Administrator")
                        @foreach ($mdrSummary->mdrApprover->where('status', 'Pending') as $key => $approver)
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
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Approver</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Status</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Start Date</strong>
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Action Date</strong>
                                                    </div>
                                                    <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                                        <strong>Remarks</strong>
                                                    </div>
                                                </div>
                                                @foreach ($mdrSummary->mdrApprover->where('mdr_summary_id', $mdr->id) as $approver)
                                                <div class="row text-center">
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                        {{$approver->users->name}}
                                                    </div>
                                                    <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
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
                                                    <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
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
                    @endif --}}

                    @foreach ($mdrSummary->mdrApprover->where('status', 'Pending')->where('user_id', auth()->user()->id) as $key => $approver)
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
                                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                    <strong>Status</strong>
                                                </div>
                                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                    <strong>Action Date</strong>
                                                </div>
                                                <div class='col-md-5 border border-primary border-top-bottom border-left-right'>
                                                    <strong>Remarks</strong>
                                                </div>
                                            </div>
                                            @foreach ($mdrSummary->mdrApprover->where('mdr_id', $mdr->id) as $approver)
                                            <div class="row text-center">
                                                <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                                    {{$approver->users->name}}
                                                </div>
                                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                    {{$approver->status}}
                                                </div>
                                                <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                                    {{-- @if($approver->status == "Approved" || $approver->status == "Returned") --}}
                                                    {{date('Y-m-d', strtotime($approver->updated_at))}}
                                                    {{-- @endif   --}}
                                                </div>
                                                <div class='col-md-5 border border-primary border-top-bottom border-left-right'>
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
                                    <textarea name="remarks" class="form-control" cols="30" rows="10" style="height: 10vh;" required></textarea>
                                </div>
                                <div class="col-md-12">
                                    &nbsp;
                                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                </div>
                            </div>
                        </form>
                    @endforeach
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
<script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
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

        $(".approveTimelinessRequest").on('click', function () {
            var url = $(this).data('url');
            var token = $('meta[name="csrf-token"]').attr('content');

            swal({
                title: "Approve Timeliness?",
                text: "Timeliness will be updated",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#4a88ed",
                confirmButtonText: "Yes, approve it!",
                cancelButtonText: "Cancel",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: token
                        },
                        success: function (response) {
                        swal("Approved!", response.message, "success");
                        
                        setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 1000);
                    },
                        error: function () {
                            swal("Error", "Something went wrong!", "error");
                        }
                    });
                }
            });
        });

        $(".disapproveTimelinessRequest").on('click', function () {
            var url = $(this).data('url');
            var token = $('meta[name="csrf-token"]').attr('content');

            swal({
                title: "Disapprove Timeliness?",
                text: "Please enter your remarks:",
                type: "input", 
                showCancelButton: true,
                confirmButtonColor: "#ec4758",
                confirmButtonText: "Yes, disapprove it!",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                inputPlaceholder: "Enter remarks here"
            }, function (remarks) {
                if (remarks === false) return; 
                if (remarks.trim() === "") {
                    swal.showInputError("Remarks are required!");
                    return false;
                }

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: token,
                        remarks: remarks
                    },
                    success: function (response) {
                        swal("Disapproved!", response.message, "success");
                        setTimeout(function () {
                            window.location.href = response.redirect;
                        }, 1000);
                    },
                    error: function () {
                        swal("Error", "Something went wrong!", "error");
                    }
                });
            });
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