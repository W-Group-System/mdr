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
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h1 class="text-center">{{auth()->user()->department->name}}</h1>

                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#monthModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        New MDR
                    </button>
                </div>

                <div class="ibox-content">
                    
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Month</th>
                                    <th>KPI</th>
                                    {{-- <th>Process Improvement</th> --}}
                                    <th>Innovation</th>
                                    <th>Timeliness</th>
                                    <th>Total Scores</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrs as $mdr)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#mdrStatusModal{{$mdr->id}}">
                                                <i class="fa fa-history"></i>
                                            </button>

                                            <form action="{{ url('edit_mdr') }}" method="get" style="display: inline-block;" onsubmit="show()">
                                                <input type="hidden" name="yearAndMonth" value="{{ $mdr->year.'-'.$mdr->month }}">
                                                <input type="hidden" name="mdr_id" value="{{ $mdr->id }}">

                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ date("F Y", strtotime($mdr->year.'-'.$mdr->month))}}</td>
                                        <td>{{ number_format($mdr->score,2) }}</td>
                                        <td>{{ number_format($mdr->innovation_scores,2) }}</td>
                                        <td>{{ number_format($mdr->timeliness,2) }}</td>
                                        <td>{{ number_format($mdr->score,2) }}</td>
                                        <td>
                                            @if($mdr->status == 'Pending')
                                            <span class="label label-warning">
                                            @elseif($mdr->status == 'Approved')
                                            <span class="label label-primary">
                                            @elseif($mdr->status == 'Returned')
                                            <span class="label label-danger">
                                            @endif
                                            {{ $mdr->status }}
                                            </span>
                                        </td>
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

        // $(".approveBtn").on('click', function() {
        //     var form = $(this).closest('form');

        //     swal({
        //         title: "Are you sure?",
        //         text: "The mdr will be submitted",
        //         type: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#DD6B55",
        //         confirmButtonText: "Yes, submit it!",
        //         closeOnConfirm: false
        //     }, function (){
        //         form.submit()
        //     });
        // })
    })
</script>
@endpush