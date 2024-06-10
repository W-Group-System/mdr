@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        @if(auth()->user()->role=="Human Resources")
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Notice of Disciplinary</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary)}}</h1>
                    <small>Total NOD</small>
                </div>
            </div>
        </div>
        @endif
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
                                    @if(auth()->user()->role=="Human Resources")
                                    <tr>
                                        <td>{{ $mdrSummaryData->departments->dept_name }}</td>
                                        <td>{{ $mdrSummaryData->departments->user->name }}</td>
                                        <td>{{ date('F Y', strtotime($mdrSummaryData->year.'-'.$mdrSummaryData->month)) }}</td>
                                        <td>{{ $mdrSummaryData->rate }}</td>
                                        <td>{{ !empty($mdrSummaryData->nodAttachments->users->name) ? $mdrSummaryData->nodAttachments->users->name : '' }}</td>
                                        <td width="100">
                                            <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#uploadNTEModal-{{ $mdrSummaryData->id }}">
                                                <i class="fa fa-upload"></i>
                                            </button>

                                            @if(!empty($mdrSummaryData->nodAttachments))
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewModal-{{$mdrSummaryData->id}}" type="button" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif

                                    @if(auth()->user()->role == "Department Head")
                                        @if(!empty($mdrSummaryData->nodAttachments))
                                        <tr>
                                            <td>{{ $mdrSummaryData->departments->dept_name }}</td>
                                            <td>{{ $mdrSummaryData->departments->user->name }}</td>
                                            <td>{{ date('F Y', strtotime($mdrSummaryData->year.'-'.$mdrSummaryData->month)) }}</td>
                                            <td>{{ $mdrSummaryData->rate }}</td>
                                            <td>{{ !empty($mdrSummaryData->nodAttachments->users->name) ? $mdrSummaryData->nodAttachments->users->name : '' }}</td>
                                            <td width="100">
                                                <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#uploadNTEModal-{{ $mdrSummaryData->id }}">
                                                    <i class="fa fa-upload"></i>
                                                </button>

                                                @if(!empty($mdrSummaryData->nodAttachments))
                                                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewModal-{{$mdrSummaryData->id}}" type="button" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @endif
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @foreach ($mdrSummary as $mdrSummaryData)
                        <div class="modal" id="uploadNTEModal-{{ $mdrSummaryData->id }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Upload NOD Attachments</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ url('upload_nod/'.$mdrSummaryData->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                                                    @csrf
                                                    
                                                    <input type="hidden" name="yearAndMonth" value="{{ $mdrSummaryData->year.'-'.$mdrSummaryData->month }}">
                                                    <input type="hidden" name="departmentId" value="{{ $mdrSummaryData->department_id }}">
                                                    <input type="hidden" name="mdrSummaryId" value="{{ $mdrSummaryData->id }}">

                                                    <div class="form-group">
                                                        <label for="files">Upload NOD Attachment</label>
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

                        @if(!empty($mdrSummaryData->nodAttachments))
                            @if(auth()->user()->role == "Human Resources")
                            <div class="modal" id="viewModal-{{$mdrSummaryData->id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">View Status</h1>
                                        </div>
                                        <form action="{{url('nod_status/'.$mdrSummaryData->nodAttachments->id)}}" method="post" onsubmit="show()">
                                            @csrf
                                            <input type="hidden" name="mdr_summary_id" value="{{$mdrSummaryData->id}}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        View Files :
                                                        <span>
                                                            <a href="{{$mdrSummaryData->nodAttachments->filepath}}" target="_blank">{{$mdrSummaryData->nodAttachments->filename}}</a>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Acknowledge by :
                                                        <span>{{isset($mdrSummaryData->nodAttachments->acknowledge->name)?$mdrSummaryData->nodAttachments->acknowledge->name:''}}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Status :
                                                        <span>{{$mdrSummaryData->nodAttachments->status}}</span>
                                                    </div>
                                                    &nbsp;
                                                    @if($mdrSummaryData->nodAttachments->user_id != auth()->user()->id)
                                                        <hr>
                                                        <div class="col-md-4">
                                                            Acknowledge By :
                                                            <select name="acknowledge_by" id="acknowledgeBy" class="form-control cat" required>
                                                                <option value="">-Acknowledge-</option>
                                                                <option value="{{auth()->user()->id}}"{{auth()->user()->id==$mdrSummaryData->nodAttachments->acknowledge_by ? 'selected' : ''}}>{{auth()->user()->name}}</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            Status :
                                                            <select name="status" id="status" class="form-control cat" required>
                                                                <option value="">-Status-</option>
                                                                <option value="Waived" {{$mdrSummaryData->nodAttachments->status == 'Waived' ? 'selected' : ''}}>Waived</option>
                                                                <option value="For PIP" {{$mdrSummaryData->nodAttachments->status == 'For PIP' ? 'selected' : ''}}>For PIP</option>
                                                            </select>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($mdrSummaryData->nodAttachments->user_id != auth()->user()->id)
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(auth()->user()->role == "Department Head")
                            <div class="modal" id="viewModal-{{$mdrSummaryData->id}}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">View Status</h1>
                                        </div>
                                        <form action="{{url('nod_status/'.$mdrSummaryData->nodAttachments->id)}}" method="post" onsubmit="show()">
                                            @csrf
                                            <input type="hidden" name="mdr_summary_id" value="{{$mdrSummaryData->id}}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        View Files :
                                                        <span>
                                                            <a href="{{$mdrSummaryData->nodAttachments->filepath}}" target="_blank">{{$mdrSummaryData->nodAttachments->filename}}</a>
                                                        </span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Acknowledge by :
                                                        <span>{{isset($mdrSummaryData->nodAttachments->acknowledge->name)?$mdrSummaryData->nodAttachments->acknowledge->name:''}}</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        Status :
                                                        <span>{{$mdrSummaryData->nodAttachments->status}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
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