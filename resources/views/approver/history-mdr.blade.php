@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="" method="get" enctype="multipart/form-data" onsubmit="show()">
                        <div class="row">
                            <div class="col-lg-3">
                                Year & Month :
                                <input type="month" name="year_month" class="form-control input-sm" value="{{ $year_month }}">
                            </div>
                            <div class="col-lg-3">
                                &nbsp;
                                <div>
                                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>WHI Reports</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2">Nos.</th>
                                    <th rowspan="2">Department</th>
                                    <th rowspan="2">PIC</th>
                                    <th colspan="2" rowspan="1">MDR Submission</th>
                                    <th rowspan="2">Timeliness</th>
                                    <th rowspan="2">Operational Objectives</th>
                                    <th rowspan="2">Innovation</th>
                                    <th rowspan="2">Rating</th>
                                    <th rowspan="2">Rating</th>
                                </tr>
                                <tr>
                                    <th>Pre-approved Date</th>
                                    <th>Actual Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($whi_reports_array) > 0)
                                    @foreach ($whi_reports_array as $key=>$whi_reports_data)
                                        <tr @if($whi_reports_data->mdr) @if($whi_reports_data->mdr->score < 3.00) class="bg-warning" style="color: black;" @endif @else class="bg-warning" style="color: black;" @endif>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $whi_reports_data->department }}</td>
                                            <td>{{ $whi_reports_data->head }}</td>
                                            <td>
                                                @if($whi_reports_data->mdr)
                                                {{ date('d-M-Y', strtotime($whi_reports_data->mdr->pre_approved_date)) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if($whi_reports_data->mdr)
                                                {{ date('d-M-Y', strtotime($whi_reports_data->mdr->created_at)) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if($whi_reports_data->mdr)
                                                {{ number_format($whi_reports_data->mdr->timeliness,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($whi_reports_data->mdr)
                                                {{ number_format($whi_reports_data->mdr->grade,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($whi_reports_data->mdr)
                                                {{ number_format($whi_reports_data->mdr->innovation_scores,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($whi_reports_data->mdr)
                                                {{ number_format($whi_reports_data->mdr->score,2) }}
                                                @else
                                                <b><i>No MDR Submitted</i></b>
                                                @endif
                                            </td>
                                            <td>
                                                0.00
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="10" class="text-center">No data available.</td>
                                </tr>
                                @endif
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2">Nos.</th>
                                    <th rowspan="2">Department</th>
                                    <th rowspan="2">PIC</th>
                                    <th colspan="2" rowspan="1">MDR Submission</th>
                                    <th rowspan="2">Timeliness</th>
                                    <th rowspan="2">Operational Objectives</th>
                                    <th rowspan="2">Innovation</th>
                                    <th rowspan="2">@if($year_month) {{ date('F', strtotime($year_month)) }} @endif Rating</th>
                                    <th rowspan="2">@if($year_month) {{ date('F', strtotime('-1 month', strtotime($year_month))) }} @endif Rating</th>
                                </tr>
                                <tr>
                                    <th>Pre-approved Date</th>
                                    <th>Actual Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($wli_reports_array) > 0)
                                    @foreach ($wli_reports_array as $key=>$wli_reports_data)
                                        {{-- @dd($wli_reports_data) --}}
                                        <tr @if($wli_reports_data->mdr) @if($wli_reports_data->mdr->score < 3.00) class="bg-warning" style="color: black;" @endif @else class="bg-warning" style="color: black;" @endif>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $wli_reports_data->department }}</td>
                                            <td>{{ $wli_reports_data->head }}</td>
                                            <td>
                                                @if($wli_reports_data->mdr)
                                                {{ date('d-M-Y', strtotime($wli_reports_data->mdr->pre_approved_date)) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if($wli_reports_data->mdr)
                                                {{ date('d-M-Y', strtotime($wli_reports_data->mdr->created_at)) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if($wli_reports_data->mdr)
                                                {{ number_format($wli_reports_data->mdr->timeliness,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($wli_reports_data->mdr)
                                                {{ number_format($wli_reports_data->mdr->grade,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($wli_reports_data->mdr)
                                                {{ number_format($wli_reports_data->mdr->innovation_scores,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($wli_reports_data->mdr)
                                                {{ number_format($wli_reports_data->mdr->score,2) }}
                                                @else
                                                <b><i>No MDR Submitted</i></b>
                                                @endif
                                            </td>
                                            <td>
                                                0.00
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="10" class="text-center">No data available.</td>
                                </tr>
                                @endif
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
