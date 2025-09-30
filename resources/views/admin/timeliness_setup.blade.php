@extends('layouts.app')

@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <style>
        .chosen-container {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('content')

<div class="wrapper wrapper-content">
    <div class="row">
        
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#newTimeliness">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Setup Timeliness
                    </button>
                </div>

                <div class="ibox-content">
                    @include('components.error')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="timelinessSetupTable">
                            <thead>
                                <tr>
                                    <th>Score</th>
                                    <th>Effective Date</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($timeliness_setup_list as $setup)
                                    <tr>
                                        <td>{{ $setup->score }}</td>
                                        <td>{{ $setup->effective_date }}</td>
                                        <td>{{ $setup->created_at }}</td>
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

@include('admin.new_timeliness')

@include('components.footer')

@endsection

@push('scripts')
<script src="js/plugins/dataTables/datatables.min.js"></script>
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {
        $(".cat").chosen({width: "100%"});

        var userTable = $('#timelinessSetupTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
        
    })
</script>
@endpush