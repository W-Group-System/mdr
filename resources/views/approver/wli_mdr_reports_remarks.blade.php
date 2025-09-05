<div class="modal" id="remarksModal{{ $data->departments->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">MDR Remarks - {{ $data->departments->code }}</h5>
            </div>
            <form method="POST" action="{{url('store_remarks')}}" onsubmit="show()">
                @csrf
                
                <input type="hidden" name="year_and_month" value="{{ $year_month }}">
                <input type="hidden" name="department_id" value="{{ $data->departments->id }}">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Remarks :
                            <textarea name="remarks" class="form-control" cols="30" rows="10" required></textarea>
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