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
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrScoreList as $mdrScoreData)
                                    @php
                                        $pdScores = $mdrScoreData->process_development();
                                        $scoreList = $mdrScoreData->kpi_scores->sortBy('date');
                                    @endphp
                                    @foreach ($scoreList as $item)
                                        <tr>
                                            {{-- <td><a href="{{ url('mdr_summary/' . $item->id) }}" class="text">{{ $mdrScoreData->dept_name }}</a></td> --}}
                                            <td>{{ $mdrScoreData->dept_name }} </td>
                                            <td>{{ date('F', strtotime($item->date)) }}</td>
                                            <td>{{ $item->score }}</td>
                                            <td>{{ !empty($item->pd_scores) ? $item->pd_scores : '0.0' }}</td>
                                            <td>0.0</td>
                                            <td>0.0</td>
                                        </tr>
                                    @endforeach
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