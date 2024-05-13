@extends('layouts.app')
@section('css')
<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    @if (Session::has('errors'))
                        <div class="alert alert-danger">
                            @foreach (Session::get('errors') as $errors)
                                {{ $errors }}<br>
                            @endforeach
                        </div>
                    @endif

                    <a class="btn btn-sm btn-primary" target="_blank" href="{{ url('new-mdr') }}">New MDR</a>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Month</th>
                                    <th>KPI</th>
                                    {{-- <th>Business Plan</th> --}}
                                    <th>Process Development</th>
                                    <th>Innovation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $scoreList = $mdrScoreList->kpi_scores->sortBy('date');
                                @endphp
                                @foreach ($scoreList as $item)
                                    <tr>
                                        <td>{{ $mdrScoreList->dept_name }} </td>
                                        <td>{{ date('F, Y', strtotime($item->date)) }}</td>
                                        <td>{{ $item->score }}</td>
                                        <td>{{ !empty($item->pd_scores) ? number_format($item->pd_scores, 1) : '0.0' }}</td>
                                        <td>{{ !empty($item->innovation_scores) ? number_format($item->innovation_scores, 1) : '0.0' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.footer')

@endsection

@push('scripts')

<!-- Jasny -->
<script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>
<script src="js/plugins/dataTables/datatables.min.js"></script>

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
    })
</script>
@endpush