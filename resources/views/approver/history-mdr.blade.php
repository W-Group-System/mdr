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
                    <form action="" method="get" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="department" id="department" class="form-control">
                                    <option value="">- Department -</option>
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
                            <table class="table table-bordered table-hover" id="kpiScores">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>KPI</th>
                                        <th>Process Development</th>
                                        <th>Innovation</th>
                                        <th>Timeliness</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($data->kpi_scores) > 0)
                                        @foreach ($data->kpi_scores as $score)
                                            <tr>
                                                <td>{{ date('F Y', strtotime($score->year.'-'.$score->month)) }}</td>
                                                <td>{{ $score->score }}</td>
                                                <td>{{ $score->pd_scores }}</td>
                                                <td>{{ $score->innovation_scores }}</td>
                                                <td>{{ $score->timeliness }}</td>
                                                <td>{{ $score->rating }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No data available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ibox float-e-margins" style="margin-top: 10px;">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <p><strong>I.</strong>Departmental Goals</p>
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
                                    @if(count($data->departmentalGoals) > 0)
                                        @foreach ($data->departmentalGoals as $departmentalGoals)
                                            <tr>
                                                <td>{{ $departmentalGoals->kpi_name }}</td>
                                                <td>{{ $departmentalGoals->target }}</td>
                                                <td>{{ $departmentalGoals->grade }}</td>
                                                <td>{{ $departmentalGoals->actual }}</td>
                                                <td>{{ $departmentalGoals->remarks }}</td>
                                                <td>
                                                    @foreach ($departmentalGoals->departmentKpi->attachments as $attachment)
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ibox float-e-margins" style="margin-top: 10px;">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <p><strong>II.</strong>Innovation</p>
                            <table class="table table-bordered table-hover" id="innovationTable">
                                <thead>
                                    <tr>
                                        <th>Innovations / Projects</th>
                                        <th>Project Summary</th>
                                        <th>Job / Work Order Number</th>
                                        <th>Start Date</th>
                                        <th>Target Date of Completion</th>
                                        <th>Actual Date of Completion</th>
                                        <th>Attachments</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->innovation as $innovationData)
                                        <tr>
                                            <td>{{ $innovationData->projects }}</td>
                                            <td>{{ $innovationData->project_summary }}</td>
                                            <td>{{ $innovationData->work_order_number }}</td>
                                            <td>{{ date('F m, Y', strtotime($innovationData->start_date)) }}</td>
                                            <td>{{ date('F m, Y', strtotime($innovationData->target_date)) }}</td>
                                            <td>{{ date('F m, Y', strtotime($innovationData->actual_date)) }}</td>
                                            <td>
                                                @foreach ($innovationData->innovationAttachments as $file)
                                                    <a href="{{ asset('file/' . $file->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endforeach
                                            </td>
                                            <td>{{ $innovationData->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="ibox float-e-margins" style="margin-top: 10px;">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <p><strong>III.</strong>Process Development</p>
    
                            <table class="table table-bordered table-hover" id="processDevelopmentTable">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Accomplished Date</th>
                                        <th>Attachments</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->process_development as $item)
                                        <tr>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ date('F d, Y', strtotime($item->accomplished_date)) }}</td>
                                            <td>
                                                <a href="{{ asset('file/' . $item->pd_attachments->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                            <td>{{ $item->remarks }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

        $('#innovationTable').DataTable({
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
