@extends('layouts.app')
@section('css')
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">

<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        {{-- <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                Year & Month:
                                <div class="form-group">
                                    <input type="month" name="filterYearAndMonth" id="yearAndMonth" class="form-control input-sm" value="{{$filterYearAndMonth}}" max="{{date('Y-m')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                &nbsp;
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h1 class="text-center">{{auth()->user()->department->name}}</h1>

                @if(auth()->user()->role == "Users")
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#monthModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        New MDR
                    </button>
                </div>
                @endif

                <div class="ibox-content">
                    
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Month</th>
                                    <th>KPI</th>
                                    <th>Process Improvement</th>
                                    <th>Innovation</th>
                                    <th>Timeliness</th>
                                    <th>Rating</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrScore as $data)
                                    <tr>
                                        <td>
                                            @if($data->mdrSummary)
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#mdrStatusModal{{$data->mdrSummary->id}}">
                                                <i class="fa fa-user"></i>
                                            </button>
                                            @endif

                                            <form action="{{ url('edit_mdr') }}" method="get" style="display: inline-block;" onsubmit="show()">
                                                <input type="hidden" name="yearAndMonth" value="{{ $data->yearAndMonth }}">

                                                <button type="submit" class="btn btn-sm btn-warning" @if(optional($data->mdrSummary)->level != null) disabled @endif>
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                            </form>

                                            @if(auth()->user()->role == "Users")
                                            <form action="{{url('approveMdr')}}" method="POST" style="display: inline-block;" onsubmit="show()">
                                                @csrf

                                                <input type="hidden" name="yearAndMonth" value="{{$data->yearAndMonth}}">
                                                <input type="hidden" name="department_id" value="{{$data->department_id}}">

                                                <button type="button" class="btn btn-sm btn-primary approveBtn" @if(optional($data->mdrSummary)->level != null) disabled @endif>
                                                    <i class="fa fa-thumbs-up"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>{{ date("F Y", strtotime($data->yearAndMonth))}}</td>
                                        <td>@if($data->score != null){{ $data->score }}@else 0.00 @endif</td>
                                        <td>@if($data->pd_scores){{ $data->pd_scores }}@else 0.00 @endif</td>
                                        <td>@if($data->innovation_scores){{ $data->innovation_scores }}@else 0.00 @endif</td>
                                        <td>@if($data->timeliness != null){{ $data->timeliness }}@else @endif</td>
                                        <td>@if($data->total_rating != null){{ $data->total_rating }} @else 0.00 @endif</td>
                                        <td>@if($data->remarks != null){{ $data->remarks }}@else N/A @endif</td>
                                    </tr>

                                    @include('dept-head.view_mdr_status')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('dept-head.new_mdr')
@include('components.footer')

@endsection

@push('scripts')

<!-- Jasny -->
<script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>
<script src="js/plugins/dataTables/datatables.min.js"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {
        $('#departmentKpiTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
        
        $("[name='department']").chosen({width: "100%"});

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