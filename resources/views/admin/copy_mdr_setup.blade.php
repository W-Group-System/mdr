<div class="modal fade" id="duplicateModal{{ $department_kpi->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Duplicate KPI for Next Month</h5>
      </div>

      <form method="POST" action="{{ url('duplicateDepartmentKpiSave/'.$department_kpi->id) }}" onsubmit="show()">
        @csrf
        <div class="modal-body">
          <div class="row">
            
            <div class="col-md-6">
              Department:
              <select name="department" class="form-control" required>
                <option value="">-Select Department-</option>
                @foreach ($departmentList as $dept)
                  <option value="{{ $dept->id }}" {{ $dept->id == $department_kpi->department_id ? 'selected' : '' }}>
                    {{ $dept->code }} - {{ $dept->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-12 mt-3">
              Department KPI:
              <textarea name="kpiName" cols="30" rows="10" class="form-control" required>{{ $department_kpi->name }}</textarea>
            </div>

            <div class="col-md-12 mt-3">
              Target:
              <textarea name="target" cols="30" rows="10" class="form-control" required>{{ $department_kpi->target }}</textarea>
            </div>

            <div class="col-md-12 mt-3">
              Attachment Needed:
              <input name="attachment_description" class="form-control" value="{{ $department_kpi->attachment_description }}">
            </div>

            <div class="col-md-6">
              Month:
              <select name="month" class="form-control" required>
                @foreach (range(1, 12) as $m)
                  <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                    {{ (int)$m == ((int)$department_kpi->month == 12 ? 1 : (int)$department_kpi->month + 1) ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
                Year:
                <select name="year" id="year" class="form-control" required>
                  @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                  @endfor
                </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Duplicate</button>
        </div>
      </form>
    </div>
  </div>
</div>
