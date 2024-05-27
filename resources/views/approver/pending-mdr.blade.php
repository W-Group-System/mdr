@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <table class="table table-bordered" id="pendingApprovalTable">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>PIC</th>
                                <th>Month</th>
                                <th>Submission Date</th>
                                <th>Deadline</th>
                                <th>MDR Status</th>
                                <th>Approver Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mdrSummary as $data)
                                <tr>
                                    <td>{{ $data->departments->dept_name }}</td>
                                    <td>{{ $data->users->name }}</td>
                                    <td>{{ date('F Y', strtotime($data->year.'-'.$data->month)) }}</td>
                                    <td>{{ date('F d, Y', strtotime($data->submission_date)) }}</td>
                                    <td>{{ date('F d, Y', strtotime($data->deadline)) }}</td>
                                    <td>
                                        <span class="{{ $data->final_approved == 1 ? 'label label-primary' : 'label label-warning'}}">
                                            {{ $data->final_approved == 1 ? 'Approved' : 'Waiting' }}
                                        </span>
                                    </td>
                                    <td width="10">
                                        <button class="btn btn-sm btn-info" type="button" data-toggle="modal" data-target="#approverStatusModal-{{ $data->id }}">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @foreach ($mdrSummary as $data)
                        <div class="modal fade" id="approverStatusModal-{{ $data->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Approver Status</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Approver</th>
                                                            <th>Status</th>
                                                            <th>Date Approved</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data->mdrStatus as $approverData)
                                                            <tr>
                                                                <td>{{ $approverData->users->name }}</td>
                                                                <td>{{ $approverData->status == 1 ? 'APPROVED' : 'WAITING'}}</td>
                                                                <td>{{ !empty($approverData->start_date) ? date('F d, Y', strtotime($approverData->start_date )) : 'No Date' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
