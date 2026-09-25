<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-title">
            @if(count($departmentalGoals->where('year', date('Y', strtotime($yearAndMonth)))->where('month', date('m', strtotime($yearAndMonth)))) > 0)
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editKpi" style="margin-top: 3px;">
                <i class="fa fa-pencil"></i>
                View KPI
            </button>
            @else
            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#newKpi" @if($departmentalGoals->isNotEmpty()) disabled @endif>
                <i class="fa fa-plus"></i>
                View KPI
            </button>
            @endif
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="departmentalGoals">
                    <thead>
                        <tr>
                            {{-- <th>Actions</th> --}}
                            <th>Key Performance Indicator</th>
                            <th>Target</th>
                            <th>Actual</th>
                            <th>Weight</th>
                            <th>Weighted Score</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                            <th>Attachments</th>
                        </tr>
                    </thead>
                   <tbody>
                        @foreach ($departmentalGoals as $dptGoals)
                            <tr>
                                <td>{!! nl2br($dptGoals->departmentKpi->name) !!}</td>

                                <td>{!! nl2br($dptGoals->target) !!}</td>

                                <td>{{ $dptGoals->actual }}</td>

                                <td class="weightDets">
                                    <input type="hidden"
                                        name="weightInputs[]"
                                        value="{{ $dptGoals->departmentKpi->weight }}">

                                    <span class="deptWeightDets">
                                        {!! nl2br($dptGoals->departmentKpi->weight) !!}
                                    </span>
                                </td>

                                <td class="weightedScoreDets">

                                    <input type="hidden"
                                        class="weightedScoreDetsHidden"
                                        name="grade[]"
                                        value="{{ $dptGoals->grade }}">

                                    <span class="deptWeightedScoreDets">
                                        {{ $dptGoals->grade }}
                                    </span>

                                </td>

                                <td>
                                    {{ $dptGoals->grade }}
                                </td>
                                <td style="white-space: normal; word-break: break-word; overflow-wrap: anywhere;">
                                    {!! nl2br(e($dptGoals->remarks)) !!}
                                </td>

                                <td>
                                    @foreach ($dptGoals->attachments as $key => $attachment)
                                        <span>{{ $key + 1 }}. </span>

                                        <a href="{{ url($attachment->file_path) }}" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>

                                        <form action="{{ url('deleteAttachment/' . $attachment->id) }}"
                                            method="POST"
                                            class="deleteAttachmentForm m-0 p-0"
                                            style="display: contents;">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                    class="btn btn-danger btn-sm deleteAttachmentBtn p-1 d-flex align-items-center justify-content-center">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </form>

                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3"></td>
                            <td style="white-space: nowrap;"><b>Total Weight</b></td>
                            <td style="white-space: nowrap;"><b>Total Weighted Score</b></td>
                            <td colspan="3"></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input type="hidden" name="sumOfScore" id="sumOfScoreHidden" value="0">
                            </td>
                            <td>
                                <h2><span id="sumTotalWeight">0.00</span></h2>
                            </td>
                            <td>
                                <h2><span id="sumTotalWeightedScore">0.00</span></h2>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                  
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .swal2-container {
        z-index: 999999 !important;
    }
</style>



<!-- <script>
    function deactivateMdr(e)
    {
        Swal.fire({
            title: "Are you sure?",
            text: "This file will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $(e).closest("tr").remove()
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        
    function calculateTotals() {

        let totalWeight = 0;
        let totalWeightedScore = 0;

        // TOTAL WEIGHT
        $('input[name="weightInputs[]"]').each(function () {

            let value = $(this).val();

            // Remove commas and other non-numeric characters
            value = String(value).replace(/,/g, '');

            let weight = parseFloat(value);

            if (!isNaN(weight)) {
                totalWeight += weight;
            }
        });

        // TOTAL WEIGHTED SCORE
        $('input[name="grade[]"]').each(function () {

            let value = $(this).val();

            // Remove commas and other non-numeric characters
            value = String(value).replace(/,/g, '');

            let grade = parseFloat(value);

            if (!isNaN(grade)) {
                totalWeightedScore += grade;
            }
        });

        // Display
        $('#sumTotalWeight').text(totalWeight.toFixed(2));

        $('#sumTotalWeightedScore').text(
            totalWeightedScore.toFixed(2)
        );

        // Hidden input
        $('#sumOfScoreHidden').val(
            totalWeightedScore.toFixed(2)
        );

        console.log('Total Weight:', totalWeight);
        console.log('Total Weighted Score:', totalWeightedScore);
    }

    // Run on page load
    calculateTotals();
        const deleteButtons = document.querySelectorAll('.deleteAttachmentBtn');

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This file will be permanently deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        const saveButtons = document.querySelectorAll('.forfuturereference');

        saveButtons.forEach(function (saveButton) {
            saveButton.addEventListener('click', function (e) {
                e.preventDefault();

                const form = this.closest('form');

                Swal.fire({
                    title: "Are you sure you want to submit this report?",
                    html: `
                        <p>By clicking <strong>Confirm</strong>, you acknowledge that you have reviewed the results. The submitted report is subject to review and final approval and may still be updated during the approval process.</p>

                        <p><strong>Note:</strong> If your report grade is <span style="color:red;">Failed</span>, please verify and validate the results before confirming.</p>
                    `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Confirm"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script> -->

<script>
    function deactivateMdr(e) {
        Swal.fire({
            title: "Are you sure?",
            text: "This file will be permanently deleted.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $(e).closest("tr").remove();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {

        // Calculate total weight and total weighted score
        function calculateTotals() {
            let totalWeight = 0;
            let totalWeightedScore = 0;

            $('input[name="weightInputs[]"]').each(function () {
                totalWeight += parseFloat($(this).val()) || 0;
            });

            $('input[name="grade[]"]').each(function () {
                totalWeightedScore += parseFloat($(this).val()) || 0;
            });

            $('#sumTotalWeight').text(totalWeight.toFixed(2));
            $('#sumTotalWeightedScore').text(totalWeightedScore.toFixed(2));
            $('#sumOfScoreHidden').val(totalWeightedScore.toFixed(2));
        }

        // Calculate totals on page load
        calculateTotals();


        // Delete attachment
        document.querySelectorAll('.deleteAttachmentBtn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                const form = this.closest('form');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This file will be permanently deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });


        // Submit MDR report
        document.querySelectorAll('.forfuturereference').forEach(function (saveButton) {
            saveButton.addEventListener('click', function (e) {
                e.preventDefault();

                const form = this.closest('form');

                Swal.fire({
                    title: "Are you sure you want to submit this report?",
                    html: `
                        <p>
                            By clicking <strong>Confirm</strong>, you acknowledge that you have reviewed the results.
                            The submitted report is subject to review and final approval and may still be updated
                            during the approval process.
                        </p>

                        <p>
                            <strong>Note:</strong>
                            If your report grade is <span style="color:red;">Failed</span>,
                            please verify and validate the results before confirming.
                        </p>
                    `,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Confirm"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

    });
</script>

@include('dept-head.new_kpi')
@include('dept-head.edit_kpi')
