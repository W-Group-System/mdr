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
                   <form action="" method="get" onsubmit="show()">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="department" id="departmentFilter" class="form-control">
                                    <option value="">- Departments -</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}" {{ $department == $departmentData->id ? 'selected' : '' }}>
                                            {{ $departmentData->code .' - '. $departmentData->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <div class="col-lg-3">
                                <select name="month" id="monthFilter" class="form-control">
                                    @foreach (range(1, 12) as $m)
                                        @php
                                            $monthValue = str_pad($m, 2, '0', STR_PAD_LEFT); // e.g. 01, 02, ..., 12
                                            $monthName = date('F', mktime(0, 0, 0, $m, 1));   // e.g. January, February
                                        @endphp
                                        <option value="{{ $monthValue }}"
                                            {{ ($selectedMonth ?? date('m')) == $monthValue ? 'selected' : '' }}>
                                            {{ $monthName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                            {{-- <div class="col-lg-2">
                                <select name="year" id="yearFilter" class="form-control">
                                    @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                        <option value="{{ $y }}" {{ ($selectedYear ?? date('Y')) == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div> --}}

                            <div class="col-lg-2">
                                <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                    <div class="pull-right">
                        <span class="label label-success">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1>{{count($department_kpis->where('status',"Active"))}}</h1>
                    <small>Total Active</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Deactivate</h5>
                    <div class="pull-right">
                        <span class="label label-danger">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1>{{count($department_kpis->where('status',"Inactive"))}}</h1>
                    <small>Total Deactivate</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if(check_access('Department KPI', 'create'))
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add MDR Setup
                    </button>
                    @endif
                </div>
                <div class="ibox-content">
                    @include('components.error')
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Departments</th>
                                    <th>Department KPI</th>
                                    <th>Target</th>
                                    <th>Attachment Needed</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($department_kpis as $department_kpi)
                                    <tr>
                                        <td>
                                            @if(check_access('Department KPI', 'update'))
                                                @if($department_kpi->status != "Inactive")
                                                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{ $department_kpi->id }}">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </button>
                                                    
                                                    <form action="{{url('deactivate_mdr_setup/'.$department_kpi->id)}}" method="post" onsubmit="show()" style="display: inline-block;">
                                                        @csrf

                                                        <button type="submit" class="btn btn-sm btn-danger" title="Deactivate">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>

                                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#duplicateModal{{ $department_kpi->id }}">
                                                        <i class="fa fa-copy"></i>
                                                    </button>
                                                @else
                                                <form action="{{ url('activate_mdr_setup/'.$department_kpi->id) }}" method="post" onsubmit="show()" style="display: inline-block;">
                                                    @csrf

                                                    <button class="btn btn-sm btn-success" type="submit" title="Activate">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $department_kpi->department->name }}</td>
                                        <td>{!! nl2br($department_kpi->name) !!}</td>
                                        <td>{!! nl2br($department_kpi->target) !!}</td>
                                        <td>{{ $department_kpi->attachment_description }}</td>
                                        <td>
                                            <div class="label label-{{$department_kpi->status == "Inactive" ? 'danger' : 'primary'}}">{{$department_kpi->status == "Inactive" ? 'Inactive' : 'Active'}}</div>
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

@include('admin.new_mdr_setup')
@foreach ($department_kpis as $department_kpi)
@include('admin.edit_mdr_setup')
@include('admin.copy_mdr_setup')
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
    })
</script>
@endpush