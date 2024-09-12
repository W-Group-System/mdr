<div class="modal" id="editModal{{$data->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Innovation</h5>
            </div>
            <form action="{{ url('updateInnovation/'.$data->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                @csrf

                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Innovation Projects :
                            <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" value="{{$data->projects}}" required >
                        </div>
                        <div class="col-md-12">
                            Project Summary :
                            <textarea name="projectSummary" cols="30" rows="10" class="form-control" required>{{$data->project_summary}}</textarea>
                        </div>
                        <div class="col-md-12">
                            Job / Work Number :
                            <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" value="{{$data->work_order_number}}" required>
                        </div>
                        <div class="col-md-12">
                            Start Date :
                            <input type="date" class="form-control input-sm" name="startDate" value="{{$data->start_date}}" required>
                        </div>
                        <div class="col-md-12">
                            Target Date :
                            <input type="date" class="form-control input-sm" name="targetDate" value="{{$data->target_date}}" required>
                        </div>
                        <div class="col-md-12">
                            Actual Date :
                            <input type="date" class="form-control input-sm" name="actualDate" value="{{$data->actual_date}}" required>
                        </div>
                        <div class="col-md-12">
                            Supporting Documents :
                            <input type="file" name="file[]" id="file" class="form-control" multiple>
                        </div>
                        <div class="col-md-12">
                            Remarks :
                            <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10" required>{{$data->remarks}}</textarea>
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