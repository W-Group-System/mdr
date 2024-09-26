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
                    <h5>Performance Improvement Plan</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary)}}</h1>
                    <small>Total PIP</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        @if (Session::has('errors'))
                            <div class="alert alert-danger">
                                @foreach (Session::get('errors') as $errors)
                                    {{ $errors }}<br>
                                @endforeach
                            </div>
                        @endif

                        <table class="table table-bordered" id="penaltiesTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Department</th>
                                    <th>Department Head</th>
                                    <th>Month</th>
                                    <th>Total Rating</th>
                                    <th>Uploaded By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary as $mdrSummaryData)
                                <tr>
                                    <td width="100">
                                        @if($mdrSummaryData->pipAttachments == null && auth()->user()->role == "Human Resources")
                                        <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#upload{{ $mdrSummaryData->id }}">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                        @endif
                                        
                                        @if($mdrSummaryData->pipAttachments != null)
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#view{{$mdrSummaryData->id}}" type="button" title="View">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ $mdrSummaryData->departments->name }}</td>
                                    <td>{{ $mdrSummaryData->user->name }}</td>
                                    <td>{{ date('F Y', strtotime($mdrSummaryData->yearAndMonth)) }}</td>
                                    <td>{{ $mdrSummaryData->mdrScoreHasOne->total_rating }}</td>
                                    <td>{{ !empty($mdrSummaryData->pipAttachments->users->name) ? $mdrSummaryData->pipAttachments->users->name : '' }}</td>
                                </tr>

                                @include('hr.upload_pip_attachments')

                                @if($mdrSummaryData->pipAttachments != null)
                                @include('hr.view_pip')
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>                    
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

            $('#penaltiesTable').DataTable({
                pageLength: 10,
                ordering: false,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [],
            });

        })
    </script>
@endpush