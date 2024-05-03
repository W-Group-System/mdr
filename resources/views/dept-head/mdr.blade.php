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

    @foreach ($departmentalGoalsList as $departmentalGoalsData)
        @include('dept-head.departmental-goals', array('departmentalGoalsList' => $departmentalGoalsList))
    @endforeach

    {{-- @include('dept-head.innovation', array('innovationList' => $innovationList, 'innovationCount' => $innovationCount)) --}}
    {{-- @include('dept-head.business-plan', array('businessPlanList' => $businessPlanList, 'businessPlanCount' => $businessPlanCount)) --}}
    {{-- @include('dept-head.ongoing-innovation', array('ongoingInnovationList' => $ongoingInnovationList, 'ongoingInnovationCount' => $ongoingInnovationCount)) --}}
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

@endpush
