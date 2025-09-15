<div class="modal" id="editModal{{$department_kpi->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Edit MDR Setup</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{url('updateDepartmentsKpi/'.$department_kpi->id)}}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Departments :
                            <select name="department" id="department" class="form-control">
                                <option value="">-Departments-</option>
                                @foreach ($departmentList as $departmentData)
                                    <option value="{{ $departmentData->id }}" @if($departmentData->id == $department_kpi->department_id) selected @endif>{{ $departmentData->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Department KPI :
                            <textarea name="kpiName" id="" class="form-control" cols="30" rows="10" required>{{$department_kpi->name}}</textarea>
                        </div>
                        <div class="col-md-12">
                            Target :
                            <textarea name="target" id="" class="form-control" cols="30" rows="10" required>{{$department_kpi->target}}</textarea>
                        </div>
                        <div class="col-md-12">
                            Attachment Needed
                            <input name="attachment_description" id="" class="form-control" value="{{ $department_kpi->attachment_description }}">
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