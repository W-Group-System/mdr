<div class="modal" id="editModal-{{$c->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-left">Edit Company</h6>
            </div>
            <form action="{{url('update_company/'.$c->id)}}" method="POST" onsubmit="show()">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control input-sm" placeholder="Enter code" value="{{$c->code}}" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control input-sm" placeholder="Enter name" value="{{$c->name}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>