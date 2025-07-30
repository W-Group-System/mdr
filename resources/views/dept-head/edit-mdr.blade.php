@extends('layouts.app')
@section('content')

@section('css')
<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="css/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<!-- Sweet Alert -->
<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

<div class="wrapper wrapper-content">
    <div class="row">
        <h1 class="text-center">{{ date('F Y', strtotime($yearAndMonth)) }}</h1>
        @include('components.error')
        <div class="col-md-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    Departmental Goals
                </div>
                <div class="ibox-content">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editKpi" style="margin-top: 3px;">
                        <i class="fa fa-pencil"></i>
                        Edit KPI
                    </button>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="departmentalGoals">
                            <thead>
                                <tr>
                                    {{-- <th>Actions</th> --}}
                                    <th>Key Performance Indicator</th>
                                    <th>Target</th>
                                    <th>Actual</th>
                                    <th>Grade</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentalGoals as $dptGoals)
                                    <tr>
                                        <td>{!! nl2br($dptGoals->departmentKpi->name) !!}
                                            <p class="m-t-md"><a href="javascript:void(0)" data-toggle="modal" data-target="#comments{{ $dptGoals->id }}"><i class="fa fa-comments"></i> {{ count($dptGoals->comments) }} View Comments</a></p>
                                        </td>
                                        <td>{!! nl2br($dptGoals->departmentKpi->target) !!}</td>
                                        <td>{{$dptGoals->actual}}</td>
                                        <td>{{$dptGoals->grade}}</td>
                                        <td>{!! nl2br($dptGoals->remarks) !!}</td>
                                        <td>
                                            @foreach ($dptGoals->attachments as $key=>$attachment)
                                                <span>{{$key+1}}. </span>
                                                <a href="{{url($attachment->file_path)}}" target="_blank">
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

        <div class="col-md-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Innovation
                    </button>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="innovationTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Project Charter</th>
                                    <th>Project Benefit</th>
                                    <th>Accomplishment Report</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($innovations as $innovation)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit{{ $innovation->id }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
        
                                            <form action="{{ url('deleteInnovation/'.$innovation->id) }}" method="post" onsubmit="show()" style="display: inline-block;">
                                                @csrf
        
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>   
                                        <td>{{ $innovation->project_charter }}</td>
                                        <td>{{ $innovation->project_benefit }}</td>
                                        <td>
                                            @foreach ($innovation->innovationAttachments as $attachment)
                                                <a href="{{ url($attachment->filepath) }}" target="_blank">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <br>
                                            @endforeach
                                        </td>
                                    </tr>

                                    @include('dept-head.edit_innovation')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addProcessDevelopment">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Process Improvement
                    </button>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="processDevelopmentTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Activities</th>
                                    <th>Benefit <br>
                                        <small><i>(Specify if Time Savings, Risk Mitigation, Cost Savings / Profit gain, Employee Morale)</i></small>
                                    </th>
                                    <th>DICR Number</th>
                                    <th>Date Approved</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($process_improvement as $processDevelopmentData)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editProcessDevelopment{{ $processDevelopmentData->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>

                                            <form action="{{ url('deleteProcessDevelopment/' . $processDevelopmentData->id) }}" method="post" onsubmit="show()" style="display: inline-block;">
                                                @csrf

                                                <input type="hidden" name="yearAndMonth" value="{{$yearAndMonth}}">
                                                <input type="hidden" name="department" value="{{$processDevelopmentData->department_id}}">

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $processDevelopmentData->activities }}</td>
                                        <td>{!! nl2br($processDevelopmentData->remarks) !!}</td>
                                        <td>{{$processDevelopmentData->dicr_number}}</td>
                                        <td>{{ date('F d, Y', strtotime($processDevelopmentData->accomplished_date )) }}</td>
                                        <td>
                                            @foreach ($processDevelopmentData->pdAttachments as $key=>$pdFile)
                                                <span>{{$key+1}}. </span>
                                                <a href="{{ url($pdFile->filepath) }}" target="_blank">
                                                    <i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <br>
                                            @endforeach
                                        </td>
                                    </tr>

                                    @include('dept-head.edit_process_improvement')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                {{-- <div class="ibox-title">
                    <b>MDR Status</b>
                </div> --}}
                <div class="ibox-content">
                    @if(auth()->user()->role == "Users" || auth()->user()->role == "Department Head")
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{url('approveMdr')}}" method="POST" onsubmit="show()">
                                    @csrf

                                    <input type="hidden" name="yearAndMonth" value="{{$yearAndMonth}}">
                                    <input type="hidden" name="department_id" value="{{auth()->user()->department_id}}">
                                    {{-- @if(optional($data->mdrSummary)->level != null) disabled @endif --}}
                                    <button type="button" class="btn btn-block btn-primary approveBtn" >
                                        Submit MDR
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('dept-head.add_innovation')
@include('dept-head.add_process_improvement')
@include('dept-head.edit_kpi')
@endsection

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>

<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- DROPZONE -->
<script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
<!-- Jasny -->
<script src="{{ asset('js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
<!-- Sweet alert -->
<script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

<script>
    $(document).ready(function() {
        $("[name='grade[]']").keypress(function(event) {
            if (event.keyCode == 8) {
                return
            }

            if (event.keyCode < 48 || event.keyCode > 57) {
                event.preventDefault(); 
            }   
        });

        $('#processDevelopmentTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $('#innovationTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $('#departmentalGoals').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $(".approveBtn").on('click', function() {
            var form = $(this).closest('form');

            swal({
                title: "Are you sure?",
                text: "The mdr will be submitted",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, submit it!",
                closeOnConfirm: false
            }, function (){
                form.submit()
            });
        })
    })
</script>
@endpush