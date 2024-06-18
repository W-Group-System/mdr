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
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <form action="" method="get">

                        <div class="row">
                            <div class="col-md-3">
                                Year & Month:
                                <div class="form-group">
                                    <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" value="{{$yearAndMonth}}" max="{{date('Y-m')}}">
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
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <h1 class="text-center">{{$department->name}}</h1>
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#monthModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        New MDR
                    </button>
                </div>

                <div class="ibox-content">
                    <div class="modal" id="monthModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Select a Month</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form action="{{ url('new-mdr') }}" method="get">
                                                <div class="form-group">
                                                    <input type="month" name="yearAndMonth" min="{{ date("Y-m", strtotime("+1month", strtotime($yearAndMonth))) }}" max="{{ date('Y-m') }}" class="form-control input-sm" required>
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
                                    <th>Process Improvement</th>
                                    <th>Innovation</th>
                                    <th>Timeliness</th>
                                    <th>Rating</th>
                                    <th>Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kpiScore as $data)
                                    <tr>
                                        <td>{{ $data->departments->name }} </td>
                                        <td>{{ date("F Y", strtotime($data->year . '-' . $data->month))}}</td>
                                        <td>{{ $data->score }}</td>
                                        <td>{{ !empty($data->pd_scores) ? number_format($data->pd_scores, 1) : '0.0' }}</td>
                                        <td>{{ !empty($data->innovation_scores) ? number_format($data->innovation_scores, 1) : '0.0' }}</td>
                                        <td>{{ $data->timeliness }}</td>
                                        <td>{{ $data->total_rating }}</td>
                                        <td>{{ !empty($data->remarks) ? $data->remarks : 'No Remarks' }}</td>
                                        <td width="10">
                                            <form action="{{ url('edit_mdr') }}" method="get">
                                                
                                                <input type="hidden" name="yearAndMonth" value="{{ $data->year.'-'.$data->month }}">

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