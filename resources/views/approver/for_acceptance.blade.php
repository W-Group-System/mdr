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
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrs->where('is_accepted', null))}}</h1>
                    <small>Total For Approval</small>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Approved</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrApprovers->where('user_id', auth()->user()->id)->where('status', 'Approved'))}}</h1>
                    <small>Total Approved</small>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <table class="table table-bordered" id="forApprovalTable">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Department</th>
                                {{-- <th>PIC</th> --}}
                                <th>Month</th>
                                <th>Deadline</th>
                                <th>Submission Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mdrs->where('is_accepted', null) as $key => $mdr)
                            @php
                                $fullTargetDate = getAdjustedTargetDate($mdr->month, $mdr->year, $mdr->departments->target_date);
                            @endphp
                                {{-- @php
                                    $mdr = $approver->mdrRelationship;
                                @endphp --}}
                                <tr>
                                    <td>
                                        {{-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#viewStatus{{$mdr->id}}">
                                            <i class="fa fa-eye"></i>
                                        </button> --}}
                                        <a href="{{url('list_of_mdr/'.$mdr->id)}}" class="btn btn-warning btn-sm" onclick="show()">
                                            <i class="fa fa-pencil-square-o"></i>                                            
                                        </a>
                                    </td>
                                    <td>{{ $mdr->departments->name }}</td>
                                    {{-- <td>{{ optional($mdr->user)->name }}</td> --}}
                                    <td>{{ DateTime::createFromFormat('!m', $mdr->month)->format('F') }} {{ $mdr->year }}</td>
                                    <td>{{ $fullTargetDate->format('F d, Y') }}</td>
                                    <td>{{ date('F d, Y', strtotime($mdr->created_at)) }}</td>
                                </tr>

                                {{-- @include('approver.view_mdr_status') --}}
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
