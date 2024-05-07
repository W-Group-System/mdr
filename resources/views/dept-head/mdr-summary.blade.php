@extends('layouts.app')
{{-- @section('css')
<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection --}}

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

                    {{-- <a class="btn btn-sm btn-primary" target="_blank" href="{{ url('new-mdr') }}">New MDR</a>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Month</th>
                                    <th>KPI</th>
                                    <th>Business Plan</th>
                                    <th>Innovation</th>
                                    <th>Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrScoreList as $mdrScoreData)
                                    @php
                                        $scoreList = $mdrScoreData->kpi_scores->sortBy('date');
                                    @endphp
                                    @foreach ($scoreList as $item)
                                        <tr>
                                            <td>{{ $mdrScoreData->dept_name }}</td>
                                            <td>{{ date('F', strtotime($item->date)) }}</td>
                                            <td>{{ $item->score }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.footer')

@endsection