<div class="modal" id="view{{$mdrSummaryData->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Penalties</h5>
            </div>
            <div class="modal-body">
                @if(!empty($mdrSummaryData->nteAttachments))
                <div class="row">
                    <div class="col-md-4">
                        NTE Files :
                        <a href="{{url($mdrSummaryData->nteAttachments->filepath)}}" target="_blank">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    </div>
                    <div class="col-md-4">
                        Acknowledge by :
                        <span>{{isset($mdrSummaryData->nteAttachments->acknowledge->name)?$mdrSummaryData->nteAttachments->acknowledge->name:''}}</span>
                    </div>
                    <div class="col-md-4">
                        Status :
                        <span>{{$mdrSummaryData->nteAttachments->status}}</span>
                    </div>
                </div>
                @endif
                <hr>
                @if(!empty($mdrSummaryData->nodAttachments))
                <div class="row">
                    <div class="col-md-4">
                        NOD Files :
                        <a href="{{url($mdrSummaryData->nodAttachments->filepath)}}" target="_blank">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
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
                @endif
                <hr>
                @if(!empty($mdrSummaryData->pipAttachments))
                <div class="row">
                    <div class="col-md-4">
                        PIP Files :
                        <a href="{{url($mdrSummaryData->nteAttachments->filepath)}}" target="_blank">
                            <i class="fa fa-file-pdf-o"></i>
                        </a>
                    </div>
                    <div class="col-md-4">
                        Acknowledge by :
                        <span>{{isset($mdrSummaryData->nteAttachments->acknowledge->name)?$mdrSummaryData->nteAttachments->acknowledge->name:''}}</span>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>