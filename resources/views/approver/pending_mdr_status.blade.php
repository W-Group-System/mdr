<div class="modal" id="mdrStatusModal{{ $data->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">MDR Status</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading"></div>
                            <div class="panel-body">
                                <div class="row text-center">
                                    <div class="col-md-4 border border-primary border-top-bottom border-left-right">
                                        <strong>Approver</strong>
                                    </div>
                                    <div class="col-md-4 border border-primary border-top-bottom border-left-right">
                                        <strong>Status</strong>
                                    </div>
                                    <div class="col-md-4 border border-primary border-top-bottom border-left-right">
                                        <strong>Date Approved</strong>
                                    </div>
                                </div>
                                @foreach ($data->approvers as $approver)
                                <div class="row text-center">
                                    <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->users->name}}
                                    </div>
                                    <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                        {{$approver->status}}
                                    </div>
                                    <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                        @if($approver->start_date != null)
                                        {{$approver->start_date}}
                                        @else
                                        No Date 
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>