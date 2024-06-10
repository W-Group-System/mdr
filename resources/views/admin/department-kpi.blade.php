@extends('layouts.app')

@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <form action="" method="get" enctype="multipart/form-data" onsubmit="show()">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="department" id="departmentFilter" class="form-control">
                                    <option value="">- Departments -</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}" {{ $department == $departmentData->id ? 'selected' : '' }}>{{ $departmentData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <button class="btn btn-sm btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    

                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add MDR Setup
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
                        <table class="table table-striped table-bordered table-hover" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Departments</th>
                                    <th>MDR Groups</th>
                                    <th>Department KPI</th>
                                    <th>Target</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentKpi as $departmentKpiData)
                                    <tr>
                                        <td>{{ $departmentKpiData->departments->name }}</td>
                                        <td>{{ $departmentKpiData->departmentGroup->name}}</td>
                                        <td>{!! nl2br($departmentKpiData->name) !!}</td>
                                        <td>{!! nl2br($departmentKpiData->target) !!}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $departmentKpiData->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>

                                            <form action="{{ url('deleteDepartmentKpi/'.$departmentKpiData->id) }}" method="post" onsubmit="show()">
                                                @csrf

                                                <button class="btn btn-sm btn-danger">
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
    </div>
</div>

<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-left">Add MDR Setup</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form role="form" method="post" id="addForm" action="{{url('addDepartmentKpi')}}" onsubmit="show()">
                            @csrf

                            <div class="form-group">
                                <label>Departments</label>
                                <select name="department" id="department" class="form-control">
                                    <option value="">-Departments-</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}">{{ $departmentData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>MDR Groups</label>
                                <select name="departmentGroupKpi" class="form-control">
                                    <option value="">-MDR Groups-</option>
                                    @foreach ($departmentGroupKpiList as $departmentGroupKpiData)
                                        <option value="{{ $departmentGroupKpiData->id }}">{{ $departmentGroupKpiData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department KPI</label>
                                <textarea name="kpiName" id="" class="form-control" cols="30" rows="10" placeholder="Enter KPI" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <textarea name="target" id="" class="form-control" cols="30" rows="10" placeholder="Enter Target" required></textarea>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary btn-rounded btn-block">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($departmentKpi as $departmentKpiData)
<div class="modal" id="editModal-{{ $departmentKpiData->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-left">Edit MDR Setup</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form method="post" action="{{url('updateDepartmentsKpi/'.$departmentKpiData->id)}}" role="form" onsubmit="show()">
                            @csrf
                            <div class="form-group">
                                <label>Departments</label>
                                <select name="department" id="department" class="form-control">
                                    <option value="">-Departments-</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}" {{ $departmentData->id == $departmentKpiData->department_id ? 'selected' : '' }}>{{ $departmentData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>MDR Groups</label>
                                <select name="departmentGroupKpi" class="form-control">
                                    <option value="">-MDR Groups-</option>
                                    @foreach ($departmentGroupKpiList as $departmentGroupKpiData)
                                        <option value="{{ $departmentGroupKpiData->id }}" {{ $departmentGroupKpiData->id == $departmentKpiData->mdr_group_id ? 'selected' : '' }}>{{ $departmentGroupKpiData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department KPI</label>
                                <textarea name="kpiName" id="" class="form-control input-sm" cols="30" rows="10" placeholder="Enter KPI">{{ $departmentKpiData->name }}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Target</label>
                                <textarea name="target" id="" class="form-control input-sm" cols="30" rows="10" placeholder="Enter Target">{{ $departmentKpiData->target }}</textarea>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary btn-rounded btn-block">Update</button>
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
        $("[name='departmentGroupKpi']").chosen({width: "100%"});

        // $("#departmentFilter").on('change', function() {
            
            
        // })
    })
</script>
@endpush