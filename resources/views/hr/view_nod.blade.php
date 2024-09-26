<div class="modal" id="view{{$mdrSummaryData->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Status</h5>
            </div>
            <form action="{{url('nod_status/'.$mdrSummaryData->nodAttachments->id)}}" method="post" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="mdr_summary_id" value="{{$mdrSummaryData->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            View Files :
                            <span>
                                <a href="{{url($mdrSummaryData->nodAttachments->filepath)}}" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
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
                        <hr>
                        @if(auth()->user()->role == "Department Head")
                        <div class="col-md-12">
                            <div class="col-md-12">
                                Upload NOD Attachment :
                                <input type="file" name="files" id="files" class="form-control" required>
                            </div>
                        </div>
                        @else
                        <div class="col-md-4">
                            <input type="hidden" name="acknowledge_by" value="{{auth()->user()->id}}">
                            Acknowledge By :
                            {{auth()->user()->name}}
                        </div>
                        <div class="col-md-4">
                            Status :
                            <select name="status" id="status" class="form-control cat" required>
                                <option value="">-Status-</option>
                                <option value="Waived">Waived</option>
                                <option value="For PIP">For PIP</option>
                            </select>
                        </div>
                        @endif
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