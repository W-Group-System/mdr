@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        {{-- <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="" method="get" enctype="multipart/form-data" onsubmit="show()">
                    </form>
                </div>
            </div>
        </div> --}}

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>WHI Reports</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2">Nos.</th>
                                    <th rowspan="2">Department</th>
                                    <th rowspan="2">PIC</th>
                                    <th colspan="2" rowspan="1">MDR Submission</th>
                                    <th rowspan="2">Timeliness</th>
                                    <th rowspan="2">Operational Objectives</th>
                                    <th rowspan="2">Innovation</th>
                                    <th>Rating</th>
                                    <th>Rating</th>
                                </tr>
                                <tr>
                                    <th>Pre-approved Date</th>
                                    <th>Actual Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10" class="text-center">No data available.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>WLI Reports</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2">Nos.</th>
                                    <th rowspan="2">Department</th>
                                    <th rowspan="2">PIC</th>
                                    <th colspan="2" rowspan="1">MDR Submission</th>
                                    <th rowspan="2">Timeliness</th>
                                    <th rowspan="2">Operational Objectives</th>
                                    <th rowspan="2">Innovation</th>
                                    <th>Rating</th>
                                    <th>Rating</th>
                                </tr>
                                <tr>
                                    <th>Pre-approved Date</th>
                                    <th>Actual Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="10" class="text-center">No data available.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>
{{-- datatable --}}
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script>
    $(document).ready(function() {
        // $('#processDevelopmentTable').DataTable({
        //     pageLength: 10,
        //     ordering: false,
        //     responsive: true,
        //     stateSave: true,
        //     dom: '<"html5buttons"B>lTfgitp',
        //     buttons: []
        // });

        // $('#departmentalGoals').DataTable({
        //     pageLength: 10,
        //     ordering: false,
        //     responsive: true,
        //     stateSave: true,
        //     dom: '<"html5buttons"B>lTfgitp',
        //     buttons: []
        // });

        // $('#kpiScores').DataTable({
        //     pageLength: 10,
        //     ordering: false,
        //     responsive: true,
        //     stateSave: true,
        //     dom: '<"html5buttons"B>lTfgitp',
        //     buttons: []
        // });

        // $('#innovationTable').DataTable({
        //     pageLength: 10,
        //     ordering: false,
        //     responsive: true,
        //     stateSave: true,
        //     dom: '<"html5buttons"B>lTfgitp',
        //     buttons: []
        // });
        
        $("[name='department']").chosen({width: "100%"});
    })
</script>
@endpush
