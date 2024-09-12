@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    Pending
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary->where('status', 'Pending'))}}</h1>
                    <small>Total Pending</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    Approved
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary->where('status', 'Approved'))}}</h1>
                    <small>Total Approved</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <table class="table table-bordered" id="pendingApprovalTable">
                        <thead>
                            <tr>
                                <th>Approver Status</th>
                                <th>Department</th>
                                <th>PIC</th>
                                <th>Month</th>
                                <th>Submission Date</th>
                                <th>Deadline</th>
                                <th>MDR Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mdrSummary as $data)
                                <tr>
                                    <td width="10">
                                        <button class="btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#mdrStatusModal{{ $data->id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>{{ optional($data->departments)->name }}</td>
                                    <td>{{ optional($data->users)->name }}</td>
                                    <td>{{ date('F Y', strtotime($data->yearAndMonth)) }}</td>
                                    <td>{{ date('F d, Y', strtotime($data->submission_date)) }}</td>
                                    <td>{{ date('F d, Y', strtotime($data->deadline)) }}</td>
                                    <td>
                                        @if($data->status == "Pending")
                                        <span class="label label-warning">
                                        @elseif($data->status == "Approved")
                                        <span class="label label-primary">
                                        @endif

                                        {{$data->status}}
                                        </span>
                                        
                                    </td>
                                    
                                </tr>

                                @include('approver.pending_mdr_status')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#pendingApprovalTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });
    })
</script>
@endpush
