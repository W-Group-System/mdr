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

                    <form action="" method="get" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3">
                                <select name="department" id="departmentFilter" class="form-control">
                                    <option value="">- Departments -</option>
                                    @foreach ($departmentList as $departmentData)
                                        <option value="{{ $departmentData->id }}" {{ $department == $departmentData->id ? 'selected' : '' }}>{{ $departmentData->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}" value="{{ $yearAndMonth }}">
                            </div>
                            <div class="col-lg-3">
                                <button class="btn btn-sm btn-primary">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($department && $yearAndMonth)
            <div class="col-lg-12">
                <div class="ibox float-e-margins" style="margin-top: 10px;">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            {{-- <p><b>Period:</b> <span class="period">April 1 - 30, 2024</span></p> --}}
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
                                    @foreach ($departmentKpiGroup as $data)
                                        @php
                                            $deptGoals = $data->departmentalGoals()->where('department_id', $department)
                                                ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), $yearAndMonth)
                                                ->where('status_level', 1)
                                                ->get();
                                        @endphp

                                        @foreach ($deptGoals as $item)
                                            <tr>
                                                <td width="300">{!! nl2br($item->kpi_name) !!}</td>
                                                <td width="300">{!! nl2br($item->target) !!}</td>
                                                <td>{{ $item->grade }}</td>
                                                <td>{{ $item->actual }}</td>
                                                <td>{{ $item->remarks }}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach

                                    @endforeach
                                    {{-- <tr>
                                        <td colspan="6" class="text-center">No data available.</td>
                                    </tr> --}}
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

<script>
    $(document).ready(function() {
        $("[name='department']").chosen({width: "100%"});
    })
</script>
@endpush
