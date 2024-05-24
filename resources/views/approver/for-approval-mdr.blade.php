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
                    <table class="table table-bordered" id="forApprovalTable">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>PIC</th>
                                <th>Month</th>
                                <th>Deadline</th>
                                <th>Submission Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mdrSummary as $mdrSummaryData)
                                @foreach ($mdrSummaryData->mdrStatus as $key => $status)
                                    @if(auth()->user()->id == $status->user_id)
                                        <tr>
                                            <td>{{ $mdrSummaryData->departments->dept_name }}</td>
                                            <td>{{ $mdrSummaryData->users->name }}</td>
                                            <td>{{ date('F Y', strtotime($mdrSummaryData->year.'-'.$mdrSummaryData->month)) }}</td>
                                            <td>{{ date('F d, Y', strtotime($mdrSummaryData->deadline)) }}</td>
                                            <td>{{ date('F d, Y', strtotime($mdrSummaryData->submission_date)) }}</td>
                                            <td>
                                                <form action="{{ url('list_of_mdr') }}" method="get" target="_blank">

                                                    <input type="hidden" name="department_id" value="{{ $mdrSummaryData->department_id }}">
                                                    <input type="hidden" name="yearAndMonth" value="{{ $mdrSummaryData->year.'-'.$mdrSummaryData->month}}">

                                                    <button type="submit" class="btn btn-sm btn-info viewMdr">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
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
