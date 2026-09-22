@extends('layouts.app')

@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <!-- Filter Box -->
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <form action="" method="get" onsubmit="show()">
                        <div class="row">
                            <!-- Department -->
                            <div class="col-lg-3">
                                <select name="department" id="departmentFilter" class="form-control">
                                    <option value="">- Departments -</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}" {{ ($department ?? '') == $departmentData->id ? 'selected' : '' }}>
                                            {{ $departmentData->code .' - '. $departmentData->name}}
                                        </option>                                        
                                    @endforeach
                                </select>
                            </div>

                            <!-- Months -->
                            <div class="col-lg-3">
                                <select name="month" id="monthFilter" class="form-control">
                                    <option value="">- All Months -</option>
                                    @foreach (range(1, 12) as $m)
                                        @php
                                            $monthValue = str_pad($m, 2, '0', STR_PAD_LEFT); 
                                            $monthName = date('F', mktime(0, 0, 0, $m, 1)); 
                                        @endphp
                                        <option value="{{ $monthValue }}"
                                            {{ ($selectedMonth ?? '') == $monthValue ? 'selected' : '' }}>
                                            {{ $monthName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Year -->
                            <div class="col-lg-2">
                                <select name="year" id="yearFilter" class="form-control">
                                    <option value="">- All Years -</option>
                                    @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                        <option value="{{ $y }}" {{ ($selectedYear ?? '') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            
                            <!-- Button filter -->
                            <div class="col-lg-2">
                                <button class="btn btn-sm btn-primary" type="submit">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Content Table -->
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if (check_access('Department KPI', 'create'))
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                            <span><i class="fa fa-plus"></i></span>&nbsp;
                            Add MDR Setup
                        </button>
                    @endif
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentKpiTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Departments</th>
                                    <th>Date</th>
                                    <th>Total KPI</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                           <tbody>
                                @foreach ($department_kpis as $row)
                                    <tr>
                                        <td>
                                            <a href="{{ route('kpi.view', [
                                                'department' => $row->department_id,
                                                'month' => $row->month,
                                                'year' => $row->year
                                            ]) }}" 
                                            class="btn btn-xs btn-info" 
                                            title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>{{ $row->department_code }} - {{ $row->department_name }}</td>
                                        <td>{{ $row->year }} - {{ $row->month }}</td>
                                        <td>
                                            <strong>{{ $row->total_kpi }}</strong>
                                        </td>
                                        <td>
                                            <span class="label label-success">Active</span>
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
@endsection

@push('scripts')
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>
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
        $("[name='month']").chosen({width: "100%"});
        $("[name='year']").chosen({width: "100%"});
        $("[name='departmentGroupKpi']").chosen({width: "100%"});
    })
</script>
@endpush