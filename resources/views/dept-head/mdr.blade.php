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
$(document).ready(function() {
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
            return
        }

        if (event.keyCode < 48 || event.keyCode > 57) {
            event.preventDefault(); 
        } 
    });

    $('#submitMdrForm').on('submit', function (e) {
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
            e.preventDefault();
            swal(
                "Incomplete KPI Data",
                "Please ensure all KPIs have Target, Actual, Remarks, and at least one Attachment before submitting.",
                "error"
            );
            return false;
        }

        return true;
    });


    //  $('#submitMdrForm').on('submit', function (e) {
    //     let missing = false;
    //     let message = '';

       
    //     let kpiInputs = $('#newKpi').find('[name^="target["], [name^="actual["], [name^="remarks["], [name^="file["]');

    //     if (kpiInputs.length === 0) {
    //         e.preventDefault();
    //         swal("Missing KPI", "Please add at least one KPI before submitting the MDR.", "error");
    //         return false;
    //     }

    //     $('#newKpi').find('textarea[required], input[type="file"][required]').each(function () {
    //         if (!$(this).val() || ($(this).attr('type') === 'file' && this.files.length === 0)) {
    //             missing = true;
    //         }
    //     });

    //     if (missing) {
    //         e.preventDefault();
    //         swal("Incomplete KPI", "Please complete all required KPI fields (Target, Actual, Remarks, and Attachments).", "error");
    //         return false;
    //     }

    //     return true;
    // });


})

</script>

@endpush
