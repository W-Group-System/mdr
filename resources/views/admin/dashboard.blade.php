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
                                        <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}">
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
                    <div class="ibox float-e-margins" style="margin-top: 10px;">
                        <div class="ibox-content">
                            <table class="table table-striped table-bordered table-hover" id="mdrSummaryTable">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>PIC</th>
                                        <th>Deadline</th>
                                        <th>Submission Date</th>
                                        <th>Status</th>
                                        <th>Approved Date</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mdrSummary as $data)
                                        <tr>
                                            <td>{{ $data->departments->dept_name }}</td>
                                            <td>{{ $data->users->name }}</td>
                                            <td>{{ $data->deadline }}</td>
                                            <td>{{ $data->submission_date }}</td>
                                            <td>{{ $data->status }}</td>
                                            <td>{{ $data->approved_date }}</td>
                                            <td>{{ $data->rate }}</td>
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
        <div class="ibox float-e-margins" style="margin-top: 10px;">
            <div class="ibox-content">
                <canvas id="lineChart" height="70"></canvas>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>  
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>
{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>
<script>
    $('#mdrSummaryTable').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            // { extend: 'copy'},
            // {extend: 'csv'},
            // {extend: 'excel', title: 'ExampleFile'},
            {extend: 'pdf', title: 'MDR Summary'},

            {extend: 'print',
                customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
            }
            }
        ]
    });

    $("[name='department']").chosen({width: "100%"});

</script>
@if(Auth::user()->account_role == 2)
<script>
    var lineData = {
            labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            datasets: [
                {
                    label: "Example dataset",
                    backgroundColor: "rgba(26,179,148,0.5)",
                    borderColor: "rgba(26,179,148,0.7)",
                    pointBackgroundColor: "rgba(26,179,148,1)",
                    pointBorderColor: "#fff",
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: "Example dataset",
                    backgroundColor: "rgba(220,220,220,0.5)",
                    borderColor: "rgba(220,220,220,1)",
                    pointBackgroundColor: "rgba(220,220,220,1)",
                    pointBorderColor: "#fff",
                    data: [65, 59, 80, 81, 56, 55, 40]
                }
            ]
        };

        var lineOptions = {
            responsive: true
        };

        var ctx = document.getElementById("lineChart").getContext("2d");
        new Chart(ctx, {type: 'line', data: lineData, options:lineOptions});
</script>
@endif
@endpush
