<div class="modal" id="view{{$mdr->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Status</h5>
            </div>
            <form action="{{url('approver_mdr/'.$approver->id)}}" method="post" onsubmit="show()">
                @csrf
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
                                        <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                            <strong>Start Date</strong>
                                        </div>
                                        <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                            <strong>Action Date</strong>
                                        </div>
                                        <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                            <strong>Remarks</strong>
                                        </div>
                                    </div>
                                    @foreach ($mdrSummary->approvers->where('mdr_summary_id', $mdr->id) as $approver)
                                    <div class="row text-center">
                                        <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                            {{$approver->users->name}}
                                        </div>
                                        <div class='col-md-3 border border-primary border-top-bottom border-left-right'>
                                            {{$approver->status}}
                                        </div>
                                        <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                            {{$approver->start_date}}
                                        </div>
                                        <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                            @if($approver->status == "Approved" || $approver->status == "Returned")
                                            {{date('Y-m-d', strtotime($approver->updated_at))}}
                                            @endif  
                                        </div>
                                        <div class='col-md-2 border border-primary border-top-bottom border-left-right'>
                                            {{$approver->remarks}}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            Actions :
                            <select name="action" class="form-control cat">
                                <option value="">Select Action</option>
                                <option value="Approved">Approved</option>
                                <option value="Returned">Returned</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            Remarks :
                            <textarea name="remarks" class="form-control" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>