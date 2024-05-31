@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="yearAndMonth">Year & Month</label>
                                <div class="form-group">
                                    <input type="month" name="yearAndMonth" id="yearAndMonth" class="form-control input-sm" max="{{ date('Y-m') }}" value="{{ $yearAndMonth }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label for="">&nbsp;</label>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>
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

                        <table class="table table-striped table-bordered table-hover" id="penaltiesTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Department Head</th>
                                    <th>Month</th>
                                    <th>Total Rating</th>
                                    <th>Uploaded By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary as $mdrSummaryData)
                                    @if(!empty($mdrSummaryData->nteAttachments))
                                    <tr>
                                        <td>{{ $mdrSummaryData->departments->dept_name }}</td>
                                        <td>{{ $mdrSummaryData->departments->user->name }}</td>
                                        <td>{{ date('F Y', strtotime($mdrSummaryData->year.'-'.$mdrSummaryData->month)) }}</td>
                                        <td>{{ $mdrSummaryData->rate }}</td>
                                        <td>{{ !empty($mdrSummaryData->nteAttachments->users->name) ? $mdrSummaryData->nteAttachments->users->name : '' }}</td>
                                        <td width="100">
                                            <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#uploadNTEModal-{{ $mdrSummaryData->id }}">
                                                <i class="fa fa-upload"></i>
                                            </button>
                                            
                                            <form action="{{ url('delete_nte/'.$mdrSummaryData->nteAttachments->id) }}" method="post" id="deleteNteForm">
                                                @csrf
                                            </form>

                                            <div>
                                                <a href="{{ $mdrSummaryData->nteAttachments->filepath }}" class="btn btn-sm btn-info" type="button" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                @if(Auth::user()->account_role == 4)
                                                <button class="btn btn-sm btn-danger" type="submit" form="deleteNteForm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @foreach ($mdrSummary as $mdrSummaryData)
                        <div class="modal fade" id="uploadNTEModal-{{ $mdrSummaryData->id }}">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Upload NTE Attachments</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ url('upload_nte/'.$mdrSummaryData->id) }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    
                                                    <input type="hidden" name="yearAndMonth" value="{{ $mdrSummaryData->year.'-'.$mdrSummaryData->month }}">
                                                    <input type="hidden" name="departmentId" value="{{ $mdrSummaryData->department_id }}">
                                                    <input type="hidden" name="mdrSummaryId" value="{{ $mdrSummaryData->id }}">

                                                    <div class="form-group">
                                                        <label for="files">Upload NTE Attachment</label>
                                                        <input type="file" name="files" id="files" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-sm btn-primary btn-block">Upload</button>
                                                    </div>
                                                </form>
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