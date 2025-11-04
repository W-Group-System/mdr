<div class="modal" id="newWeight">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">New Weight</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{ url('add-operational-objective-setup') }}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Max Weight :
                            <input type="number" name="weight" class="form-control input-sm" step="0.01" min="0" required>
                        </div>
                        <div class="col-md-12">
                            Effective Date :
                            <input type="date" name="effective_date" class="form-control input-sm" required> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
