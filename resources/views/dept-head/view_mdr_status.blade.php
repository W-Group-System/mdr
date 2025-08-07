<div class="modal" id="mdrStatusModal{{$mdr->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Histories</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Acceptance History Logs
                            </div>
                            <div class="panel-body">
                                <div class='row text-center'>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>User</strong>
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>Action</strong>
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>Remarks</strong>
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        <strong>Date</strong>
                                    </div>
                                </div>

                                @foreach ($mdr->mdrHistoryLogs as $historyLogs)
                                <div class="row text-center">
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        {{$historyLogs->user->name}}
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        {{$historyLogs->action}}
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        {{ $historyLogs->remarks }}
                                    </div>
                                    <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                        {{date('M d Y', strtotime($historyLogs->created_at))}}
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