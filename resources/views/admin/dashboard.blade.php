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
                            <h1 class="no-margins">{{ count($total_users) }}</h1>
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
                            <h1 class="no-margins">{{ count($total_dept) }}</h1>
                            <small>Total Departments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(auth()->user()->role == "Approver" || auth()->user()->role == "Business Process Manager")
        {{-- <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <form action="" method="get" onsubmit="show()">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="">Year and Month :</label>
                                        <div class="form-group">
                                            <input type="month" name="yearAndMonth" class="form-control input-sm" value="{{$yearAndMonth}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
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
                            <form action="{{ url('print_pdf') }}" method="post" target="_blank" >
                                @csrf

                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                                <button type="submit" class="btn btn-sm btn-warning pull-right">
                                    <i class="fa fa-print"></i>
                                    &nbsp;
                                    <span class="bold">Print PDF</span>
                                </button>
                            </form>
                            <table class="table table-bordered" id="mdrStatusTable">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>KPI</th>
                                        @if($yearAndMonth < '2024-07')
                                        <th>Innovation</th>
                                        @endif
                                        <th>Process Improvement</th>
                                        <th>Timeliness</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mdr_score_array as $summary)
                                        <tr>
                                            <td>{{$summary->name}}</td>
                                            <td>
                                                @if($summary->status == null)
                                                    <span class="label label-danger">No MDR Submitted</span>
                                                @else
                                                    @if($summary->status == "Pending")
                                                    <span class="label label-warning">
                                                    @elseif($summary->status == "Approved")
                                                    <span class="label label-primary">
                                                    @endif
                                                    
                                                    {{$summary->status}}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($summary->deadline != null)
                                                {{date('M d, Y', strtotime($summary->deadline))}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($summary->scores != null)
                                                {{$summary->scores}}
                                                @else
                                                0.0
                                                @endif
                                            </td>
                                            @if($yearAndMonth < '2024-07')
                                            <td>
                                                @if($summary->innovation_scores != null)
                                                {{$summary->innovation_scores}}
                                                @else
                                                0.0
                                                @endif
                                            </td>
                                            @endif
                                            <td>
                                                @if($summary->pd_scores != null)
                                                {{$summary->pd_scores}}
                                                @else
                                                0.0
                                                @endif
                                            </td>
                                            <td>
                                                @if($summary->timeliness != null)
                                                {{$summary->timeliness}}
                                                @else
                                                0.0
                                                @endif
                                            </td>
                                            <td>
                                                @if($summary->total_rating != null)
                                                {{$summary->total_rating}}
                                                @else
                                                0.0
                                                @endif
                                            </td>
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
                            <form action="" method="get" onsubmit="show()">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="department">Department</label>
                                            <select name="departmentValue" id="department" class="form-control cat">
                                                <option value="">-Department-</option>
                                                @foreach ($departments as $departmentData)
                                                    <option value="{{ $departmentData->id }}" {{ $departmentData->id == $departmentValue ? 'selected' : '' }}>{{ $departmentData->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="year1">Years</label>
                                            <select name="years1" id="year1" class="form-control cat">
                                                <option value="">-Years-</option>
                                                @foreach (collect($years)->sortKeysDesc() as $key=>$y)
                                                    <option value="{{$y}}" @if($y == $year1) selected @endif>{{$y}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label for="years2">Years</label>
                                            <select name="years2" id="year2" class="form-control cat">
                                                <option value="">-Years-</option>
                                                @foreach (collect($years)->sortKeysDesc() as $key=>$y)
                                                    <option value="{{$y}}" @if($y == $year2) selected @endif>{{$y}}</option>
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
                            <table class="table table-striped table-bordered table-hover tables" id="">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($months as $month)
                                        <tr>
                                            <td>{{ $month->y }}</td>
                                            <td>
                                                @if(count($month->mdr_status) > 0)
                                                    <span class="label label-success">Submitted</span>
                                                @else
                                                    <span class="label label-danger">No Submitted</span>
                                                @endif
                                            </td>
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
                            <table class="table table-striped table-bordered table-hover tables" id="">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($months_array as $month)
                                        <tr>
                                            <td>{{ $month->y }}</td>
                                            <td>
                                                @if(count($month->mdr_status) > 0)
                                                    <span class="label label-success">Submitted</span>
                                                @else
                                                    <span class="label label-danger">No Submitted</span>
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
        </div> --}}
    @endif

    @if(auth()->user()->role == "Department Head" || auth()->user()->role == "Users")
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <form action="" method="get" onsubmit="show()">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <select name="year" id="year" class="form-control cat">
                                            <option value="">-Years-</option>
                                            {{-- @foreach ($years as $key=>$year)
                                                <option value="{{$key}}" {{$key==$yearData?'selected':''}}>{{$year}}</option>
                                            @endforeach --}}
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
                        <div class="ibox-title">
                            <h5>MDR Status Graph</h5>
                        </div>
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
                            <h5>Status in Year {{date('Y')}} </h5>
                        </div>
                        <div class="ibox-content">
                            <table class="table table-bordered" id="statusPerYear">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($months as $month)
                                        <tr>
                                            <td>{{ $month->y }}</td>
                                            <td>
                                                @if(count($month->mdr_status) > 0)
                                                    <span class="label label-success">Submitted</span>
                                                @else
                                                    <span class="label label-danger">No Submitted</span>
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
    @endif
{{-- {{dd(collect($summary_kpi)->pluck('mdr_status'))}} --}}
@include('components.footer')

@endsection

@push('scripts')
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>
<!-- ChartJS-->
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>
{{-- @if(auth()->user()->role == "Approver" || auth()->user()->role == "Business Process Manager")
<script>
    $(document).ready(function() {
        $(".cat").chosen({width: "100%"});

        $('#mdrStatusTable').DataTable({
            pageLength: 10,
            responsive: true,
            ordering: false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                // {extend: 'csv'},
                {extend: 'excel'},
            ]
        });

        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            ordering: false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                // {extend: 'csv'},
                {extend: 'excel'},
            ]
        });

        var department = {!! json_encode(collect($summary_kpi)->pluck('d')->toArray()) !!}
        var data = {!! json_encode(collect($summary_kpi)->pluck('mdr_status')->map(function($status) { return count($status) ? $status[0] : 0; })->toArray()) !!};

        var barData = {
            labels: department,
            datasets: [
                {
                    // label: "Total Rating in " + "{{ isset($yearAndMonth) ? date('F Y', strtotime($yearAndMonth)) : date('F Y') }}",
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

        var month1 = {!! json_encode(collect($months)->pluck('y')->toArray()) !!};
        var data1 = {!! json_encode(collect($months)->pluck('mdr_status')->map(function($status) { return count($status) ? $status[0] : 0; })->toArray()) !!};

        console.log(data1);
        
        var barDataDepartment = {
            labels: month1,
            datasets: [
                {
                    label: "MDR data",
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
        
        var month2 = {!! json_encode(collect($months_array)->pluck('y')->toArray()) !!};
        var data2 = {!! json_encode(collect($months_array)->pluck('mdr_status')->map(function($status) { return count($status) ? $status[0] : 0; })->toArray()) !!};

        var year2BarData = {
            labels: month2,
            datasets: [
                {
                    label: "MDR data",
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
        @php         

        // var yearAndMonth = "{{ $date }};"
        @endphp
    }) 
</script>
@endif --}}

@if(auth()->user()->role == "Department Head" || auth()->user()->role == "Users")
<script>
    $(".cat").chosen({width: "100%"});
    
    $('#statusPerYear').DataTable({
        pageLength: 12,
        responsive: true,
        ordering: false,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
        ]
    });
    
    var month = {!! json_encode(collect($months)->pluck('y')->toArray()) !!}
    var data = {!! json_encode(collect($months)->pluck('mdr_status')->map(function($status) { return count($status) ? $status[0] : 0; })->toArray()) !!};
    
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

@endpush
