<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>MDR Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
</head>

<style>
    html,
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8.8;
    }

    .page-break {
        page-break-after: always;
    }

    .title {
        font-size: 10;
        font-weight: bold;
    }

    table {
        page-break-inside: auto;
        width: 100%;
    }

    thead {
        display: table-row-group;
    }

    tr {
        page-break-inside: auto;
    }

    .text-justify {
        text-align: justify;
        text-justify: inter-word;
        margin: 0;
        overflow-wrap: break-word;
        word-wrap: break-word;
        word-break: break-word; 
        white-space: normal;
        width: 150px;     
    }
</style>

<body>
    <main>
        <p class="title">
            Summary of Compliance for Departmental Reports for the month of {{ date('M Y',
            strtotime($data['year_and_month'])) }} (lowest to highest)
        </p>

        <table border="1" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th rowspan="2">Company</th>
                    <th rowspan="2">Nos.</th>
                    <th rowspan="2">Department</th>
                    <th rowspan="2">PIC</th>
                    <th colspan="3" rowspan="1">History</th>
                    <th rowspan="1" colspan="2">MDR Submission</th>
                    <th rowspan="2">Timeliness</th>
                    <th rowspan="2">Operational Objectives</th>
                    <th rowspan="2">Innovation</th>
                    <th rowspan="2" class="bg-success">@if($data['year_and_month']) {{ date('F',
                        strtotime($data['year_and_month'])) }} @endif Rating</th>
                    {{-- <th rowspan="2" class="bg-success">@if($data['year_and_month']) {{ date('F', strtotime('-1 month',
                        strtotime($data['year_and_month']))) }} @endif Rating</th> --}}
                    <th rowspan="2">Reason for Low Grade</th>
                    <th rowspan="2">Action Plan</th>
                </tr>
                    @php
                        $selectedDate = \Carbon\Carbon::parse($data['year_and_month']);
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
                {{-- @dd($data['whi']) --}}
                @foreach ($data['whi'] as $key=>$whi)
                <tr @if($whi->mdr) @if($whi->mdr->score < 3.00) class="bg-warning" style="color: black;" @endif @endif>
                        <td>WHI</td>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $whi->department }}</td>
                        <td class="text-center">
                            @if($whi->departments->user)
                            {{ $whi->departments->user->name }}
                            @endif
                        </td>
                        @php
                            $history = $whi->mdr_history ?? collect();
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
                        <td class="text-center">
                            @if($whi->mdr)
                            {{ $whi->mdr->departments->target_date.'-'.date('M', strtotime("+ 1 month",
                            strtotime($whi->mdr->month))).'-'.$whi->mdr->year }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($whi->mdr)
                            {{ date('d-M-Y', strtotime($whi->mdr->created_at)) }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($whi->mdr)
                            {{ number_format($whi->mdr->timeliness,2) }}
                            @else
                            0.00
                            @endif
                        </td>
                        <td class="text-center">
                            @if($whi->mdr)
                            {{ number_format($whi->mdr->grade,2) }}
                            @else
                            0.00
                            @endif
                        </td>
                        <td class="text-center">
                            @if($whi->mdr)
                            {{ number_format($whi->mdr->innovation_scores,2) }}
                            @else
                            0.00
                            @endif
                        </td>
                        <td class="text-center">
                            @if($whi->mdr)
                            {{ number_format($whi->mdr->score,2) }}
                            @else
                            <b><i>No MDR Submitted</i></b>
                            @endif
                        </td>
                        {{-- <td class="text-center">
                            0.00
                        </td> --}}
                        <td class="text-center">
                            @php
                            $year = date('Y', strtotime($data['year_and_month']));
                            $month = date('m', strtotime($data['year_and_month']));
                            @endphp
                            @foreach (($whi->departments->remarks)->where('year', $year)->where('month',
                            $month) as $remarks)
                            {!! nl2br(e($remarks->remarks)) !!}
                            @endforeach
                        </td>
                        <td class="text-center"></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table border="1" cellpadding="0" cellspacing="0" style="margin-top: 10px;">
            <thead>
                <tr>
                    <th rowspan="2">Company</th>
                    <th rowspan="2">Nos.</th>
                    <th rowspan="2">Department</th>
                    <th rowspan="2">PIC</th>
                    <th colspan="3" rowspan="1">History</th>
                    <th rowspan="1" colspan="2">MDR Submission</th>
                    <th rowspan="2">Timeliness</th>
                    <th rowspan="2">Operational Objectives</th>
                    <th rowspan="2">Innovation</th>
                    <th rowspan="2" class="bg-success">@if($data['year_and_month']) {{ date('F',
                        strtotime($data['year_and_month'])) }} @endif Rating</th>
                    {{-- <th rowspan="2" class="bg-success">@if($data['year_and_month']) {{ date('F', strtotime('-1 month',
                        strtotime($data['year_and_month']))) }} @endif Rating</th> --}}
                    <th rowspan="2">Reason for Low Grade</th>
                    <th rowspan="2">Action Plan</th>
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
                {{-- @dd($data['whi']) --}}
                @foreach ($data['wli'] as $key=>$wli)
                <tr @if($wli->mdr) @if($wli->mdr->score < 3.00) class="bg-warning" style="color: black;" @endif @endif>
                        <td>
                            WLI
                        </td>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $wli->department }}</td>
                        <td class="text-center">
                            @if($wli->departments->user)
                            {{ $wli->departments->user->name }}
                            @endif
                        </td>
                        @php
                            $history = $wli->mdr_history ?? collect();
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
                        <td class="text-center">
                            @if($wli->mdr)
                            {{ $wli->mdr->departments->target_date.'-'.date('M', strtotime("+ 1 month",
                            strtotime($wli->mdr->month))).'-'.$wli->mdr->year }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($wli->mdr)
                            {{ date('d-M-Y', strtotime($wli->mdr->created_at)) }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($wli->mdr)
                            {{ number_format($wli->mdr->timeliness,2) }}
                            @else
                            0.00
                            @endif
                        </td>
                        <td class="text-center">
                            @if($wli->mdr)
                            {{ number_format($wli->mdr->grade,2) }}
                            @else
                            0.00
                            @endif
                        </td>
                        <td class="text-center">
                            @if($wli->mdr)
                            {{ number_format($wli->mdr->innovation_scores,2) }}
                            @else
                            0.00
                            @endif
                        </td>
                        <td class="text-center">
                            @if($wli->mdr)
                            {{ number_format($wli->mdr->score,2) }}
                            @else
                            <b><i>No MDR Submitted</i></b>
                            @endif
                        </td>
                        {{-- <td class="text-center">
                            0.00
                        </td> --}}
                        <td>
                            @php
                            $year = date('Y', strtotime($data['year_and_month']));
                            $month = date('m', strtotime($data['year_and_month']));
                            @endphp
                            @foreach (($wli->departments->remarks)->where('year', $year)->where('month',
                            $month) as $remarks)
                            <p class="text-justify">{!! nl2br(e($remarks->remarks)) !!}</p>
                            @endforeach
                        </td>
                        <td class="text-center"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"
        integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous">
    </script>
</body>

</html>