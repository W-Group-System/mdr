{{-- @foreach ($mdrSummary as $mdrSummaryData)
<div class="modal" id="uploadNTEModal-{{ $mdrSummaryData->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Upload NTE Attachments</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{url('upload_nte/'.$mdrSummaryData->id) }}" method="post"
                            enctype="multipart/form-data" onsubmit="show()">
                            @csrf

                            <input type="hidden" name="yearAndMonth"
                                value="{{ $mdrSummaryData->year.'-'.$mdrSummaryData->month }}">
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
@if(auth()->user()->role=="Human Resources")
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
                                <a href="{{$mdrSummaryData->nteAttachments->filepath}}"
                                    target="_blank">{{$mdrSummaryData->nteAttachments->filename}}</a>
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
                                <option value="{{auth()->user()->id}}" {{auth()->
                                    user()->id==$mdrSummaryData->nteAttachments->acknowledge_by?'selected':''}}>{{auth()->user()->name}}
                                </option>
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
                @if($mdrSummaryData->nteAttachments->user_id != auth()->user()->id)
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

@if(auth()->user()->role=="Department Head")
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
                                <a href="{{$mdrSummaryData->nteAttachments->filepath}}"
                                    target="_blank">{{$mdrSummaryData->nteAttachments->filename}}</a>
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
                <h5 class="modal-title">Upload NTE Attachments</h5>
            </div>
            <form action="{{url('upload_nte/'.$mdrSummaryData->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                @csrf
                <input type="hidden" name="yearAndMonth" value="{{ $mdrSummaryData->yearAndMonth }}">
                <input type="hidden" name="departmentId" value="{{ $mdrSummaryData->department_id }}">
                <input type="hidden" name="mdrSummaryId" value="{{ $mdrSummaryData->id }}">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Upload NTE Attachment :
                            <input type="file" name="files" id="files" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>