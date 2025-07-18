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
                <form action="{{ url('submitMdr') }}" method="post" onsubmit="show()">
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

})

</script>

@endpush
