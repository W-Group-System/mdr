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
                            <input name="attachment_description" id="attachment_description_edit_{{ $department_kpi->id }}" class="form-control" value="{{ $department_kpi->attachment_description }}">
                            <small id="descError_edit_{{ $department_kpi->id }}" class="text-danger" style="display:none;">Max 50 characters allowed.</small>
                        </div>
                        {{-- <div class="col-md-6">
                            Month:
                            <select name="month" class="form-control" required>
                                @foreach (range(1, 12) as $m)
                                    <option 
                                        value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                        {{ (int)$department_kpi->month === $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            Year:
                            <select name="year" id="year" class="form-control" required>
                                @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                    <option 
                                        value="{{ $y }}" 
                                        {{ (int)$department_kpi->year === (int)$y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div> --}}
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
    @foreach($department_kpis as $department_kpi)
        const input{{ $department_kpi->id }} = document.getElementById('attachment_description_edit_{{ $department_kpi->id }}');
        const error{{ $department_kpi->id }} = document.getElementById('descError_edit_{{ $department_kpi->id }}');
        
        input{{ $department_kpi->id }}.addEventListener('input', function() {
            if (this.value.length > 50) {
                error{{ $department_kpi->id }}.style.display = 'inline';
            } else {
                error{{ $department_kpi->id }}.style.display = 'none';
            }
        });
    @endforeach
});
</script>