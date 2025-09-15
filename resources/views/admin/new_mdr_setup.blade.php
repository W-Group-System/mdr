<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Add MDR Setup</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{url('addDepartmentKpi')}}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Departments :
                            <select name="department" id="department" class="form-control">
                                <option value="">-Departments-</option>
                                @foreach ($departmentList as $departmentData)
                                    <option value="{{ $departmentData->id }}">{{ $departmentData->code.' - '.$departmentData->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Department KPI :
                            <textarea name="kpiName" id="" class="form-control" cols="30" rows="10" required></textarea>
                        </div>
                        <div class="col-md-12">
                            Target :
                            <textarea name="target" id="" class="form-control" cols="30" rows="10" required></textarea>
                        </div>
                        <div class="col-md-12">
                            Attachment Needed
                            <input name="attachment_description" id="attachment_description" class="form-control">
                            <small id="descError" class="text-danger" style="display:none;">Max 50 characters allowed.</small>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('attachment_description');
    const error = document.getElementById('descError');

    input.addEventListener('input', function() {
        if (this.value.length > 50) {
            error.style.display = 'inline';
        } else {
            error.style.display = 'none';
        }
    });
});
</script>