<div class="modal" id="mdrStatusModal{{optional($data->mdrSummary)->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">MDR Status</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                MDR Status
                            </div>
                            <div class="panel-body">
                                <div class='row text-center'>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>Approver</strong>
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>Status</strong>
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>Start Date</strong>
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>Action Date</strong>
                                    </div>
                                </div>

                                @foreach ($mdrApprovers->where('mdr_summary_id', optional($data->mdrSummary)->id) as $approver)
                                <div class="row text-center">
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->users->name}}
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->status}}
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->start_date}}
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>