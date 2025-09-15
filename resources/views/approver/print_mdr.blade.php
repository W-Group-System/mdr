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
    @page {
        margin-top: 175px; /* equal or slightly bigger than header height */
        margin-bottom: 80px; /* equal or slightly bigger than footer height */
        margin-left: 20px;
        margin-right: 20px;
    }
    .page-break {
        page-break-after: always;
    }
    header {
        position: fixed;
        top: -165px; /* negative value = header height */
        left: 0;
        right: 0;
        height: 165px; /* must match header height */
        background-color: #ffffff;
        color: black;
        text-align: left;
    }

    .footer {
        position: fixed;
        top: 750px;
        left: 500px;
        right: 0px;
        height: 50px;
    }
    hr.soft {
        margin-top: 0em;
        margin-bottom: 0em;
        border: none;
        height: .5px;
    }
    input[type=checkbox] {
        display: inline;
    }
    main {
        margin-top: 170px;
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
    p {
        text-align: justify;
        text-justify: inter-word;
        margin: 0;
        padding: 0;
    }
    footer {
        position: fixed;
        bottom: -60px;
        left: 0px;
        right: 0px;
        height: 60px;
    }
    .page-number:after {
        content: counter(page);
    }
    tr.no-bottom-border td {
        border-bottom: none;
        border-top: none;
    }
    main {
        margin: 0;
    }
</style>

<body>
    <footer>
        <table style='width:100%;' border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class='text-left"'>
                    <p class="m-0" style="font-weight: normal; font-size:8;">WGI-FR-BPD-020</p>
                    <p class="m-0" style="font-weight: normal; font-size:8;">Rev. 3 05/05/2025</p>
                </td>
                <td class='text-center'>
                    <i ></i>
                </td>
                <td class='text-right'>
                    <span class="page-number">Page <script type="text/php">{PAGE_NUM} of {PAGE_COUNT}</script></span>
                </td>
            </tr>
        </table>
    </footer>
    <header>
        <table style='width:100%;' border="1" cellspacing="0" cellpadding="0">
            <tr>
                <td width='100px' style='width:20; text-align:center;'>
                    <img src="{{asset('img/wgroup.png')}}" alt="" height="100" style="margin: auto;">
                </td>
                <td colspan="3">
                    <span class='m-0 p-0' style='font-size:8;margin-top;0px;padding-top:0px;'>
                        <p class="text-center my-1" style="font-weight: bold;">Subsidiaries and Affiliates </p>
                    </span>
                    <hr class="m-0 bg-dark">
                    <table style='font-size:9;margin-top;0px;padding-top:0px;width: 100%;' border="0"
                        cellspacing="0" cellpadding="0">
                        <tr>
                            <td class='text-left' style='width:10%;'></td>
                            <td class='text-left'><input type='checkbox'> WGI</td>
                            <td class='text-left'><input type='checkbox'> WHI Carmona</td>
                            <td class='text-left'><input type='checkbox'> FMPI/FMTCC</td>
                        </tr>
                        <tr>
                            <td class='text-left' style='width:10%;'></td>
                            <td class='text-left'> <input type='checkbox'> WHI - HO</td>
                            <td class='text-left'><input type='checkbox'> CCC</td>
                            <td class='text-left'><input type='checkbox'> PBI</td>
                        </tr>
                        <tr>
                            <td class='text-left' style='width:10%;'></td>
                            <td class='text-left'> <input type='checkbox'> WLI</td>
                            <td class='text-left'><input type='checkbox'> MRDC </td>
                            <td class='text-left'><input type='checkbox'> Others: ________</td>
                        </tr>
                        <tr>
                            <td class='text-left' style='width:10%;'></td>
                            <td class='text-left'> <input type='checkbox'> PRI</td>
                            <td class='text-left'><input type='checkbox'> SPAI </td>
                            {{-- <td class='text-left'><input type='checkbox'> </td> --}}
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4" class='text-left'>
                    <p style="font-size: 9;" class="ml-1 mb-0 font-weight-bold">Form Title :</p>
                    <p style="font-weight: bold; font-size:10;" class="text-center mb-1">MONTHLY DEPARTMENT REPORT</p>
                </td>
            </tr>
        </table>
    </header>

    <main>
        <table cellpadding='1' cellspacing='0' border="1" style="width: 100%;">
            <tr>
                <td>
                    <p class="font-weight-bold ml-1" style="font-size:11">Company: <span class="font-weight-normal">{{ $data['mdr']->departments->company->name }}</span></p>
                </td>
                <td>
                    <p class="font-weight-bold ml-1 text-center" style="font-size: 11;">Criteria</p>
                </td>
                <td>
                    <p class="font-weight-bold ml-1 text-center" style="font-size: 11;">Target Weight</p>
                </td>
                <td>
                    <p class="font-weight-bold ml-1 text-center" style="font-size: 11;">Final Score</p>
                </td>
            </tr>
            <tr>
                <td rowspan="3">
                    <p class="font-weight-bold ml-1" style="font-size:11">Department: <span class="font-weight-normal">{{ $data['mdr']->departments->name }}</span></p>
                </td>
                <td>
                    <p class="font-weight-normal ml-1" style="font-size: 11;">Operational Objectives</p>
                </td>
                <td>
                    <p class="font-weight-normal ml-1 text-center" style="font-size: 11;">3.00</p>
                </td>
                <td>
                    <p class="font-weight-normal ml-1 text-center" style="font-size: 11;">{{ number_format($data['mdr']->grade,2) }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="font-weight-normal ml-1" style="font-size: 11;">Innovation</p>
                </td>
                <td>
                    <p class="font-weight-normal ml-1 text-center" style="font-size: 11;">1.50</p>
                </td>
                <td>
                    <p class="font-weight-normal ml-1 text-center" style="font-size: 11;">{{ number_format($data['mdr']->innovation_scores,2) }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="font-weight-normal ml-1" style="font-size: 11;">Timeliness</p>
                </td>
                <td>
                    <p class="font-weight-normal ml-1 text-center" style="font-size: 11;">0.50</p>
                </td>
                <td>
                    <p class="font-weight-normal ml-1 text-center" style="font-size: 11;">{{ number_format($data['mdr']->timeliness,2) }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="font-weight-bold ml-1" style="font-size: 11;">Month Covered: <span class="font-weight-normal">{{ date('F Y', strtotime($data['mdr']->year.'-'.$data['mdr']->month)) }}</span></p>
                </td>
                <td>
                    <p class="font-weight-bold ml-1" style="font-size: 11;">Total</p>
                </td>
                <td>
                    <p class="font-weight-bold ml-1 text-center" style="font-size: 11;">5.00</p>
                </td>
                <td>
                    @php
                        $total = floatval($data['mdr']->grade) + floatval($data['mdr']->innovation_scores) + floatval($data['mdr']->timeliness);
                    @endphp
                    <p class="font-weight-normal ml-1 text-center" style="font-size: 11;">{{ number_format($total,2) }}</p>
                </td>
            </tr>
        </table>
        <p class="text-right font-weight-bold mr-4" style="font-size: 10;">*Supported with verifiable evidence</p>

        <p class="ml-4" style="font-size: 10;">I. <span class="ml-4" style="text-decoration:underline;">Operational Objectives</span></p>

        <table cellpadding='0' cellspacing='0' border="1" style="width: 100%;" class="mt-1">
            <tr>
                <td rowspan="2" style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size:11">Key Performance Indicator (KPI):</p>
                </td>
                <td rowspan="2" style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size: 11;">Target</p>
                </td>
                <td rowspan="2" style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size: 11;">Actual</p>
                </td>
                <td rowspan="1" style="background:rgb(170, 170, 170)" colspan="2">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size: 11;">Result</p>
                </td>
                <td rowspan="2" style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size: 11;">Remarks / Weight Reason (unmet) / Action Plan</p>
                </td>
            </tr>
            <tr>
                <td style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold text-center p-2" style="font-size: 11;">Weight</p>
                </td>
                <td style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold text-center p-2" style="font-size: 11;">Score</p>
                </td>
            </tr>
            @foreach ($data['mdr']->departmentalGoals as $kpi)
            <tr>
                <td><p class="p-2">{!! nl2br(e($kpi->departmentKpi->name)) !!}</p></td>
                <td width="90"><p class="p-2">{!! nl2br(e($kpi->target)) !!}</p></td>
                <td width="90"><p class="p-2">{{ $kpi->actual }}</p></td>
                <td><p class="text-center p-2">{{ number_format($kpi->weight,2) }}</p></td>
                <td><p class="text-center p-2">{{ number_format($kpi->grade,2) }}</p></td>
                <td><p class="p-2">{!! nl2br(e($kpi->remarks)) !!}</p></td>
            </tr>
            @endforeach
        </table>

        <p class="ml-4 mt-2" style="font-size: 10;">II. <span class="ml-4" style="text-decoration:underline;">Innovation (Approved)</span></p>
        <table cellpadding='0' cellspacing='0' border="1" style="width: 100%;" class="mt-1">
            <tr>
                <td style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size:11">Project Charter</p>
                    <p class="font-weight-normal text-center"><i>(activity will be considered Innovation if the two (2) criteria is met: 1. Benefits/Impact, 2. Project Completion Report)</i></p>
                </td>
                <td style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size:11">Project Benefit</p>
                    <p class="font-weight-normal text-center"><i>(Time Savings/Reductions, Financial Impact)</i></p>
                </td>
                <td style="background:rgb(170, 170, 170)">
                    <p class="font-weight-bold m-0 p-2 text-center" style="font-size: 11;">Accomplishment Report</p>
                </td>
            </tr>
            <tr>
                <td>
                    @if($data['innovations'])
                    <p class="p-2">{{ $data['innovations']->project_charter }}</p>
                    @else
                    &nbsp;
                    @endif
                </td>
                <td>
                    @if($data['innovations'])
                    <p class="p-2">{{ $data['innovations']->project_benefit }}</p>
                    @else
                    &nbsp;
                    @endif
                </td>
                <td></td>
            </tr>
        </table>

        <table cellpadding='0' cellspacing='0' border="1" style="width: 100%;" class="mt-1">
            <tr>
                <td>
                    <p class="font-weight-normal m-0" style="font-size:10">Prepared by:</p>

                    <p class="font-weight-normal mb-0 mt-5 text-center" style="font-size:10">{{ $data['mdr']->departments->user->name }}</p>
                    <p class="font-weight-bold mb-0 text-center" style="font-size:10">(Department Head)</p>
                    <p class="font-weight-normal m-0 text-center" style="font-size:7">(Signature over Printed Name/Date)</p>
                </td>
                <td>
                    <p class="font-weight-normal m-0" style="font-size:10">Checked by:</p>
                    @php
                        $pmo = $data['users']->where('position','Performance Management Officer')->first();
                        $bpd_manager = $data['users']->where('position','Business Process Manager')->first();
                    @endphp
                    <p class="font-weight-normal mb-0 mt-5 text-center" style="font-size:10">{{ $pmo->name.' / '.$bpd_manager->name }}</p>
                    <p class="font-weight-bold mb-0 text-center" style="font-size:10">(PMO/ BP Manager)</p>
                    <p class="font-weight-normal m-0 text-center" style="font-size:7">(Signature over Printed Name/Date)</p>
                </td>
            </tr>
            <tr>
                <td>
                    @php
                        $cgo = $data['users']->where('position','Chief Governance Officer')->first();
                    @endphp
                    <p class="font-weight-normal m-0" style="font-size:10">Reviewed by:</p>
                    <p class="font-weight-normal mb-0 mt-5 text-center" style="font-size:10">{{ $cgo->name }}</p>
                    <p class="font-weight-bold mb-0 text-center" style="font-size:10">(Chief Governance Officer)</p>
                    <p class="font-weight-normal m-0 text-center" style="font-size:7">(Signature over Printed Name/Date)</p>
                </td>
                <td>
                    @php
                        $coo = $data['users']->where('position','Chief Operating Officer')->first();
                    @endphp
                    <p class="font-weight-normal m-0" style="font-size:10">Noted by:</p>

                    <p class="font-weight-normal mb-0 mt-5 text-center" style="font-size:10">{{ $coo->name }}</p>
                    <p class="font-weight-bold mb-0 text-center" style="font-size:10">(Chief Operating Officer)</p>
                    <p class="font-weight-normal m-0 text-center" style="font-size:7">(Signature over Printed Name/Date)</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="font-weight-normal m-0" style="font-size:10">Approved by:</p>
                    <p class="font-weight-bold mb-0 mt-5 text-center" style="font-size:10">(President)</p>
                    <p class="font-weight-normal m-0 text-center" style="font-size:7">(Signature over Printed Name/Date)</p>
                </td>
                <td>
                    <p class="font-weight-normal m-0" style="font-size:10">Approved by:</p>
                    <p class="font-weight-bold mb-0 mt-5 text-center" style="font-size:10">(CEO)</p>
                    <p class="font-weight-normal m-0 text-center" style="font-size:7">(Signature over Printed Name/Date)</p>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p class="font-weight-normal m-0" style="font-size:10">Approved by:</p>
                    <p class="font-weight-normal mb-0 mt-5 text-center" style="font-size:10">Wee Lee Hiong</p>
                    <p class="font-weight-bold mb-0 text-center" style="font-size:10">(Chairman)</p>
                    <p class="font-weight-normal m-0 text-center" style="font-size:7">(Signature over Printed Name/Date)</p>
                </td>
            </tr>
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