@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Approval</h5>
                    <div class="pull-right">
                        <span class="label label-success">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrApprovers->where('user_id', auth()->user()->id)->where('status', 'Pending'))}}</h1>
                    <small>Total For Approval</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Approved</h5>
                    <div class="pull-right">
                        <span class="label label-primary">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrApprovers->where('user_id', auth()->user()->id)->where('status', 'Approved'))}}</h1>
                    <small>Total Approved</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <table class="table table-bordered" id="forApprovalTable">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Department</th>
                                <th>PIC</th>
                                <th>Month</th>
                                <th>Deadline</th>
                                <th>Submission Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mdrApprovers->where('status', 'Pending') as $key => $approver)
                                @php
                                    $mdr = $approver->mdrRelationship;
                                @endphp
                                <tr>
                                    <td>
                                        {{-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#viewStatus{{$mdr->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button> --}}
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#mdrStatusModal{{$mdr->id}}">
                                                <i class="fa fa-history"></i>
                                        </button>
                                        <a href="{{url('list_of_mdr/'.$mdr->id)}}" class="btn btn-warning btn-sm" onclick="show()">
                                            <i class="fa fa-pencil-square-o"></i>                                            
                                        </a>
                                    </td>
                                    <td>{{ $mdr->departments->name }}</td>
                                    <td>
                                        @if ($mdr->departments->user)
                                            {{ $mdr->departments->user->name}}
                                        @endif
                                    </td>
                                    <td>{{ DateTime::createFromFormat('!m', $mdr->month)->format('F') }} {{ $mdr->year }}</td>
                                    <td></td>
                                    <td>{{ date('F d, Y', strtotime($mdr->created_at)) }}</td>
                                </tr>

                                {{-- @include('approver.view_mdr_status') --}}
                                @include('dept-head.view_mdr_status')
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
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {
        $(".cat").chosen({width: "100%"})

        $('#forApprovalTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });
    })
</script>
@endpush
