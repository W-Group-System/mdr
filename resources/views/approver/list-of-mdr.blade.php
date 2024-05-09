@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
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

                    <form action="" method="get" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="department" id="departmentFilter" class="form-control">
                                    <option value="">- Departments -</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}" {{ $department == $departmentData->id ? 'selected' : '' }}>{{ $departmentData->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}" value="{{ $yearAndMonth }}">
                            </div>
                            <div class="col-lg-3">
                                <button class="btn btn-sm btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($department && $yearAndMonth)
            <div class="col-lg-12">
                <div class="ibox float-e-margins" style="margin-top: 10px;">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>KPI</th>
                                        <th>Process Development</th>
                                        <th>Innovation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $kpiScores)
                                        @php
                                            $kpiScores = $kpiScores->kpi_scores()
                                                ->where('status_level', 1)
                                                ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")') , $yearAndMonth)
                                                ->get();
                                        @endphp
                                        @if(count($kpiScores) > 0)
                                            @foreach ($kpiScores as $score)
                                                <tr>
                                                    <td>{{ date('F Y', strtotime($score->date)) }}</td>
                                                    <td>{{ $score->score }}</td>
                                                    <td>{{ $score->pd_scores }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No data available.</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($data as $department)
                @php
                    $departmentalGoals = $department->departmentalGoals()
                        ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
                        ->where('status_level', 1)
                        ->get();

                    $processDevelopment = $department->process_development()
                        ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
                        ->where('status_level', 1)
                        ->get();
                @endphp
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <p><strong>I.</strong>Departmental Goals</p>
                                <form action="{{ url('add_remarks') }}" method="post" id="addRemarksForm">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>KPI</th>
                                                <th>Target</th>
                                                <th>Grade</th>
                                                <th>Actual</th>
                                                <th>Remarks</th>
                                                <th>Attachments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($departmentalGoals) > 0)
                                                @foreach ($departmentalGoals as $item)
                                                        <tr>
                                                            <td width="300">{!! nl2br($item->kpi_name) !!}</td>
                                                            <td width="300">{!! nl2br($item->target) !!}</td>
                                                            <td>{{ $item->grade }}</td>
                                                            <td>{{ $item->actual }}</td>
                                                            <td width="200">
                                                                @csrf
                                                                
                                                                <input type="hidden" name="date" value="{{ $item->date }}">
                                                                <input type="hidden" name="department_id" value="{{ $item->department_id }}">

                                                                <textarea name="remarks[]" id="remarks" cols="30" rows="10" class="form-control">{{ $item->remarks }}</textarea>
                                                            </td>
                                                            <td>
                                                                @foreach ($item->departmentKpi->attachments as $attachment)
                                                                <a href="{{ asset('file/' . $attachment->file_name) }}" class="btn btn-sm btn-info" target="_blank">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                @endforeach
                                            @else 
                                                <tr>
                                                    <td colspan="6" class="text-center">No data available.</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                    <button class="btn btn-sm btn-primary pull-right" type="submit">Add Remarks</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <p><strong>II.</strong>Process Development</p>

                                <table class="table table-bordered table-hover" id="processDevelopmentTable">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Accomplished Date</th>
                                            <th>Attachments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($processDevelopment as $item)
                                            <tr>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ date('F d, Y', strtotime($item->accomplished_date)) }}</td>
                                                <td>
                                                    <a href="{{ asset('file/' . $item->pd_attachments->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

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
                                        <td> {{ auth()->user()->name  }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#approveModal">Approve</button>

                                            <button type="button" class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#returnModal">Return</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="returnModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">Month Of</h1>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form action="{{ url('return_mdr') }}" method="post">
                                        @csrf
                                        
                                        <input type="hidden" name="department_id" value="{{ $department->id }}">
                                        <div class="form-group">
                                            <label for="monthOf">Month</label>
                                            <input type="month" name="monthOf" id="monthOf" class="form-control input-sm" max="{{ date('Y-m') }}">
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-primary btn-block">Return</button>
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
</div>

    
@endsection

@push('scripts')
{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>
{{-- datatable --}}
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#processDevelopmentTable').DataTable({
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
