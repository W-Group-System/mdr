@extends('layouts.app')

@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <style>
        .chosen-container {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Departments</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departmentList)}}</h1>
                    <small>Total Departments</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departmentList->where('status', "Active"))}}</h1>
                    <small>Total Active</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Inactive</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departmentList->where('status', "Inactive"))}}</h1>
                    <small>Total Inactive</small>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Department
                    </button>
                </div>

                <div class="ibox-content">
                    @include('components.error')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Department Code</th>
                                    <th>Department Name</th>
                                    <th>Department Head</th>
                                    <th>Target Date</th>
                                    <th>Approvers</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentList as $departmentData)
                                    <tr>
                                        <td>
                                            @if($departmentData->status == "Active")
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $departmentData->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            
                                            <form action="{{url('deactivate_department/'.$departmentData->id)}}" method="post" onsubmit="show()" style="display: inline-block;">
                                                @csrf

                                                <input type="hidden" name="status" value="0">

                                                <button class="btn btn-sm btn-danger" type="submit">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{url('activate_department/'.$departmentData->id)}}" method="post" role="form" onsubmit="show()" style="display: inline-block;">
                                                @csrf

                                                <input type="hidden" name="status" value="1">

                                                <button class="btn btn-sm btn-success" type="submit">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>{{ $departmentData->code }}</td>
                                        <td>{{ $departmentData->name }}</td>
                                        <td>{{optional($departmentData->user)->name}}</td>
                                        <td>
                                            @php
                                                $due_date = getOrdinal($departmentData->target_date);
                                            @endphp
                                            {{$due_date}}
                                        </td>
                                        <td>
                                            @foreach ($departmentData->approver as $approver)
                                                <small>{{ $approver->status_level .'. ' . $approver->user->name }}</small> <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($departmentData->status == "Active")
                                                <div class="label label-primary">Active</div>
                                            @else
                                                <div class="label label-danger">Inactive</div>
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
    </div>
</div>

@include('admin.new_department')

@foreach ($departmentList as $departmentData)
@include('admin.edit_department')
@endforeach

@include('components.footer')

@endsection

@push('scripts')
<script src="js/plugins/dataTables/datatables.min.js"></script>
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {
        $(".cat").chosen({width: "100%"});

        var userTable = $('#departmentTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
        

        $(".addApprover").on('click', function() {
            
            $(".approverFormGroup").append(`
                <select name="approver[]" id="" class="form-control approver" style="margin-bottom: 10px;" required="">
                    <option value=""></option>
                    @foreach($user->where('role', 'Approver') as $approverData)
                        <option value="{{ $approverData->id }}">{{ $approverData->name }}</option>
                    @endforeach
                </select>
            `)

            $(".approver").chosen({width: "100%"});
        })
    })
</script>
@endpush