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

                    <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#monthModal">New MDR</button>

                    <div class="modal fade" id="monthModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Select a Month</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form action="{{ url('new-mdr') }}" method="get" target="_blank">
                                                <div class="form-group">
                                                    <input type="month" name="yearAndMonth" min="{{ date('Y-m', strtotime($yearAndMonth)) }}" max="{{ date('Y-m') }}" class="form-control input-sm" required>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-sm btn-primary btn-block" type="submit">Next</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Month</th>
                                    <th>KPI</th>
                                    <th>Process Development</th>
                                    <th>Innovation</th>
                                    <th>Timeliness</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrScoreList->kpi_scores as $item)
                                    <tr>
                                        <td>{{ $mdrScoreList->dept_name }} </td>
                                        <td>{{ date("F Y", strtotime($item->year . '-' . $item->month))}}</td>
                                        <td>{{ $item->score }}</td>
                                        <td>{{ !empty($item->pd_scores) ? number_format($item->pd_scores, 1) : '0.0' }}</td>
                                        <td>{{ !empty($item->innovation_scores) ? number_format($item->innovation_scores, 1) : '0.0' }}</td>
                                        <td>{{ $item->timeliness }}</td>
                                        <td>{{ $item->total_rating }}</td>
                                        <td>
                                            <form action="{{ url('edit_mdr') }}" method="get" target="_blank">
                                                
                                                <input type="hidden" name="yearAndMonth" value="{{ $item->year.'-'.$item->month }}">

                                                <button type="submit" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </form>
                                        </td>
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