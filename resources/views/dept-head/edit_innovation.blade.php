<div class="modal" id="edit{{$innovation->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Innovation</h5>
            </div>
            <form action="{{ url('updateInnovation/'.$innovation->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                @csrf

                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Project Ttitle :
                            <input type="text" name="project_charter" class="form-control input-sm" value="{{ $innovation->project_charter }}" required >
                        </div>
                        <div class="col-md-12">
                            Project Expectations/Benefits :
                            <textarea name="project_benefit" cols="30" rows="10" class="form-control" required>{{ $innovation->project_benefit }}</textarea>
                        </div>
                        <div class="col-md-12">
                            Attachment :
                            <input type="file" name="accomplishment_report[]" class="form-control" multiple >
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