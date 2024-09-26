{{-- @foreach ($mdrSummary as $mdrSummaryData)
@if(auth()->user()->role == "Human Resources")
@if(!empty($mdrSummaryData->pipAttachments))
<div class="modal" id="viewModal-{{$mdrSummaryData->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">View Status</h1>
            </div>
            <form action="{{url('pip_status/'.$mdrSummaryData->pipAttachments->id)}}" method="post" onsubmit="show()">
                @csrf
                <input type="hidden" name="mdr_summary_id" value="{{$mdrSummaryData->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            View Files :
                            <span>
                                <a href="{{$mdrSummaryData->pipAttachments->filepath}}"
                                    target="_blank">{{$mdrSummaryData->pipAttachments->filename}}</a>
                            </span>
                        </div>
                        <div class="col-md-4">
                            Acknowledge by :
                            <span>{{isset($mdrSummaryData->pipAttachments->acknowledge->name)?$mdrSummaryData->pipAttachments->acknowledge->name:''}}</span>
                        </div>
                        &nbsp;
                        @if($mdrSummaryData->pipAttachments->user_id != auth()->user()->id)
                        <hr>
                        <div class="col-md-4">
                            Acknowledge By :
                            <select name="acknowledge_by" id="acknowledgeBy" class="form-control cat" required>
                                <option value="">-Acknowledge-</option>
                                <option value="{{auth()->user()->id}}" {{auth()->
                                    user()->id==$mdrSummaryData->pipAttachments->acknowledge_by ? 'selected' :
                                    ''}}>{{auth()->user()->name}}</option>
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
                @if($mdrSummaryData->pipAttachments->user_id != auth()->user()->id)
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
@elseif(auth()->user()->role == "Department Head")
@if(!empty($mdrSummaryData->pipAttachments))

@endif
@endif
@endforeach --}}

<div class="modal" id="upload{{ $mdrSummaryData->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload PIP Attachments</h5>
            </div>
            <form action="{{ url('upload_pip/'.$mdrSummaryData->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                @csrf

                <input type="hidden" name="yearAndMonth" value="{{ $mdrSummaryData->yearAndMonth }}">
                <input type="hidden" name="departmentId" value="{{ $mdrSummaryData->department_id }}">
                <input type="hidden" name="mdrSummaryId" value="{{ $mdrSummaryData->id }}">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            Upload PIP Attachment :
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