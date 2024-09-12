<div class="modal" id="editKpi{{$dptGoals->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Edit KPI</h5>
            </div>
            <form method="POST" action="{{url('update_kpi/'.$dptGoals->id)}}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Actual :
                            <textarea name="actual" class="form-control" cols="30" rows="10" required>{!! nl2br($dptGoals->actual) !!}</textarea>
                        </div>
                        <div class="col-md-12">
                            Grade :
                            <input type="number" name="grade" class="form-control input-sm" value="{{$dptGoals->grade}}" required>
                        </div>
                        <div class="col-md-12">
                            Remarks :
                            <textarea name="remarks" class="form-control" cols="30" rows="10" required>{!! nl2br($dptGoals->remarks) !!}</textarea>
                        </div>
                        <div class="col-md-12">
                            Attachments :
                            <input type="file" name="file" class="form-control">
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