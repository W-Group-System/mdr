<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Add Company</h5>
            </div>
            <form action="{{url('add_company')}}" method="POST" onsubmit="show()">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            Code :
                            <input type="text" name="code" class="form-control input-sm" required>
                        </div>
                        <div class="col-sm-12">
                            Name :
                            <input type="text" name="name" class="form-control input-sm" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>