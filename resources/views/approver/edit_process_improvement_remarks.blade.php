<div class="modal" id="edit{{$item->id}}">
    <div class="modal-dialog modal-dialog-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Remarks</h5>
            </div>
            <form action="{{url('add_pd_remarks/'.$item->id)}}" method="post" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Remarks :
                            <textarea name="remarks" class="form-control" cols="30" rows="10">{{$item->remarks}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>