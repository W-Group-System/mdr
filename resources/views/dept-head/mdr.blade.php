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
    @foreach ($mdrSetup as $departmentKpiData)
        @include('dept-head.departmental-goals', array('mdrSetup' => $departmentKpiData, 'yearAndMonth' => $yearAndMonth))
        @include('dept-head.process-development', array('mdrSetup' => $mdrSetup))
        {{-- @include('dept-head.innovation', array('mdrSetup' => $mdrSetup)) --}}
    @endforeach

    @if(auth()->user()->role == "Department Head" || auth()->user()->role == "Users")
        <div class="col-lg-12">
            <div class="ibox float-e-margins" style="margin-top: 10px;">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="processDevelopmentTable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>MDR Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{auth()->user()->name}}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#mdrStatusModal">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </td>
                                    <td>
                                        @if(auth()->user()->role == "Department Head")
                                        <form action="{{ url('approveMdr') }}" method="post" onsubmit="show()">
                                            @csrf

                                            <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                                            
                                            <button class="btn btn-sm btn-primary" type="submit">Approve</button>
                                        </form>
                                        @else
                                        <form action="{{ url('submitMdr') }}" method="post" onsubmit="show()">
                                            @csrf

                                            <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                                            
                                            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="mdrStatusModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">MDR Status</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                    </div>
                                    <div class="panel-body">
                                        <div class='row text-center'>
                                            <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                            <strong>Approver</strong>
                                            </div>
                                            <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                            <strong>Status</strong>
                                            </div>
                                            <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                            <strong>Start Date</strong>
                                            </div>
                                        </div>
                                        @if(!empty($approver->mdrStatus))
                                          @foreach ($approver->mdrStatus as $status)
                                            <div class="row text-center">
                                              <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                              {{$status->users->name}}
                                              </div>
                                              <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                              {{!empty($status->status_desc)?$status->status_desc:'WAITING'}}
                                              </div>
                                              <div class='col-md-4 border border-primary border-top-bottom border-left-right'>
                                              {{!empty($status->start_date)?$status->start_date:'No Date'}}
                                              </div>
                                            </div>
                                          @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
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

    $('#innovationTable').DataTable({
        pageLength: 10,
        ordering: false,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
    });

    $(".uploadKpiAttachmentForm").on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData:false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                var appendHtml = ``;

                $.each(res.filePath, function(key, path) {
                    appendHtml += `
                        <div class="attachment-kpi-${key}">
                            <a href="${path}" target="_blank" class="btn btn-sm btn-info">
                                <i class="fa fa-eye"></i>
                            </a>
    
                            <button type="button" class="btn btn-sm btn-danger" name="deleteKpiAttachments" data-id="${key}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `
                })

                $(".kpi-attachment-container-"+res.id).append(appendHtml)

                swal("SUCCESS", "Successfully Added.", "success");

                $("#uploadModal-"+res.id).modal('hide');
            }
        })
    })

    $(document).on('click', "[name='deleteKpiAttachments']" ,function() {
        var id = $(this).data('id');

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover your file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            
            $.ajax({
                type: "POST",
                url: "{{ url('deleteKpiAttachments') }}",
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    swal("Deleted!", "Successfully Deleted.", "success");

                    $('.attachment-kpi-'+id).remove();
                }
            })
        });
    })

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
