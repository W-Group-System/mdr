@extends('layouts.app')
@section('css')
<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="css/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<!-- Sweet Alert -->
<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
{{-- Chosen --}}
<link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="row">
    {{-- <div class="col-lg-12">
        <div class="ibox float-e-margins" style="margin-top: 10px;">
            <div class="ibox-content">
                <div class="table-responsive">
                    <p><b>Period:</b> <span class="period">April 1 - 30, 2024</span></p>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Criteria</th>
                                <th>Value</th>
                                <th>Rating</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>KPI</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>Innovation</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>Business Plan</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>Timeliness</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td class="text-right"><b>MDR Score</b></td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

    @foreach ($departmentKpi as $departmentKpiData)
        @include('dept-head.departmental-goals', array('departmentKpi' => $departmentKpi))
        @include('dept-head.process-development', array('departmentKpi' => $departmentKpi))
    @endforeach

    {{-- @foreach ($departmentKpi as $departmentKpiData)
    @endforeach --}}

    {{-- @include('dept-head.innovation', array('innovationList' => $innovationList, 'innovationCount' => $innovationCount)) --}}
    {{-- @include('dept-head.business-plan', array('businessPlanList' => $businessPlanList, 'businessPlanCount' => $businessPlanCount)) --}}
    {{-- @include('dept-head.ongoing-innovation', array('ongoingInnovationList' => $ongoingInnovationList, 'ongoingInnovationCount' => $ongoingInnovationCount)) --}}

    @if(auth()->user()->account_role == 2)
        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="processDevelopmentTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> {{ $deptHead->user->name }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#approveModal">Approve</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="approveModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Month of</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ url('approveMdr') }}" method="post">
                                    @csrf

                                    <div class="form-group">
                                        <label for="monthOf">Month</label>
                                        <input type="month" name="monthOf" id="monthOf" max="{{ date('Y-m') }}" class="form-control input-sm">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-primary pull-right">Approve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@include('components.footer')

@endsection

@push('scripts')
<!-- DROPZONE -->
<script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
<!-- Jasny -->
<script src="{{ asset('js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
<!-- Sweet alert -->
<script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script src="js/plugins/dataTables/datatables.min.js"></script>

<script>
$(document).ready(function() {
    $('#processDevelopmentTable').DataTable({
        pageLength: 10,
        ordering: false,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
    });
})

</script>

@endpush
