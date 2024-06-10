@extends('layouts.app')
@section('content')

@section('css')
<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<link href="css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="css/plugins/dropzone/dropzone.css" rel="stylesheet">
<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<!-- Sweet Alert -->
<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

<div class="wrapper wrapper-content">
    <div class="row">
        <h1 class="text-center">{{ date('F Y', strtotime($yearAndMonth)) }}</h1>
        @foreach ($departmentKpiGroup as $dptGroupData)
            @if($dptGroupData->name == "Departmental Goals")
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-title">
                            <p><strong>I.</strong>{{ $dptGroupData->name }}</p>
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <form action="{{ url('create') }}" method="post" onsubmit="show()">
                                    @csrf
                                    
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>KPI</th>
                                                <th>Target</th>
                                                <th>Actual</th>
                                                <th>Grade</th>
                                                <th>Remarks</th>
                                                <th>Attachments</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dptGroupData->departmentalGoals as $dptGoals)
                                                <tr>
                                                    <input type="hidden" name="mdr_setup_id[]" value="{{ $dptGoals->mdr_setup_id }}">
                                                    <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                                    <td width="300">{!! nl2br(e($dptGoals->kpi_name)) !!}</td>
                                                    <td width="300">{!! nl2br(e($dptGoals->target)) !!}</td>
                                                    <td>
                                                        <textarea name="actual[]" id="actual" cols="30" rows="10" class="form-control" placeholder="Input an actual" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} required>{{ $dptGoals->actual }}</textarea>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="grade[]" id="grade" class="form-control input-sm" value="{{ $dptGoals->grade }}" placeholder="Input grade (use percentage)" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} maxlength="3"  required>
                                                    </td>
                                                    <td>
                                                        <textarea name="remarks[]" id="remarks" cols="30" rows="10" class="form-control" placeholder="Input a remarks" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} required>{{ $dptGoals->remarks }}</textarea>
                                                    </td>
                                                    <td width="10">
                                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $dptGoals->mdr_setup_id }}" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} >
                                                            <i class="fa fa-upload"></i>
                                                        </button>

                                                        @foreach ($dptGroupData->mdrSetup as $dptKpi)
                                                            @if($dptKpi->id == $dptGoals->mdr_setup_id)
                                                                <div class="kpi-attachment-container-{{ $dptKpi->id }}">
                                                                    @foreach ($dptKpi->attachments as $attachment)
                                                                        @if($dptGoals->mdr_setup_id == $attachment->mdr_setup_id)
                                                                            <div class="attachment-kpi-{{ $attachment->id }}">
                                                                                <a href="{{ url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </a>
    
                                                                                <button type="button" class="btn btn-sm btn-danger" name="deleteKpiAttachments" data-id="{{ $attachment->id }}" {{ $dptGoals->status_level != 0 ? 'disabled' : '' }} >
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button class="btn btn-sm btn-primary pull-right" type="submit">Update KPI</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($dptGroupData->mdrSetup as $item)
                    <div class="modal uploadModal" id="uploadModal-{{ $item->id }}">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Attachments</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label>File Upload</label>
                                            <form action="{{ url('uploadAttachments/'. $item->id) }}" method="post" class="uploadKpiAttachmentForm" enctype="multipart/form-data">
                                                @csrf

                                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                                <div class="form-group">
                                                    <input type="file" name="file[]" id="file" class="form-control" multiple required>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-sm btn-primary btn-block" type="submit">Add Files</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if($dptGroupData->name == "Innovations (Accomplished)")
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-title">
                            <p><b>II:</b> <span class="period">{{ $dptGroupData->name }}</span></p>
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                                <span><i class="fa fa-plus"></i></span>&nbsp;
                                Add Innovation
                            </button>
                        </div>
                        <div class="ibox-content">
                            @if (Session::has('errors'))
                                <div class="alert alert-danger">
                                    @foreach (Session::get('errors') as $errors)
                                        {{ $errors }}<br>
                                    @endforeach
                                </div>
                            @endif
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
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dptGroupData->innovation as $innovationData)
                                            <tr>
                                                <td>{{ $innovationData->projects }}</td>
                                                <td>{{ $innovationData->project_summary }}</td>
                                                <td>{{ $innovationData->work_order_number }}</td>
                                                <td>{{ date('F d, Y', strtotime($innovationData->start_date)) }}</td>
                                                <td>{{ date('F d, Y', strtotime($innovationData->target_date)) }}</td>
                                                <td>{{ date('F d, Y', strtotime($innovationData->actual_date)) }}</td>
                                                <td>{{ $innovationData->remarks }}</td>
                                                <td width="10">
                                                    @foreach ($innovationData->innovationAttachments as $key=>$file)
                                                        <div class="innovation-attachments-{{ $file->id }}">
                                                            <a href="{{ asset('file/' . $file->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            
                                                            <button class="btn btn-sm btn-danger" name="deleteAttachments" type="button" data-id="{{ $file->id }}" id="deleteAttachments" {{ $innovationData->status_level != 0 ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td width="10">
                                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $innovationData->id }}" {{ $innovationData->status_level != 0 ? 'disabled' : '' }}>
                                                        <i class="fa fa-pencil"></i>
                                                    </button>

                                                    <form action="{{ url('deleteInnovation/' . $innovationData->id) }}" method="post" onsubmit="show()">
                                                        @csrf

                                                        <input type="hidden" name="department_id" value="{{ $innovationData->department_id }}">
                                                        <input type="hidden" name="yearAndMonth" value="{{ $innovationData->year.'-'.$innovationData->month }}">

                                                        <button type="submit" class="btn btn-sm btn-danger" {{ $innovationData->status_level != 0 ? 'disabled' : '' }}>
                                                            <i class="fa fa-trash"></i>
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

                <div class="modal" id="addModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title">Add Innovation</h1>
                            </div>
                            <div class="modal-body p-4" >
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="{{ url('addInnovation') }}" method="post" enctype="multipart/form-data" autocomplete="off" id="innovationForm" onsubmit="show()">
                                            @csrf

                                            <input type="hidden" name="mdr_group_id" value="{{ $dptGroupData->id }}">
                                            <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                            <div class="form-group">
                                                <label for="innovationProjects">Innovation Projects</label>
                                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="projectSummary">Project Summary</label>
                                                <textarea name="projectSummary" cols="30" rows="10" class="form-control" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="jobOrWorkNum">Job / Work Number</label>
                                                <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" required>
                                            </div>
                                            <div class="form-group" id="startDate">
                                                <label for="startDate">Start Date</label>
                                                <input type="date" class="form-control input-sm" name="startDate" required>
                                            </div>
                                            <div class="form-group" id="targetDate">
                                                <label for="targetDate">Target Date</label>
                                                <input type="date" class="form-control input-sm" name="targetDate" required>
                                            </div>
                                            <div class="form-group" id="actualDate">
                                                <label for="actualDate">Actual Date</label>
                                                <input type="date" class="form-control input-sm" name="actualDate" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="file">Supporting Documents</label>
                                                <input type="file" name="file[]" id="file" class="form-control" multiple>
                                            </div>
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10" required></textarea>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-sm btn-primary btn-block" type="submit">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($dptGroupData->innovation as $innovationData)
                <div class="modal" id="editModal-{{ $innovationData->id }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title">Edit Innovations</h1>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form action="/updateInnovation/{{ $innovationData->id }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                                            @csrf
                                            
                                            <input type="hidden" name="mdr_group_id" value="{{ $dptGroupData->id }}">

                                            <div class="form-group">
                                                <label for="innovationProjects">Innovation Projects</label>
                                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" value="{{ $innovationData->projects }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="projectSummary">Project Summary</label>
                                                <textarea name="projectSummary" cols="30" rows="10" class="form-control" required>{{ $innovationData->project_summary }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="jobOrWorkNum">Job / Work Number</label>
                                                <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" value="{{ $innovationData->work_order_number }}" required>
                                            </div>
                                            <div class="form-group" id="startDate">
                                                <label for="startDate">Start Date</label>
                                                <input type="date" class="form-control input-sm" name="startDate" value="{{ $innovationData->start_date }}" required>
                                            </div>
                                            <div class="form-group" id="targetDate">
                                                <label for="targetDate">Target Date</label>
                                                <input type="date" class="form-control input-sm" name="targetDate" value="{{ $innovationData->target_date }}" required>
                                            </div>
                                            <div class="form-group" id="actualDate">
                                                <label for="actualDate">Actual Date</label>
                                                <input type="date" class="form-control input-sm" name="actualDate" value="{{ $innovationData->actual_date }}" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="file">Supporting Documents</label>
                                                <input type="file" name="file[]" id="file" class="form-control" multiple>
                                            </div>
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10" required>{{ $innovationData->remarks }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-sm btn-primary btn-block" type="submit">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

            @if($dptGroupData->name == "Process Improvement")
                <div class="col-lg-12">
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-title">
                            <p><b>III:</b> <span class="period">{{ $dptGroupData->name }}</span></p>
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addProcessDevelopment">
                                <span><i class="fa fa-plus"></i></span>&nbsp;
                                Add Process Improvement
                            </button>
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                @if (Session::has('pdError'))
                                    <div class="alert alert-danger">
                                        @foreach (Session::get('pdError') as $errors)
                                            {{ $errors }}<br>
                                        @endforeach
                                    </div>
                                @endif

                                <table class="table table-bordered table-hover" id="processDevelopmentTable">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Accomplished Date</th>
                                            <th>Remarks</th>
                                            <th>Attachments</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dptGroupData->processDevelopment as $processDevelopmentData)
                                            <tr>
                                                <td>{{ $processDevelopmentData->description }}</td>
                                                <td>{{ date('F d, Y', strtotime($processDevelopmentData->accomplished_date )) }}</td>
                                                <td>{{ $processDevelopmentData->remarks }}</td>
                                                <td width="10">
                                                    @foreach ($processDevelopmentData->pdAttachments as $key=>$pdFile)
                                                        <div class="pd-attachments-{{ $pdFile->id }}">
                                                            <a href="{{ $pdFile->filepath }}" class="btn btn-sm btn-info" target="_blank">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            
                                                            <button type="button" class="btn btn-sm btn-danger deletePdAttachments" data-id="{{ $pdFile->id }}" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td width="10">
                                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editPdModal-{{ $processDevelopmentData->id }}" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                                        <i class="fa fa-pencil"></i>
                                                    </button>

                                                    <form action="{{ url('deleteProcessDevelopment/' . $processDevelopmentData->id) }}" method="post" onsubmit="show()">
                                                        @csrf

                                                        <input type="hidden" name="department_id" value="{{ $processDevelopmentData->department_id }}">
                                                        <input type="hidden" name="yearAndMonth" value="{{ $processDevelopmentData->year.'-'.$processDevelopmentData->month }}">

                                                        <button type="submit" class="btn btn-sm btn-danger" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                                            <i class="fa fa-trash"></i>
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

                    <div class="modal" id="addProcessDevelopment">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Process Improvement</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form action="{{ url('addProcessDevelopment') }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                                                @csrf
                    
                                                <input type="hidden" name="dptGroup" value="{{ $dptGroupData->id }}">
                                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <input type="text" name="description" id="description" class="form-control input-sm" required>
                                                </div>
                                                <div class="form-group" id="accomplishedDate">
                                                    <label for="accomplishedDate">Accomplished Date</label>
                                                    <input type="date" class="form-control input-sm" name="accomplishedDate" autocomplete="off" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="file">Upload an Attachments</label>
                                                    <input type="file" name="file[]" id="file" class="form-control" multiple required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="remarks">Remarks</label>
                                                    <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-sm btn-primary btn-block">Add</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($dptGroupData->processDevelopment as $pd)
                        <div class="modal" id="editPdModal-{{ $pd->id }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Edit Process Improvement</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ url('updateProcessDevelopment/' . $pd->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                                                    @csrf
                                                    
                                                    <input type="hidden" name="pd_id" value="{{ $pd->id }}">

                                                    <div class="form-group">
                                                        <label for="description">Description</label>
                                                        <input type="text" name="description" id="description" class="form-control input-sm" value="{{ $pd->description }}" required>
                                                    </div>
                                                    <div class="form-group" id="accomplishedDate">
                                                        <label for="accomplishedDate">Accomplished Date</label>
                                                        <input type="date" class="form-control input-sm" name="accomplishedDate" autocomplete="off" value="{{ $pd->accomplished_date }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="file">Upload an Attachments</label>
                                                        <input type="file" name="file[]" id="file" class="form-control" multiple>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="remarks">Remarks</label>
                                                        <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10" required>{{ $pd->remarks }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-sm btn-primary btn-block">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach

        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="processDevelopmentTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>MDR Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> {{ auth()->user()->name  }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdrStatusModal">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        @if(auth()->user()->role == "Department Head")
                                        <form action="{{ url('approveMdr') }}" method="post" onsubmit="show()">
                                            @csrf

                                            <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                                            
                                            <button class="btn btn-sm btn-primary" type="submit">Approve</button>
                                        </form>
                                        @else
                                        <form action="{{ url('submitMdr') }}" method="post" onsubmit="show()">
                                            @csrf

                                            <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                                            
                                            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="mdrStatusModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">MDR Status</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Approver</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($approver as $approverData)
                                                    @foreach ($approverData->mdrStatus as $item)
                                                        <tr>
                                                            <td>{{ $item->users->name }}</td>
                                                            <td>{{ $item->status == 1 ? 'APPROVED' : 'WAITING'}}</td>
                                                            <td>{{ !empty($item->start_date) ? date('F d, Y', strtotime($item->start_date)) : 'No Date' }}</td>
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>

<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- DROPZONE -->
<script src="{{ asset('js/plugins/dropzone/dropzone.js') }}"></script>
<!-- Jasny -->
<script src="{{ asset('js/plugins/jasny/jasny-bootstrap.min.js') }}"></script>
<!-- Sweet alert -->
<script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>

<script>
    $(document).ready(function() {
        $("[name='grade[]']").keypress(function(event) {
            if (event.keyCode == 8) {
                return
            }

            if (event.keyCode < 48 || event.keyCode > 57) {
                event.preventDefault(); 
            }   
        });

        $('#processDevelopmentTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $('#innovationTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $(".uploadKpiAttachmentForm").on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: formData,
                contentType: false,
                processData:false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    var appendHtml = ``;

                    $.each(res.filePath, function(key, path) {
                        appendHtml += `
                            <div class="attachment-kpi-${key}">
                                <a href="${path}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
    
                                <button type="button" class="btn btn-sm btn-danger" name="deleteKpiAttachments" data-id="${key}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        `
                    })

                    $(".kpi-attachment-container-"+res.id).append(appendHtml)

                    swal("SUCCESS", "Successfully Added.", "success");

                    $("#uploadModal-"+res.id).modal('hide');
                }
            })
        })

        $(document).on('click',"[name='deleteKpiAttachments']",function() {
            var id = $(this).data('id')
            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover your file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                
                $.ajax({
                    type: "POST",
                    url: "{{ url('deleteKpiAttachments') }}",
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        swal("Deleted!", "Your file has been deleted.", "success");

                        $('.attachment-kpi-'+id).remove()
                    }
                })
            });
        })

        $(".deletePdAttachments").on('click', function() {

            var id = $(this).data('id');

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover your file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: "POST",
                    url: "{{ url('deletePdAttachments') }}",
                    data: {
                        file_id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal("Deleted!", response.message, "success");

                        $('.pd-attachments-'+id).remove();
                    }
                })
            });
        })

        $("[name='deleteAttachments']").on('click', function() {
            var id = $(this).data('id');
            
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover your file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: "POST",
                    url: "{{ url('deleteAttachments') }}",
                    data: {
                        file_id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal("Deleted!", response.message, "success");

                        $(".innovation-attachments-" + id).remove();
                    }
                })
            });
        })
    })
</script>
@endpush