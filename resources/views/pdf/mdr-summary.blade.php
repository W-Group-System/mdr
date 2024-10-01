<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>MDR Summary</title>
</head>
<style>
    html, body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 9;
    }
    .page-break {
        page-break-after: always;
    }

    p {
        text-align: justify;
        text-justify: inter-word;
        margin: 0;
        padding: 0;
    }

    table {
        page-break-inside: auto;
        border-spacing: 0;
        border-collapse: collapse;
        margin-top: 10px;
    }

</style>
<body>
    <img src="{{asset('img/wgroup.png')}}" alt="" height="80" width="100" style="float: left;">
    <h2 class="pdf-title">Monthly Department Report Summary for the month of {{ date('F Y', strtotime($year_and_month)) }}</h2>

    <table border="1" cellpadding="0" cellspacing="0" style="width: 100%; font-size:9; margin-top: 40; word-wrap:break-word; table-layout:fixed; text-align:center;">
        <thead>
            <tr>
                <th>Department</th>
                {{-- <th>Action</th> --}}
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
            @if(count($mdr_summary) > 0)
                @foreach ($mdr_summary as $data)
                    <tr>
                        <td class="tdData" width="30%">{{ $data->name }}</td>
                        {{-- <td class="tdData">{{ $data['action'] }}</td> --}}
                        <td class="tdData">{{ $data->status }}</td>
                        <td class="tdData">{{ $data->deadline }}</td>
                        <td class="tdData">{{ $data->scores}}</td>
                        <td class="tdData">{{ $data->innovation_scores }}</td>
                        <td class="tdData">{{ $data->pd_scores }}</td>
                        <td class="tdData">{{ $data->timeliness }}</td>
                        <td class="tdData">{{ $data->total_rating }}</td>
                    </tr>
                @endforeach
            @else
            <tr>
                <td class="tdData" colspan="9">No data available.</td>
            </tr>
            @endif
        </tbody>
    </table>
    {{-- <div class="page-break"></div>
    <h1>Page 2</h1> --}}
</body>
</html>