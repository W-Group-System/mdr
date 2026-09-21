
<div class="modal" id="newKpi">
    <div class="modal-dialog modal-lg" id="kpiModal">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">New KPI</h5>
            </div>

            <form method="POST"
                  action="{{ url('create') }}"
                  id="mdrForm"
                  onsubmit="show()"
                  enctype="multipart/form-data">

                @csrf

                {{-- Selected year/month for the MDR --}}
                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                {{-- Year/month actually used for the KPI template --}}
                <input type="hidden" name="kpiYearAndMonth" value="{{ $kpiYearAndMonth }}">

                <input type="hidden" name="save_type" id="save_type" value="final">


                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mx-2">
                            {{-- KPI fallback alert --}}
                            @if ($kpiYearAndMonth != $yearAndMonth)
                                <div class="alert alert-warning" style="margin-bottom: 20px;">
                                    <strong>Notice:</strong>
                                    No KPI was found for
                                    {{ date('F Y', strtotime($yearAndMonth)) }}.
                                    Using KPI template from
                                    <strong>{{ date('F Y', strtotime($kpiYearAndMonth)) }}</strong>.
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="panel panel-primary">

                                <div class="panel-heading">
                                    Department KPI

                                 
                                </div>

                                <div class="panel-body">

                                    <div class="table-responsive">

                                        <table class="table table-hover table-striped table-bordered"
                                               id="newKpiTable">

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

                                                @foreach ($department_kpis as $key => $department_kpi)

                                                    @if (isset($mdr))
                                                        <input type="text"
                                                               name="mdr_id[]"
                                                               value="{{ $mdr->id }}">
                                                    @endif

                                                    <tr>

                                                        <td>
                                                            <input type="hidden"
                                                                   name="department_kpi_id[{{ $key }}]"
                                                                   value="{{ $department_kpi->id }}">

                                                            {!! nl2br($department_kpi->name) !!}
                                                        </td>

                                                        <td>
                                                            <textarea
                                                                name="target[{{ $key }}]"
                                                                class="form-control target numerical"
                                                                cols="30"
                                                                rows="10"
                                                                required>{{ $department_kpi->target }}</textarea>
                                                        </td>

                                                        <td>
                                                            <textarea
                                                                name="actual[{{ $key }}]"
                                                                class="form-control actual numerical"
                                                                cols="30"
                                                                rows="10"
                                                                required></textarea>
                                                        </td>

                                                        <td class="weight">

                                                            <input type="hidden"
                                                                   name="weight[{{ $key }}]"
                                                                   value="{{ $department_kpi->weight }}">

                                                            <span class="deptWeight">
                                                                {!! nl2br($department_kpi->weight) !!}
                                                            </span>

                                                        </td>

                                                        <td class="weighted-score">

                                                            <input type="hidden"
                                                                   class="weighted-score-hidden"
                                                                   name="grade[{{ $key }}]"
                                                                   value="0">

                                                            <span class="weighted-score-span">
                                                                0
                                                            </span>

                                                        </td>

                                                        <td>
                                                            <textarea
                                                                name="remarks[{{ $key }}]"
                                                                class="form-control input-sm"
                                                                cols="30"
                                                                rows="10"
                                                                required></textarea>
                                                        </td>

                                                        <td>

                                                            <small class="form-text text-muted">
                                                                File to upload:
                                                                {{ $department_kpi->attachment_description }}
                                                            </small>

                                                            <input type="file"
                                                                   name="file[{{ $key }}][]"
                                                                   class="form-control input-md"
                                                                   multiple
                                                                   required>

                                                        </td>

                                                    </tr>

                                                @endforeach

                                            </tbody>

                                            <tfoot>

                                                <tr>
                                                    <td colspan="3"></td>

                                                    <td>
                                                        <b>Total Weight</b>
                                                    </td>

                                                    <td>
                                                        <b>Total Weighted Score</b>
                                                    </td>

                                                    <td colspan="2"></td>
                                                </tr>

                                                <tr>

                                                    <td colspan="3">
                                                        <input type="hidden"
                                                               name="final_grade"
                                                               id="totalWeightedScoreHidden"
                                                               value="">
                                                    </td>

                                                    <td>
                                                        <h2>
                                                            <span id="totalWeight">
                                                                0.00
                                                            </span>
                                                        </h2>
                                                    </td>

                                                    <td>
                                                        <h2>
                                                            <span id="totalWeightedScore">
                                                                0.00
                                                            </span>
                                                        </h2>
                                                    </td>

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

                    <button class="btn btn-secondary"
                            type="button"
                            data-dismiss="modal">
                        Close
                    </button>

                    <button class="btn btn-success"
                            type="button"
                            onclick="saveNewDraft()">
                        Save Draft
                    </button>

                    <button class="btn btn-primary saveKpi"
                            type="submit">
                        Save
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>


<script>

function saveNewDraft() {

    document.getElementById('save_type').value = 'draft';

    document.querySelectorAll('#mdrForm [required]').forEach(function(el) {
        el.removeAttribute('required');
    });

    document.getElementById('mdrForm').submit();
}


document.addEventListener('DOMContentLoaded', function() {

    $('#newKpi').on('shown.bs.modal', function () {

        $('#newKpiTable tbody tr').each(function () {
            computeRow($(this));
        });

        calculateTotalWeightedScore();

    });


    $('.numerical').on('keypress', function (e) {

        // Allow numbers 0-9
        if (e.which >= 48 && e.which <= 57) {
            return true;
        }

        // Allow decimal point
        if (e.which === 46) {
            return true;
        }

        // Allow Enter
        if (e.which === 13) {
            return true;
        }

        e.preventDefault();
    });


    $(document).on('input', '.actual, .target', function () {

        computeRow($(this).closest('tr'));

        calculateTotalWeightedScore();

    });


    function calculateTotalWeightedScore() {

        var total = 0;
        var weightTotal = 0;

        $('.weighted-score-span').each(function () {

            total += parseFloat($(this).text()) || 0;

        });

        $('.deptWeight').each(function () {

            weightTotal += parseFloat($(this).text()) || 0;

        });

        $('#totalWeightedScoreHidden').val(total.toFixed(2));

        $('#totalWeightedScore').text(total.toFixed(2));

        $('#totalWeight').text(weightTotal.toFixed(2));
    }


    function computeRow(row) {

        var target = parseFloat(row.find('.target').val()) || 0;
        var actual = parseFloat(row.find('.actual').val()) || 0;
        var weight = parseFloat(row.find('.deptWeight').text()) || 0;

        var weightedScore = 0;

        if (actual > target) {
            actual = target;
        }

        if (target > 0) {
            weightedScore = (actual / target) * weight;
        }

        row.find('.weighted-score-span')
            .text(weightedScore.toFixed(2));

        row.find('.weighted-score-hidden')
            .val(weightedScore.toFixed(2));
    }

});

</script>

