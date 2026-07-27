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
                                        <table class="table table-hover table-striped table-bordered" id="editKpiTable">
                                            <thead>
                                                <tr>
                                                    <th>KPI</th>
                                                    <th>Target (%)</th>
                                                    <th>Actual (%)</th>
                                                    <th>Weight</th>
                                                    <th>Weighted Score</th>
                                                    <th>Remarks</th>
                                                    <th>Attachments</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($departmentalGoals as $key=>$dptGoals)
                                                    <input type="hidden" name="department_goals_id[]" value="{{$dptGoals->id}}">
                                                    @if (isset($mdr))
                                                        <input type="hidden" name="mdr_id[]" value="{{$mdr->id}}">
                                                    @endif
                                                    
                                                    <tr>
                                                        <td>
                                                            {!! nl2br($dptGoals->departmentKpi->name) !!}
                                                        </td>
                                                        <td>
                                                            <textarea name="target[]" class="form-control numerical target" cols="30" rows="10" required>{{$dptGoals->target}}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea name="actual[]" class="form-control numerical actual" cols="30" rows="10" required>{{$dptGoals->actual}}</textarea>
                                                        </td>
                                                         <td class="weight">
                                                            {!! nl2br($dptGoals->departmentKpi->weight) !!}
                                                        </td>
                                                        <td class="weighted-score">
                                                            0
                                                        </td>
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
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td>Total Score: <input type="hidden" name="final_grade" id="editTotalWeightedScoreHidden" value=""> </td>
                                                    <td><h2><span id="EditTotalWeightedScore">0.00</span></h2></td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tfoot>
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
document.addEventListener('DOMContentLoaded', function() {
    $('#editKpi').on('shown.bs.modal', function () {
        $('#editKpiTable tbody tr').each(function () {
            computeRow($(this));
        });
        calculateTotalWeightedScore();

    });
    
    $('.numerical').on('keypress', function (e) {
        // Allow numbers (0-9)
        if (e.which >= 48 && e.which <= 57) {
            return true;
        }
        // Allow decimal point (.)
        if (e.which === 46) {
            return true;
        }
        // Allow Enter
        if (e.which === 13) {
            return true;
        }
        e.preventDefault();
    });

    $(this).on('input', '.actual', function () {
        computeRow($(this).closest('tr'));
        calculateTotalWeightedScore();
    });

    function calculateTotalWeightedScore() {
        var total = 0;
        $('.weighted-score').each(function () {
            total += parseFloat($(this).text()) || 0;
        });
        console.log(total);
        
        $('#editTotalWeightedScoreHidden').val(total.toFixed(2));
        $('#EditTotalWeightedScore').text(total.toFixed(2));
    }

    function computeRow(row) {
        var target = parseFloat(row.find('.target').val()) || 0;
        var actual = parseFloat(row.find('.actual').val()) || 0;
        var weight = parseFloat(row.find('.weight').text()) || 0;

        var weightedScore = 0;

        if (target > 0) {
            weightedScore = (actual / target) * weight;
        }

        row.find('.weighted-score').text(weightedScore.toFixed(2));
    }
});
</script>