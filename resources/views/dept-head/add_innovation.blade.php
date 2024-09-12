<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Innovation</h5>
            </div>
            <form action="{{ url('addInnovation') }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                {{-- <input type="hidden" name="mdr_group_id" value="{{ $dptGroupData->id }}"> --}}
                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Innovation Projects :
                            <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" required >
                        </div>
                        <div class="col-md-12">
                            Project Summary :
                            <textarea name="projectSummary" cols="30" rows="10" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-12">
                            Job / Work Number :
                            <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Start Date :
                            <input type="date" class="form-control input-sm" name="startDate" required>
                        </div>
                        <div class="col-md-12">
                            Target Date :
                            <input type="date" class="form-control input-sm" name="targetDate" required>
                        </div>
                        <div class="col-md-12">
                            Actual Date :
                            <input type="date" class="form-control input-sm" name="actualDate" required>
                        </div>
                        <div class="col-md-12">
                            Supporting Documents :
                            <input type="file" name="file[]" id="file" class="form-control" multiple>
                        </div>
                        <div class="col-md-12">
                            Remarks :
                            <textarea name="remarks" id="remarks" class="form-control input-sm" cols="30" rows="10" required></textarea>
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