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
                    <h5>Notice of Explanation</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary)}}</h1>
                    <small>Total NTE</small>
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

                                            @if(!empty($mdrSummaryData->nteAttachments))
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewModal-{{$mdrSummaryData->id}}" type="button" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @foreach ($mdrSummary as $mdrSummaryData)
                        <div class="modal" id="uploadNTEModal-{{ $mdrSummaryData->id }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Upload NTE Attachments</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{url('upload_nte/'.$mdrSummaryData->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
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

                        @if(!empty($mdrSummaryData->nteAttachments))
                            <div class="modal" id="viewModal-{{$mdrSummaryData->id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">View Status</h1>
                                        </div>
                                        <form action="{{url('nte_status/'.$mdrSummaryData->nteAttachments->id)}}" method="post" onsubmit="show()">
                                            @csrf
                                            <input type="hidden" name="mdr_summary_id" value="{{$mdrSummaryData->id}}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        View Files :
                                                        <span>
                                                            <a href="{{$mdrSummaryData->nteAttachments->filepath}}" target="_blank">{{$mdrSummaryData->nteAttachments->filename}}</a>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Acknowledge by :
                                                        <span>{{isset($mdrSummaryData->nteAttachments->acknowledge->name)?$mdrSummaryData->nteAttachments->acknowledge->name:''}}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Status :
                                                        <span>{{$mdrSummaryData->nteAttachments->status}}</span>
                                                    </div>
                                                    &nbsp;
                                                    @if($mdrSummaryData->nteAttachments->user_id != auth()->user()->id)
                                                        <hr>
                                                        <div class="col-md-4">
                                                            Acknowledge By :
                                                            <select name="acknowledge_by" id="acknowledgeBy" class="form-control cat" required>
                                                                <option value="">-Acknowledge-</option>
                                                                <option value="{{auth()->user()->id}}" {{auth()->user()->id==$mdrSummaryData->nteAttachments->acknowledge_by?'selected':''}}>{{auth()->user()->name}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Status :
                                                            <select name="status" id="status" class="form-control cat" required>
                                                                <option value="">-Status-</option>
                                                                <option value="Waived">Waived</option>
                                                                <option value="For NOD">For NOD</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
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
            $('#penaltiesTable').DataTable({
                pageLength: 10,
                ordering: false,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [],
            });

            $(".cat").chosen({width: "100%"});
        })
    </script>
@endpush