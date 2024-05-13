@extends('layouts.app')

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
                {{-- <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-primary pull-right">Today</span>
                            <h5>visits</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">106,120</h1>
                            <div class="stat-percent font-bold text-navy">44% <i class="fa fa-level-up"></i></div>
                            <small>New visits</small>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <span class="label label-danger pull-right">Low value</span>
                            <h5>User activity</h5>
                        </div>
                        <div class="ibox-content">
                            <h1 class="no-margins">80,600</h1>
                            <div class="stat-percent font-bold text-danger">38% <i class="fa fa-level-down"></i></div>
                            <small>In first month</small>
                        </div>
                    </div>
                </div> --}}
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
{{-- Chart JS --}}
<script src="{{ asset('js/plugins/chartJs/Chart.min.js') }}"></script>
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
@endpush