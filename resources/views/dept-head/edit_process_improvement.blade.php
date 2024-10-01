<div class="modal" id="editProcessDevelopment{{$processDevelopmentData->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Process Improvement</h5>
            </div>
            <form action="{{ url('updateProcessDevelopment/' . $processDevelopmentData->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                @csrf
                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Activities :
                            <input type="text" name="activities" id="description" class="form-control input-sm" value="{{$processDevelopmentData->activities}}" required>
                        </div>
                        <div class="col-md-12">
                            Benefits :
                            <textarea name="benefits" id="benefits" class="form-control" cols="30" rows="10" required>{{$processDevelopmentData->remarks}}</textarea>
                        </div>
                        <div class="col-md-12">
                            DICR Number :
                            <input type="text" name="dicr_number" class="form-control input-sm" value="{{$processDevelopmentData->dicr_number}}" required>
                        </div>
                        <div class="col-md-12">
                            Date Approved :
                            <input type="date" name="date_approved" class="form-control input-sm" value="{{$processDevelopmentData->accomplished_date}}" required>
                        </div>
                        <div class="col-md-12">
                            Upload an Attachments :
                            <input type="file" name="file[]" id="file" class="form-control" multiple>
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