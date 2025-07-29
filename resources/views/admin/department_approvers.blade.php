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
                    <h5>Department Approvers</h5>
                    <div class="pull-right">
                        <small class="label label-success">as of {{ date('Y-m-d') }}</small>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($department_approvers)}}</h1>
                    <small>Total Departments</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                    <div class="pull-right">
                        <small class="label label-primary">as of {{ date('Y-m-d') }}</small>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($department_approvers->where('status', "Active"))}}</h1>
                    <small>Total Active</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Inactive</h5>
                    <div class="pull-right">
                        <small class="label label-danger">as of {{ date('Y-m-d') }}</small>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($department_approvers->where('status', "Inactive"))}}</h1>
                    <small>Total Inactive</small>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#new">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Department Approvers
                    </button>
                </div>

                <div class="ibox-content">
                    @include('components.error')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($department_approvers as $department_approver)
                                    <tr>
                                        <td>
                                            <button type="submit" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit{{ $department_approver->id }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            @if($department_approver->status == "Active")
                                            <form method="post" action="{{ url('deactivate-department-approvers/'.$department_approver->id) }}" onsubmit="show()"  style="display: inline-block;">
                                                @csrf 
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form method="post" action="{{ url('activate-department-approvers/'.$department_approver->id) }}" onsubmit="show()"  style="display: inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>{{ $department_approver->user->name }}</td>
                                        <td>{{ $department_approver->status_level }}</td>
                                        <td>
                                            @if($department_approver->status == "Active")
                                            <span class="label label-primary">Active</span>
                                            @else
                                            <span class="label label-danger">Inactive</span>
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

@include('admin.new_department_approvers')

@foreach ($department_approvers as $department_approver)
@include('admin.edit_department_approvers')
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
    })
</script>
@endpush