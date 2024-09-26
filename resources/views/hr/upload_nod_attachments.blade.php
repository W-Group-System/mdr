{{-- @foreach ($mdrSummary as $mdrSummaryData)


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
                                <a href="{{$mdrSummaryData->nodAttachments->filepath}}"
                                    target="_blank">{{$mdrSummaryData->nodAttachments->filename}}</a>
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
                                <option value="{{auth()->user()->id}}" {{auth()->
                                    user()->id==$mdrSummaryData->nodAttachments->acknowledge_by ? 'selected' :
                                    ''}}>{{auth()->user()->name}}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            Status :
                            <select name="status" id="status" class="form-control cat" required>
                                <option value="">-Status-</option>
                                <option value="Waived" {{$mdrSummaryData->nodAttachments->status == 'Waived' ?
                                    'selected' : ''}}>Waived</option>
                                <option value="For PIP" {{$mdrSummaryData->nodAttachments->status == 'For PIP' ?
                                    'selected' : ''}}>For PIP</option>
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
                                <a href="{{$mdrSummaryData->nodAttachments->filepath}}"
                                    target="_blank">{{$mdrSummaryData->nodAttachments->filename}}</a>
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
@endforeach --}}

<div class="modal" id="upload{{ $mdrSummaryData->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload NOD Attachments</h5>
            </div>
            <form action="{{ url('upload_nod/'.$mdrSummaryData->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                @csrf
                <input type="hidden" name="yearAndMonth" value="{{ $mdrSummaryData->yearAndMonth }}">
                <input type="hidden" name="departmentId" value="{{ $mdrSummaryData->department_id }}">
                <input type="hidden" name="mdrSummaryId" value="{{ $mdrSummaryData->id }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Upload NOD Attachment :
                            <input type="file" name="files" id="files" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>