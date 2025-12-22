<div class="modal" id="editKpi">
    <div class="modal-dialog modal-lg" id="kpiModal">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Edit KPI</h5>
            </div>
            <form method="POST" action="{{url('update_kpi')}}" id="mdrFormEdit" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                {{-- <input type="hidden" name="yearAndMonth" value="{{$yearAndMonth}}">
                <input type="hidden" name="target_date" value="{{auth()->user()->department->target_date}}"> --}}
                <input type="hidden" name="save_type" id="save_type" value="final">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Department KPI
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>KPI</th>
                                                    <th>Target</th>
                                                    <th>Actual</th>
                                                    {{-- <th>Grade</th> --}}
                                                    <th>Remarks</th>
                                                    <th>Attachments</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($departmentalGoals as $key=>$dptGoals)
                                                    <input type="hidden" name="department_goals_id[]" value="{{$dptGoals->id}}">
                                                    <input type="hidden" name="mdr_id[]" value="{{$mdr->id}}">
                                                    <tr>
                                                        <td>
                                                            {!! nl2br($dptGoals->departmentKpi->name) !!}
                                                        </td>
                                                        <td>
                                                            <textarea name="target[]" class="form-control" cols="30" rows="10" required>{{$dptGoals->target}}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea name="actual[]" class="form-control" cols="30" rows="10" required>{{$dptGoals->actual}}</textarea>
                                                        </td>
                                                        {{-- <td>
                                                            <input type="number" name="grade[]" class="form-control input-sm" maxlength="3" value="{{$dptGoals->grade}}" disabled required>
                                                        </td> --}}
                                                        <td>
                                                            <textarea name="remarks[]" class="form-control input-sm" cols="30" rows="10" required>{{$dptGoals->remarks}}</textarea>
                                                        </td>
                                                        <td>
                                                            <small class="form-text text-muted">
                                                                File to upload: {{ $dptGoals->departmentKpi->attachment_description }}
                                                            </small>
                                                            <input type="file" name="file[{{$key}}][]" class="form-control input-md" multiple>    
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" type="button" onclick="saveDraft()">Save Draft</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function saveDraft() {
    document.getElementById('save_type').value = 'draft';

    document.querySelectorAll('#mdrFormEdit [required]').forEach(el => {
        el.removeAttribute('required');
    });

    document.getElementById('mdrFormEdit').submit();
}
</script>