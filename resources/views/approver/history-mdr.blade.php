@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="" method="get" enctype="multipart/form-data" onsubmit="show()">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="department" id="department" class="form-control">
                                    <option value="">- Department -</option>
                                    @foreach ($department_list as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm">
                            </div>
                            <div class="col-lg-3">
                                <button class="btn btn-sm btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        @if($department_list && $year_and_month)
        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="kpiScores">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>KPI</th>
                                    <th>Process Improvement</th>
                                    <th>Innovation</th>
                                    <th>Timeliness</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdr_score as $score)
                                    <tr>
                                        <td>{{ date('F Y', strtotime($score->yearAndMonth)) }}</td>
                                        <td>{{ $score->score }}</td>
                                        <td>{{ $score->pd_scores }}</td>
                                        <td>{{ $score->innovation_scores }}</td>
                                        <td>{{ $score->timeliness }}</td>
                                        <td>{{ $score->total_rating }}</td>
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
                <div class="ibox-title">
                    <p>Departmental Goals</p>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="departmentalGoals">
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
                                @foreach ($departmental_goals as $departmentalGoals)
                                    <tr>
                                        <td>{!! nl2br(e($departmentalGoals->kpi_name)) !!}</td>
                                        <td>{!! nl2br(e($departmentalGoals->target)) !!}</td>
                                        <td>{{ $departmentalGoals->grade }}</td>
                                        <td>{{ $departmentalGoals->actual }}</td>
                                        <td>{{ $departmentalGoals->remarks }}</td>
                                        <td width="10">
                                            @foreach ($departmentalGoals->attachments as $key=>$attachment)
                                                <div>
                                                    <span><strong>{{ $key+1 }}</strong>. &nbsp;</span>
                                                    <a href="{{ url($attachment->file_path) }}" target="_blank">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </td>
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
                <div class="ibox-title">
                    <p>Innovation</p>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="innovationTable">
                            <thead>
                                <tr>
                                    <th>Innovations / Projects</th>
                                    <th>Project Summary</th>
                                    <th>Job / Work Order Number</th>
                                    <th>Start Date</th>
                                    <th>Target Date of Completion</th>
                                    <th>Actual Date of Completion</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($innovation as $innovationData)
                                    <tr>
                                        <td>{{ $innovationData->projects }}</td>
                                        <td>{{ $innovationData->project_summary }}</td>
                                        <td>{{ $innovationData->work_order_number }}</td>
                                        <td>{{ date('F m, Y', strtotime($innovationData->start_date)) }}</td>
                                        <td>{{ date('F m, Y', strtotime($innovationData->target_date)) }}</td>
                                        <td>{{ date('F m, Y', strtotime($innovationData->actual_date)) }}</td>
                                        <td>{{ $innovationData->remarks }}</td>
                                        <td width="10">
                                            @foreach ($innovationData->innovationAttachments as $key=>$file)
                                                <div>
                                                    <span><strong>{{ $key+1 }}</strong>. &nbsp;</span>

                                                    <a href="{{ url($file->filepath) }}" target="_blank">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </td>
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
                <div class="ibox-title">
                    <p>Process Improvement</p>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="processDevelopmentTable">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Accomplished Date</th>
                                    <th>Remarks</th>
                                    <th>Attachments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($process_improvement as $item)
                                    <tr>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ date('F d, Y', strtotime($item->accomplished_date)) }}</td>
                                        <td>{{ $item->remarks }}</td>
                                        <td width="10">
                                            @foreach ($item->pdAttachments as $key=>$file)
                                                <div>
                                                    <span><strong>{{ $key+1 }}</strong>. &nbsp;</span>

                                                    <a href="{{ url($file->filepath) }}" target="_blank">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </td>
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

        $('#departmentalGoals').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });

        $('#kpiScores').DataTable({
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
