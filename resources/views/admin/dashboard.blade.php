@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
    @if (auth()->user()->role == "Administrator")
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Users</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{ $totalUsers }}</h1>
                            <small>Total Users</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Departments</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">{{ $totalDepartments }}</h1>
                            <small>Total Departments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif(auth()->user()->role == "Approver")
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form action="" method="get">
                                <div class="row">
                                    {{-- <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="department">Department</label>
                                            <select name="department" id="department" class="form-control cat">
                                                <option value="">-Department-</option>
                                                @foreach ($listOfDepartment as $departmentData)
                                                    <option value="{{ $departmentData->id }}" {{ $departmentData->id == $department ? 'selected' : '' }}>{{ $departmentData->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="yearAndMonth">Year & Month</label>
                                            <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}" value="{{ $yearAndMonth }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">&nbsp;</label>
                                        <div class="form-group">
                                            <button class="btn btn-sm btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <canvas id="barChart" height="70"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form action="{{ url('print_pdf') }}" method="post" target="_blank">
                                @csrf

                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                {{-- <input type="hidden" name="startYearAndMonth" value="{{ $startYearAndMonth }}">
                                <input type="hidden" name="endYearAndMonth" value="{{ $endYearAndMonth }}"> --}}

                                <button type="submit" class="btn btn-sm btn-warning pull-right">
                                    <i class="fa fa-print"></i>
                                    &nbsp;
                                    <span class="bold">Print PDF</span>
                                </button>
                            </form>
                            <table class="table table-striped table-bordered table-hover" id="mdrStatusTable">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>KPI</th>
                                        <th>Innovation</th>
                                        <th>Process Improvement</th>
                                        <th>Timeliness</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mdrStatus as $data)
                                        <tr>
                                            <td>{{ $data['department'] }}</td>
                                            <td>{{ $data['action'] }}</td>
                                            <td>{{ $data['status'] }}</td>
                                            <td>{{ $data['deadline'] }}</td>
                                            <td>{{ $data['kpi'] }}</td>
                                            <td>{{ $data['innovation_scores'] }}</td>
                                            <td>{{ $data['pd_scores'] }}</td>
                                            <td>{{ $data['timeliness'] }}</td>
                                            <td>{{ $data['rate'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="department">Department</label>
                                            <select name="departmentValue" id="department" class="form-control cat" required>
                                                <option value="">-Department-</option>
                                                @foreach ($listOfDepartment as $departmentData)
                                                    <option value="{{ $departmentData->id }}" {{ $departmentData->id == $departmentValue ? 'selected' : '' }}>{{ $departmentData->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="year1">Years</label>
                                            <select name="years1" id="year1" class="form-control cat" required>
                                                <option value="">-Years-</option>
                                                @foreach ($years as $key=>$y)
                                                    <option value="{{$key}}" {{$key == $year1Val ? 'selected' : ''}}>{{$y}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="year2">Years</label>
                                            <select name="years2" id="year2" class="form-control cat" required>
                                                <option value="">-Years-</option>
                                                @foreach ($years as $key=>$y)
                                                    <option value="{{$key}}" {{$key == $year2Val ? 'selected' : ''}}>{{$y}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">&nbsp;</label>
                                        <div class="form-group">
                                            <button class="btn btn-sm btn-primary">Compare</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <canvas id="barChartDepartment" height="70"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <canvas id="yearChart2" height="70"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <table class="table table-striped table-bordered table-hover" id="">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mdrStatusOne as $ms1)
                                        <tr>
                                            <td>{{ $ms1['month'] }}</td>
                                            <td>{{ $ms1['status'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <table class="table table-striped table-bordered table-hover" id="">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mdrStatusTwo as $ms2)
                                        <tr>
                                            <td>{{ $ms2['month'] }}</td>
                                            <td>{{ $ms2['status'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->role == "Department Head" || auth()->user()->role == "Users")
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <select name="year" id="year" class="form-control cat">
                                            <option value="">-Years-</option>
                                            @foreach ($years as $key=>$year)
                                                <option value="{{$key}}" {{$key==$yearData?'selected':''}}>{{$year}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div>
                                <canvas id="barChart" height="140"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h3>Status in year </h3>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-striped table-bordered table-hover" id="">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($status as $statusData)
                                        <tr>
                                            <td>{{ $statusData['month'] }}</td>
                                            <td>{{ $statusData['status'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- @elseif (auth()->user()->role == "Human Resources")
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="yearAndMonth">Year & Month</label>
                                            <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}" value="{{ $yearAndMonth }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="">&nbsp;</label>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <canvas id="barChart" height="70"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="penaltiesTable">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Department Head</th>
                                            <th>Total Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mdrSummary as $mdrSummaryData)
                                            <tr>
                                                <td>{{ $mdrSummaryData->departments->name }}</td>
                                                <td>{{ $mdrSummaryData->departments->user->name }}</td>
                                                <td>{{ $mdrSummaryData->rate }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    @endif

@include('components.footer')

@endsection

@push('scripts')
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>
<!-- ChartJS-->
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>
@if(auth()->user()->role == "Approver")
<script>
    $(document).ready(function() {
        $(".cat").chosen({width: "100%"});

        var dept = {!! json_encode(array_keys($dashboardData)) !!}
        var data = {!! json_encode(array_values($dashboardData)) !!}

        var barData = {
            labels: dept,
            datasets: [
                {
                    label: "Total Rating in " + "{{ isset($yearAndMonth) ? date('F Y', strtotime($yearAndMonth)) : date('F Y') }}",
                    backgroundColor: 'rgba(26,179,148,0.5)',
                    borderColor: "rgba(26,179,148,0.7)",
                    pointBackgroundColor: "rgba(26,179,148,1)",
                    pointBorderColor: "#fff",
                    data: data
                }
            ]
        };

        var barOptions = {
            responsive: true
        };

        var ctx2 = document.getElementById("barChart").getContext("2d");
        new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
        
        var month1 = {!! json_encode(array_keys($yearOneArray)) !!};
        var data1 = {!! json_encode(array_values($yearOneArray)) !!};

        var barDataDepartment = {
            labels: month1,
            datasets: [
                {
                    label: "MDR data in year of {{$year1Val}}",
                    backgroundColor: 'rgba(26,179,148,0.5)',
                    data: data1
                }
            ]
        };

        var barOptionsDepartment = {
            responsive: true
        };

        var ctx2 = document.getElementById("barChartDepartment").getContext("2d");
        new Chart(ctx2, {type: 'bar', data: barDataDepartment, options:barOptionsDepartment});
        
        var month2 = {!! json_encode(array_keys($yearTwoArray)) !!};
        var data2 = {!! json_encode(array_values($yearTwoArray)) !!};

        var year2BarData = {
            labels: month2,
            datasets: [
                {
                    label: "MDR data in year of {{$year2Val}}",
                    backgroundColor: '#1C84C6',
                    data: data2
                }
            ]
        };

        var year2Options = {
            responsive: true
        };

        var ctx2 = document.getElementById("yearChart2").getContext("2d");
        new Chart(ctx2, {type: 'bar', data: year2BarData, options:year2Options});

        var yearAndMonth = "{{ $date }};"
        
        $('#mdrStatusTable').DataTable({
            pageLength: 10,
            responsive: true,
            ordering: false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                // {extend: 'csv'},
                {extend: 'excel', title: "MDR Summary for the month of {{ date('F Y', strtotime($yearAndMonth)) }}"},
            ]
        });

    })
</script>
@endif
@if(auth()->user()->role == "Department Head" || auth()->user()->role == "Users")
<script>
    $(".cat").chosen({width: "100%"});

    var month = {!! json_encode(array_keys($data)) !!}

    var data = {!! json_encode(array_values($data)) !!}

    var barData = {
        labels: month,
        datasets: [
            {
                label: "",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: data
            }
        ]
    };

    var barOptions = {
        responsive: true
    };

    var ctx2 = document.getElementById("barChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
</script>
@endif

{{-- @if(auth()->user()->role == "Human Resources")
<script>

    var department = {!! json_encode(array_keys($barData)) !!}
    var values = {!! json_encode(array_values($barData)) !!}
    
    var barData = {
        labels: department,
        datasets: [
            {
                label: "Total Rating in " + "{{ isset($yearAndMonth) ? date('F Y', strtotime($yearAndMonth)) : date('F Y') }}",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: values
            }
        ]
    };

    var barOptions = {
        responsive: true
    };

    var ctx2 = document.getElementById("barChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});

    $('#penaltiesTable').DataTable({
        pageLength: 10,
        ordering: false,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
    });
</script>
@endif --}}
@endpush
