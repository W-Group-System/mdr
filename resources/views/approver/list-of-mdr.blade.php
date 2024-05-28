@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        @foreach ($data->approver as $approver)
            @if (auth()->user()->id == $approver->user_id)
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
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $kpiScores = $data->kpi_scores()
                                                ->where('status_level', $approver->status_level)
                                                ->where('year', date('Y', strtotime($yearAndMonth)))
                                                ->where('month', date('m', strtotime($yearAndMonth)))
                                                ->get();
                                        @endphp
                                        @if(count($kpiScores) > 0)
                                            @foreach ($kpiScores as $score)
                                                <form action="{{ url('submit_scores') }}" method="post" id="submitScoresForm">
                                                    @csrf

                                                    <input type="hidden" name="id" value="{{ $score->id }}">
                                                    <tr>
                                                        <td>{{ date('F Y', strtotime($score->year.'-'.$score->month)) }}</td>
                                                        <td><input type="text" name="kpiScores" class="form-control input-sm" value="{{ $score->score }}" {{ $approver->status_level != 1 ? 'disabled' : '' }}></td>
                                                        <td><input type="text" name="pdScores" class="form-control input-sm" value="{{ $score->pd_scores }}" {{ $approver->status_level != 1 ? 'disabled' : '' }}></td>
                                                        <td><input type="text" name="innovationScores" class="form-control input-sm" value="{{ $score->innovation_scores }}" {{ $approver->status_level != 1 ? 'disabled' : '' }}></td>
                                                        <td><input type="text" name="timelinessScores" class="form-control input-sm" value="{{ $score->timeliness }}" {{ $approver->status_level != 1 ? 'disabled' : '' }}></td>
                                                        <td><input type="text" name="ratingScores" class="form-control input-sm" value="{{ $score->total_rating }}" disabled></td>
                                                        <td>
                                                            <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10" placeholder="Input a remarks">{{ $score->remarks }}</textarea>
                                                        </td>
                                                    </tr>
                                                </form>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">No data available.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                @if($approver->status_level == 1)
                                    <button type="submit" class="btn btn-sm btn-primary pull-right" form="submitScoresForm">Submit Scores</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $departmentalGoals = $data->departmentalGoals()
                        ->where('year', date('Y', strtotime($yearAndMonth)))
                        ->where('month', date('m', strtotime($yearAndMonth)))
                        ->where('status_level', $approver->status_level)
                        ->get();

                    $processDevelopment = $data->process_development()
                        ->where('year', date('Y', strtotime($yearAndMonth)))
                        ->where('month', date('m', strtotime($yearAndMonth)))
                        ->where('status_level', $approver->status_level)
                        ->get();
    
                    $innovation = $data->innovation()
                        ->where('year', date('Y', strtotime($yearAndMonth)))
                        ->where('month', date('m', strtotime($yearAndMonth)))
                        ->where('status_level', $approver->status_level)
                        ->get();
                @endphp
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <p><strong>I.</strong>Departmental Goals</p>
                                <form action="{{ url('add_remarks') }}" method="post" id="addRemarksForm">
                                    @csrf
                                    
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
                                                                
                                                                <input type="hidden" name="year" value="{{ $item->year }}">
                                                                <input type="hidden" name="month" value="{{ $item->month }}">
                                                                <input type="hidden" name="department_id" value="{{ $item->department_id }}">
    
                                                                <textarea name="remarks[]" id="remarks" cols="30" rows="10" class="form-control">{{ $item->remarks }}</textarea>
                                                            </td>
                                                            <td width="10">
                                                                @foreach ($item->departmentKpi->attachments as $key => $attachment)
                                                                <div>
                                                                    <span><strong>{{ $key+1 }}</strong>.</span> 
                                                                    <a href="{{ asset('file/' . $attachment->file_name) }}" class="btn btn-sm btn-info" target="_blank">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                </div>
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
                                        @foreach ($innovation as $innovationData)
                                            <tr>
                                                <td>{{ $innovationData->projects }}</td>
                                                <td>{{ $innovationData->project_summary }}</td>
                                                <td>{{ $innovationData->work_order_number }}</td>
                                                <td>{{ date('F m, Y', strtotime($innovationData->start_date)) }}</td>
                                                <td>{{ date('F m, Y', strtotime($innovationData->target_date)) }}</td>
                                                <td>{{ date('F m, Y', strtotime($innovationData->actual_date)) }}</td>
                                                <td width="10">
                                                    @foreach ($innovationData->innovationAttachments as $key=>$file)
                                                        <div>
                                                            <span><strong>{{ $key+1 }}</strong>. &nbsp;</span>
                                                            <a href="{{ $file->filepath }}" class="btn btn-sm btn-info" target="_blank">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td width="10">
                                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#innovationRemarksModal-{{ $innovationData->id }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($innovation as $innovationData)
                <div class="modal fade" id="innovationRemarksModal-{{ $innovationData->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title">Add Remarks</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="{{ url('add_innovation_remarks') }}" method="post">
                                            @csrf

                                            <input type="hidden" name="id" value="{{ $innovationData->id }}">

                                            <div class="form-group">
                                                <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10">{{ $innovationData->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-block btn-primary">Add Remarks</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
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
                                        @foreach ($processDevelopment as $item)
                                            <tr>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ date('F d, Y', strtotime($item->accomplished_date)) }}</td>
                                                <td width="10">
                                                    @foreach ($item->pdAttachments as $key=>$file)
                                                        <div>
                                                            <span><strong>{{ $key+1 }}</strong>. &nbsp;</span>
                                                            <a href="{{ $file->filepath }}" class="btn btn-sm btn-info" target="_blank">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td width="10">
                                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#processDevelopmentRemarks-{{ $item->id }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($processDevelopment as $processDevelopmentData)
                <div class="modal fade" id="processDevelopmentRemarks-{{ $processDevelopmentData->id }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title">Add Remarks</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="{{ url('add_pd_remarks') }}" method="post">
                                            @csrf

                                            <input type="hidden" name="id" value="{{ $processDevelopmentData->id }}">
                                            <div class="form-group">
                                                <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10">{{ $processDevelopmentData->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-sm btn-block btn-primary">Add Remarks</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
                                                <form action="{{ url('approver_mdr') }}" method="post">
                                                    @csrf

                                                    <input type="hidden" name="monthOf" value="{{ $yearAndMonth }}">
                                                    <input type="hidden" name="department_id" value="{{ $department }}">

                                                    <button type="submit" class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#approveModal">Approve</button>
                                                </form>
    
                                                <form action="{{ url('return_mdr') }}" method="post">
                                                    @csrf

                                                    <input type="hidden" name="monthOf" value="{{ $yearAndMonth }}">
                                                    <input type="hidden" name="department_id" value="{{ $department }}">

                                                    <button type="submit" class="btn btn-sm btn-warning" type="button">Return</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
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
        
    })
</script>
@endpush
