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
                                <input type="month" name="year_month" class="form-control input-sm" value="{{ $year_month ? $year_month : date('Y-m', strtotime('-1 month')) }}">
                            </div>
                            <div class="col-lg-3">
                                &nbsp;
                                <div>
                                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>

                                    @if($year_month)
                                    <a href="{{ url('export/'.$year_month) }}" target="_blank" class="btn btn-sm btn-danger">
                                        <i class="fa fa-file-pdf-o"></i>
                                        Export as PDF
                                    </a>
                                    @endif
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
                                    <th rowspan="2">Company</th>
                                    <th rowspan="2">Nos.</th>
                                    <th rowspan="2">Department</th>
                                    <th rowspan="2">PIC</th>
                                    <th colspan="3" rowspan="1">History</th>
                                    <th colspan="2" rowspan="1">MDR Submission</th>
                                    <th rowspan="2">Timeliness</th>
                                    <th rowspan="2">Operational Objectives</th>
                                    <th rowspan="2">Innovation</th>
                                    <th rowspan="2">@if($year_month) {{ date('F', strtotime($year_month)) }} @endif Rating</th>
                                    {{-- <th rowspan="2">Rating</th> --}}
                                    <th rowspan="2">Remarks</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                @php
                                    $selectedDate = \Carbon\Carbon::parse($year_month);
                                    $month1 = $selectedDate->copy()->subMonths(3); 
                                    $month2 = $selectedDate->copy()->subMonths(2); 
                                    $month3 = $selectedDate->copy()->subMonths(1);
                                @endphp
                                <tr>
                                    <th>{{ $month1->format('F Y') }}</th>
                                    <th>{{ $month2->format('F Y') }}</th>
                                    <th>{{ $month3->format('F Y') }}</th>
                                    <th>Pre-approved Date</th>
                                    <th>Actual Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($whi_reports_array) > 0)
                                    @foreach ($whi_reports_array as $key=>$whi_reports_data)
                                        <tr @if($whi_reports_data->mdr) @if($whi_reports_data->mdr->score < 3.00) class="bg-warning" style="color: black;" @endif @endif>
                                            @if($key == 0)
                                                <td rowspan="{{ count($whi_reports_array) }}" style="vertical-align: middle; font-weight:bold;">
                                                    WHI
                                                </td>
                                            @endif
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $whi_reports_data->department }}</td>
                                            <td>{{ $whi_reports_data->head }}</td>
                                            @php
                                                $history = $whi_reports_data->mdr_history ?? collect();
                                                $m1 = $history->where('month', $month1->format('m'))
                                                            ->where('year', $month1->format('Y'))
                                                            ->first();
                                                $m2 = $history->where('month', $month2->format('m'))
                                                            ->where('year', $month2->format('Y'))
                                                            ->first();
                                                $m3 = $history->where('month', $month3->format('m'))
                                                            ->where('year', $month3->format('Y'))
                                                            ->first();
                                            @endphp

                                            <td>{{ $m1 ? number_format($m1->score,2) : '-' }}</td>
                                            <td>{{ $m2 ? number_format($m2->score,2) : '-' }}</td>
                                            <td>{{ $m3 ? number_format($m3->score,2) : '-' }}</td>
                                            <td>
                                                @if($whi_reports_data->mdr)
                                                {{ $whi_reports_data->mdr->departments->target_date.'-'.date('M', strtotime("+ 1 month", strtotime($whi_reports_data->mdr->month))).'-'.$whi_reports_data->mdr->year }}
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
                                            {{-- <td>
                                                0.00
                                            </td> --}}
                                            <td>
                                                @php
                                                    $year = date('Y', strtotime($year_month));
                                                    $month = date('m', strtotime($year_month));
                                                @endphp
                                                @foreach (($whi_reports_data->departments->remarks)->where('year', $year)->where('month', $month) as $remarks)
                                                    {!! nl2br(e($remarks->remarks)) !!}
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($year_month)
                                                    @if($whi_reports_data->mdr)
                                                        @if($whi_reports_data->mdr->grade < 3.00)
                                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#remarksModal{{ $whi_reports_data->departments->id }}">
                                                            <i class="fa fa-comment"></i>
                                                        </button>
                                                        @endif
                                                    @endif
                                                @endif
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
                                    <th rowspan="2">Company</th>
                                    <th rowspan="2">Nos.</th>
                                    <th rowspan="2">Department</th>
                                    <th rowspan="2">PIC</th>
                                    <th colspan="3" rowspan="1">History</th>
                                    <th colspan="2" rowspan="1">MDR Submission</th>
                                    <th rowspan="2">Timeliness</th>
                                    <th rowspan="2">Operational Objectives</th>
                                    <th rowspan="2">Innovation</th>
                                    <th rowspan="2">@if($year_month) {{ date('F', strtotime($year_month)) }} @endif Rating</th>
                                    {{-- <th rowspan="2">@if($year_month) {{ date('F', strtotime('-1 month', strtotime($year_month))) }} @endif Rating</th> --}}
                                    <th rowspan="2">Remarks</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th>{{ $month1->format('F Y') }}</th>
                                    <th>{{ $month2->format('F Y') }}</th>
                                    <th>{{ $month3->format('F Y') }}</th>
                                    <th>Pre-approved Date</th>
                                    <th>Actual Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($wli_reports_array) > 0)
                                    @foreach ($wli_reports_array as $key=>$wli_reports_data)
                                        {{-- @dd($wli_reports_data) --}}
                                        <tr @if($wli_reports_data->mdr) @if($wli_reports_data->mdr->score < 3.00) class="bg-warning" style="color: black;" @endif @endif>
                                            @if($key == 0)
                                                <td rowspan="{{ count($wli_reports_array) }}" style="vertical-align: middle; font-weight:bold;">
                                                    WLI
                                                </td>
                                            @endif
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $wli_reports_data->department }}</td>
                                            <td>{{ $wli_reports_data->head }}</td>
                                             @php
                                                $history = $wli_reports_data->mdr_history ?? collect();
                                                $m1 = $history->where('month', $month1->format('m'))
                                                            ->where('year', $month1->format('Y'))
                                                            ->first();
                                                $m2 = $history->where('month', $month2->format('m'))
                                                            ->where('year', $month2->format('Y'))
                                                            ->first();
                                                $m3 = $history->where('month', $month3->format('m'))
                                                            ->where('year', $month3->format('Y'))
                                                            ->first();
                                            @endphp

                                            <td>{{ $m1 ? number_format($m1->score,2) : '-' }}</td>
                                            <td>{{ $m2 ? number_format($m2->score,2) : '-' }}</td>
                                            <td>{{ $m3 ? number_format($m3->score,2) : '-' }}</td>
                                            <td>
                                                @if($wli_reports_data->mdr)
                                                {{ $wli_reports_data->mdr->departments->target_date.'-'.date('M', strtotime("+1 month", strtotime($wli_reports_data->mdr->year.'-'.$wli_reports_data->mdr->month))).'-'.$wli_reports_data->mdr->year }}
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
                                            {{-- <td>
                                                0.00
                                            </td> --}}
                                            <td>
                                                @php
                                                    $year = date('Y', strtotime($year_month));
                                                    $month = date('m', strtotime($year_month));
                                                @endphp
                                                @foreach (($wli_reports_data->departments->remarks)->where('year', $year)->where('month', $month) as $remarks)
                                                    <p style="text-align: justify; text-justify:inter-word;">{!! nl2br(e($remarks->remarks)) !!}</p>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($year_month)
                                                    @if($wli_reports_data->mdr)
                                                        @if($wli_reports_data->mdr->grade < 3.00)
                                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#remarksModal{{ $wli_reports_data->departments->id }}">
                                                            <i class="fa fa-comment"></i>
                                                        </button>
                                                        @endif
                                                    @endif
                                                @endif
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
                    <h5>Other Reports</h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2">Company</th>
                                    <th rowspan="2">Nos.</th>
                                    <th rowspan="2">Department</th>
                                    <th rowspan="2">PIC</th>
                                    <th colspan="3" rowspan="1">History</th>
                                    <th colspan="2" rowspan="1">MDR Submission</th>
                                    <th rowspan="2">Timeliness</th>
                                    <th rowspan="2">Operational Objectives</th>
                                    <th rowspan="2">Innovation</th>
                                    <th rowspan="2">@if($year_month) {{ date('F', strtotime($year_month)) }} @endif Rating</th>
                                    {{-- <th rowspan="2">@if($year_month) {{ date('F', strtotime('-1 month', strtotime($year_month))) }} @endif Rating</th> --}}
                                    <th rowspan="2">Remarks</th>
                                    <th rowspan="2">Action</th>
                                </tr>
                                <tr>
                                    <th>{{ $month1->format('F Y') }}</th>
                                    <th>{{ $month2->format('F Y') }}</th>
                                    <th>{{ $month3->format('F Y') }}</th>
                                    <th>Pre-approved Date</th>
                                    <th>Actual Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($other_reports_array) > 0)
                                    @foreach ($other_reports_array as $key=>$other_reports_data)
                                        {{-- @dd($wli_reports_data) --}}
                                        <tr @if($other_reports_data->mdr) @if($other_reports_data->mdr->score < 3.00) class="bg-warning" style="color: black;" @endif @endif>
                                            @if($key == 0)
                                                <td rowspan="{{ count($other_reports_array) }}" style="vertical-align: middle; font-weight:bold;">
                                                    OTHERS
                                                </td>
                                            @endif
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $other_reports_data->department }}</td>
                                            <td>{{ $other_reports_data->head }}</td>
                                             @php
                                                $history = $other_reports_data->mdr_history ?? collect();
                                                $m1 = $history->where('month', $month1->format('m'))
                                                            ->where('year', $month1->format('Y'))
                                                            ->first();
                                                $m2 = $history->where('month', $month2->format('m'))
                                                            ->where('year', $month2->format('Y'))
                                                            ->first();
                                                $m3 = $history->where('month', $month3->format('m'))
                                                            ->where('year', $month3->format('Y'))
                                                            ->first();
                                            @endphp

                                            <td>{{ $m1 ? number_format($m1->score,2) : '-' }}</td>
                                            <td>{{ $m2 ? number_format($m2->score,2) : '-' }}</td>
                                            <td>{{ $m3 ? number_format($m3->score,2) : '-' }}</td>
                                            <td>
                                                @if($other_reports_data->mdr)
                                                {{ $other_reports_data->mdr->departments->target_date.'-'.date('M', strtotime("+1 month", strtotime($other_reports_data->mdr->year.'-'.$other_reports_data->mdr->month))).'-'.$other_reports_data->mdr->year }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if($other_reports_data->mdr)
                                                {{ date('d-M-Y', strtotime($other_reports_data->mdr->created_at)) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if($other_reports_data->mdr)
                                                {{ number_format($other_reports_data->mdr->timeliness,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($other_reports_data->mdr)
                                                {{ number_format($other_reports_data->mdr->grade,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($other_reports_data->mdr)
                                                {{ number_format($other_reports_data->mdr->innovation_scores,2) }}
                                                @else
                                                0.00
                                                @endif
                                            </td>
                                            <td>
                                                @if($other_reports_data->mdr)
                                                {{ number_format($other_reports_data->mdr->score,2) }}
                                                @else
                                                <b><i>No MDR Submitted</i></b>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                0.00
                                            </td> --}}
                                            <td>
                                                @php
                                                    $year = date('Y', strtotime($year_month));
                                                    $month = date('m', strtotime($year_month));
                                                @endphp
                                                @foreach (($other_reports_data->departments->remarks)->where('year', $year)->where('month', $month) as $remarks)
                                                    <p style="text-align: justify; text-justify:inter-word;">{!! nl2br(e($remarks->remarks)) !!}</p>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($year_month)
                                                    @if($other_reports_data->mdr)
                                                        @if($other_reports_data->mdr->grade < 3.00)
                                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#remarksModal{{ $other_reports_data->departments->id }}">
                                                            <i class="fa fa-comment"></i>
                                                        </button>
                                                        @endif
                                                    @endif
                                                @endif
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

@foreach ($whi_reports_array as $data)
@include('approver.whi_mdr_reports_remarks')
@endforeach

@foreach ($wli_reports_array as $data)
@include('approver.wli_mdr_reports_remarks')
@endforeach
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
