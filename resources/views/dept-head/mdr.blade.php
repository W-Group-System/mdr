@extends('layouts.app')
@section('css')
<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
<style>
    .period {
        margin-left: 5px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <h1 class="text-center">{{ date('F Y', strtotime($yearAndMonth)) }}</h1>
    @include('components.error')
    
    @include('dept-head.departmental-goals', array('departmentalGoals' => $departmentalGoals, 'yearAndMonth' => $yearAndMonth))
    
    @if($mdr_groups->where('name', 'Innovation')->where('status','Active')->isNotEmpty())
        @include('dept-head.innovation', array('innovations' => $innovations))
    @endif

    <div class="col-lg-12">
        <div class="ibox float-e-margins" >
            <div class="ibox-content">
                <form action="{{ url('submitDraftMdr') }}" method="post" onsubmit="show()">
                    @csrf

                    <input type="hidden" name="year_and_month" value="{{ $yearAndMonth }}">
                    
                    <button type="submit" class="btn btn-block btn-success">Draft MDR</button>
                </form>
                <form id="submitMdrForm" action="{{ url('submitMdr') }}" method="post">
                    @csrf

                    <input type="hidden" name="year_and_month" value="{{ $yearAndMonth }}">
                    
                    <button type="submit" class="btn btn-block btn-primary">Submit MDR</button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('components.footer')

@endsection

@push('scripts')
<script src="{{ asset('js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script src="js/plugins/chosen/chosen.jquery.js"></script>
<script src="js/plugins/dataTables/datatables.min.js"></script>
<script>
function updateOperationalScore() {
    const totalWeightedScore = parseFloat($('#sumTotalWeightedScore').text().trim()) || 0;
    $('#operationalTotalScore').text(totalWeightedScore.toFixed(2));
    
    // Trigger total calculation whenever operational updates
    calculateMdrTotal();
}

function calculateMdrTotal() {
    let operational = parseFloat($('#operationalTotalScore').text()) || 0;
    
    // Assuming you can give your timeliness and innovation cells or spans specific IDs or classes
    let timeliness = parseFloat($('#timelinessScore').text()) || 0;
    let innovation = parseFloat($('#innovationScore').text()) || 0;

    let totalGrade = operational + timeliness + innovation;

    // Output to Total Grade span/display
    $('#totalGradeScore').text(totalGrade.toFixed(2));
}

$(document).ready(function() {
    updateOperationalScore();
    calculateMdrTotal();

    $('#processDevelopmentTable').DataTable({
        pageLength: 10,
        ordering: false,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
    });

    $('#departmentalGoals').DataTable({
        pageLength: 10,
        ordering: false,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
    });

    $('#innovationTable').DataTable({
        pageLength: 10,
        ordering: false,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
    });

    $("[name='grade[]']").keypress(function(event) {
        if (event.keyCode == 8) {
            return;
        }

        if (event.keyCode < 48 || event.keyCode > 57) {
            event.preventDefault(); 
        }   
    });

    $('#submitMdrForm').on('submit', function (e) {
        e.preventDefault();
        let missing = false;

        $('#departmentalGoals tbody tr').each(function () {
            const target = $(this).find('td:nth-child(2)').text().trim();
            const actual = $(this).find('td:nth-child(3)').text().trim();
            const remarks = $(this).find('td:nth-child(5)').text().trim();
            const hasAttachment = $(this).find('a[href]').length > 0;

            if (!target || !actual || !remarks || !hasAttachment) {
                missing = true;
                return false; 
            }
        });

        if (missing) {
            swal(
                "Incomplete KPI Data",
                "Please ensure all KPIs have Target, Actual, Remarks, and at least one Attachment before submitting.",
                "error"
            );
            return false;
        }

        const form = this;

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
</script>

@endpush
