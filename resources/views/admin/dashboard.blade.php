@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
    @if (Auth::user()->account_role == 0)
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

    @elseif(Auth::user()->account_role == 1)
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="department">Department</label>
                                            <select name="department" id="department" class="form-control">
                                                <option value="">-Department-</option>
                                                @foreach ($listOfDepartment as $departmentData)
                                                    <option value="{{ $departmentData->id }}" {{ $departmentData->id == $departmentValue ? 'selected' : '' }}>{{ $departmentData->dept_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="yearAndMonth">Year & Month</label>
                                            <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}" value="{{ $yearAndMonth }}">
                                        </div>
                                    </div> --}}
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="startYearAndMonth">Start Year & Month</label>
                                            <input type="month" name="startYearAndMonth" id="startYearAndMonth" class="form-control input-sm" value="{{ $startYearAndMonth }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="endYearAndMonth">End Year & Month</label>
                                            <input type="month" name="endYearAndMonth" id="endYearAndMonth" class="form-control input-sm" value="{{ $endYearAndMonth }}">
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
                            
                            <table class="table table-striped table-bordered table-hover" id="mdrStatusTable">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Action</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
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
                                            <td>{{ $data['rate'] }}</td>
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
                            <canvas id="barChartDepartment" height="70"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            {{-- <h3>Status in year {{ isset($years) ? $years : date('Y') }}</h3> --}}
                            <table class="table table-striped table-bordered table-hover" id="">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mdrSummaryStatusPerDept as $statusData)
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
    @elseif(Auth::user()->account_role == 2)
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form action="" method="get" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <input type="text" name="year" id="year" class="form-control input-sm" maxlength="4" value="{{ $years }}" placeholder="Enter a year">
                                    </div>
                                    <div class="col-lg-3">
                                        <button class="btn btn-sm btn-primary">Filter</button>
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
                        <div class="ibox-content">
                            <h3>Status in year {{ isset($years) ? $years : date('Y') }}</h3>
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
@if(Auth::user()->account_role == 1)
<script>
    $(document).ready(function() {
        $("[name='department']").chosen({width: "100%"});

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


        var month = {!! json_encode(array_keys($monthAndData)) !!}
        var data = {!! json_encode(array_values($monthAndData)) !!}
        
        var barDataDepartment = {
            labels: month,
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

        var barOptionsDepartment = {
            responsive: true
        };

        var ctx2 = document.getElementById("barChartDepartment").getContext("2d");
        new Chart(ctx2, {type: 'bar', data: barDataDepartment, options:barOptionsDepartment});

        var yearAndMonth = "{{ $date }}"
        
        $('#mdrStatusTable').DataTable({
            pageLength: 10,
            responsive: true,
            ordering: false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                // { extend: 'copy'},
                // {extend: 'csv'},
                // {extend: 'excel', title: 'ExampleFile'},
                {
                    extend: 'pdf',
                    title: 'Summary of Compliance for Departmental Reports for the month of Year and Month (Lowest to Highest)'+' - '+yearAndMonth,
                    pageSize: 'A4',
                    customize: function (doc) {
                        console.log(doc.styles);
                        doc.pageMargins = [20, 20, 20, 20];

                        var layout = {};
                        layout['hLineWidth'] = function(i) { return 2.0; };
                        layout['vLineWidth'] = function(i) { return 2.0; };
                        layout['hLineColor'] = function(i) { return '#aaa'; };
                        layout['vLineColor'] = function(i) { return '#aaa'; };
                        doc.content[1].layout = layout;

                        doc.styles.tableHeader.fontSize = 12;
                        doc.styles.tableBodyOdd.fontSize = 10;
                        doc.styles.tableBodyEven.fontSize = 10;
                        doc.styles.tableHeader.fillColor = '#FFFFFF'
                        doc.styles.tableHeader.color = '#000'
                    }
                },
                // {
                //     extend: 'print',
                //     title: 'Summary of Compliance for Departmental Reports for the month of Year and Month (Lowest to Highest)',
                //     messageTop: 'This is the MDR Status Report', // Add a custom message at the top
                //     customize: function (win){
                //         $(win.document.body).addClass('white-bg');
                //         $(win.document.body).css('font-size', '10px');

                //         $(win.document.body).find('table')
                //                 .addClass('compact')
                //                 .css('font-size', 'inherit');
                //     }
                // }
            ]
        });

    })
</script>
@endif
@if(Auth::user()->account_role == 2)
<script>
    var month = {!! json_encode(array_keys($data)) !!}

    var data = {!! json_encode(array_values($data)) !!}

    var barData = {
        labels: month,
        datasets: [
            {
                label: "Total Rating in year " + "{{ isset($years) ? $years : date('Y') }}",
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
@endpush
