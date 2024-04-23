@extends('layouts.app')
@section('css')
<link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins" style="margin-top: 10px;">
            <div class="ibox-content">
                <div class="table-responsive">
                    <p><b>Period:</b> <span class="period">April 1 - 30, 2024</span></p>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Criteria</th>
                                <th>Value</th>
                                <th>Rating</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>KPI</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>Innovation</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>Business Plan</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>Timeliness</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td class="text-right"><b>MDR Score</b></td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
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
                    <p><b>I:</b> <span class="period">Departmental Goals</span></p>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>KPI</th>
                                <th>Target</th>
                                <th>Actual</th>
                                <th>Remarks</th>
                                <th>Attachments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departmentalGoalsList as $departmentalGoalsData) 
                                <tr>
                                    <td width="300">{{ $departmentalGoalsData->name }}</td>
                                    <td>{{ $departmentalGoalsData->target }}</td>
                                    <td>
                                        <form action="/addActual/{{ $departmentalGoalsData->id }}" method="post">
                                            @csrf
                                            <textarea name="actual" id="actual" cols="30" rows="10" class="form-control">{{ $departmentalGoalsData->actual }}</textarea>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="/addRemarks/{{ $departmentalGoalsData->id }}" method="post">
                                            @csrf
                                            <textarea name="remarks" id="remarks" cols="30" rows="10" class="form-control">{{ $departmentalGoalsData->remarks }}</textarea>
                                        </form>
                                    </td>
                                    <td width="100">
                                        <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $departmentalGoalsData->id }}">
                                            <i class="fa fa-upload"></i>
                                        </button>

                                        
                                        @if(!empty($departmentalGoalsData->file_name))
                                            <a href="{{ asset('file/' . $departmentalGoalsData->file_name) }}" class="btn btn-sm btn-primary" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                        
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
            <div class="ibox-content">
                <div class="table-responsive">
                    <p><b>II:</b> <span class="period">Innovations</span></p>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Innovations / Projects</th>
                                <th>Project Summary</th>
                                <th>Job / Work Order Number</th>
                                <th>Start Date</th>
                                <th>Target Date of Completion</th>
                                <th>Actual Date of Completion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($innovationList) > 0)
                                @foreach ($innovationList as $innovationData) 
                                    {{-- <tr>
                                        <td width="300">{{ $departmentalGoalsData->name }}</td>
                                        <td>{{ $departmentalGoalsData->target }}</td>
                                        <td>
                                            <form action="/addActual/{{ $departmentalGoalsData->id }}" method="post">
                                                @csrf
                                                <textarea name="actual" id="actual" cols="30" rows="10" class="form-control">{{ $departmentalGoalsData->actual }}</textarea>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="/addRemarks/{{ $departmentalGoalsData->id }}" method="post">
                                                @csrf
                                                <textarea name="remarks" id="remarks" cols="30" rows="10" class="form-control">{{ $departmentalGoalsData->remarks }}</textarea>
                                            </form>
                                        </td>
                                        <td width="100">
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $departmentalGoalsData->id }}">
                                                <i class="fa fa-upload"></i>
                                            </button>

                                            
                                            @if(!empty($departmentalGoalsData->file_name))
                                                <a href="{{ asset('file/' . $departmentalGoalsData->file_name) }}" class="btn btn-sm btn-primary" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endif
                                            
                                        </td>  
                                    </tr> --}}
                                @endforeach
                            @else
                                <td colspan="6" class="text-center">No data available.</td>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($departmentalGoalsList as $departmentalGoalsData)
<div class="modal fade" id="uploadModal-{{ $departmentalGoalsData->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Add Attachments</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <label>File Upload</label>
                        <form action="/uploadAttachments/{{ $departmentalGoalsData->id }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="fa fa-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">Select file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="file"/>
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div> 
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary pull-right" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach


@include('components.footer')

@endsection

@push('scripts')
<!-- Jasny -->
<script src="js/plugins/jasny/jasny-bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('[name="actual"]').on('change',function() {
            $(this).parent()[0].submit()
        })

        $('[name="remarks"]').on('change',function() {
            $(this).parent()[0].submit()
        })

    })

</script>
@endpush
