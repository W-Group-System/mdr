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

                    <div class="btn btn-primary" data-toggle="modal" data-target="#addModal">Add Department KPI</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Departments</th>
                                    <th>Departmental Goals</th>
                                    <th>Department KPI</th>
                                    <th>Target</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentKpi as $departmentKpiData)
                                    <tr>
                                        <td>{{ $departmentKpiData->department->dept_name }}</td>
                                        <td>{{ $departmentKpiData->departmentGroup->name }}</td>
                                        <td>{{ $departmentKpiData->name }}</td>
                                        <td>{{ $departmentKpiData->target }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $departmentKpiData->id }}">
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
    </div>
</div>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-left">Add Department KPI</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form role="form" method="post" id="addForm" action="/addDepartmentKpi">
                            @csrf
                            <div class="form-group">
                                <label>Departments</label>
                                <select name="department" id="department" class="form-control">
                                    <option value="">-Departments-</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}">{{ $departmentData->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department KPI Group</label>
                                <select name="departmentGroupKpi" class="form-control">
                                    <option value="">-Department Group KPI-</option>
                                    @foreach ($departmentGroupKpiList as $departmentGroupKpiData)
                                        <option value="{{ $departmentGroupKpiData->id }}">{{ $departmentGroupKpiData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department KPI</label>
                                <textarea name="kpiName" id="" class="form-control" cols="30" rows="10" placeholder="Enter KPI"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <textarea name="target" id="" class="form-control" cols="30" rows="10" placeholder="Enter Target"></textarea>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-rounded btn-block">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($departmentKpi as $departmentKpiData)
<div class="modal fade" id="editModal-{{ $departmentKpiData->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-left">Edit Department KPI</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form method="post" action="/updateDepartmentsKpi/{{ $departmentKpiData->id }}" role="form">
                            @csrf
                            <div class="form-group">
                                <label>Departments</label>
                                <select name="department" id="department" class="form-control">
                                    <option value="">-Departments-</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}" {{ $departmentData->id == $departmentKpiData->department_id ? 'selected' : '' }}>{{ $departmentData->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department KPI Group</label>
                                <select name="departmentGroupKpi" class="form-control">
                                    <option value="">-Department Group KPI-</option>
                                    @foreach ($departmentGroupKpiList as $departmentGroupKpiData)
                                        <option value="{{ $departmentGroupKpiData->id }}" {{ $departmentGroupKpiData->id == $departmentKpiData->id ? 'selected' : '' }}>{{ $departmentGroupKpiData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department KPI</label>
                                <textarea name="kpiName" id="" class="form-control" cols="30" rows="10" placeholder="Enter KPI">{{ $departmentKpiData->name }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <textarea name="target" id="" class="form-control" cols="30" rows="10" placeholder="Enter Target">{{ $departmentKpiData->target }}</textarea>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-rounded btn-block">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<!-- Mainly scripts -->
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