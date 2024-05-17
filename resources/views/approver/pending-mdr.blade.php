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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>PIC</th>
                                <th>Deadline</th>
                                <th>Submission Date</th>
                                <th>Approver Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mdrSummary as $mdrSummaryData)
                                @foreach ($mdrSummaryData->mdrStatus as $key => $status)
                                    @if(auth()->user()->id == $status->user_id)
                                        <tr>
                                            <td>{{ $mdrSummaryData->department_id }}</td>
                                            <td>{{ $mdrSummaryData->user_id }}</td>
                                            <td>{{ $mdrSummaryData->deadline }}</td>
                                            <td>{{ $mdrSummaryData->submission_date }}</td>
                                            <td>
                                                @foreach ($mdrSummaryData->mdrStatus as $key => $status)
                                                    {{ $key+1 .'. '. $status->user_id }} - {{ $status->status == 1 ? 'APPROVED' : 'WAITING' }} <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{ url('list_of_mdr/' . $mdrSummaryData->id) }}" class="btn btn-sm btn-info" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
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

@endpush
