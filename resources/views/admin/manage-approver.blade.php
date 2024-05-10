@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        @if (Session::has('errors'))
                            <div class="alert alert-danger">
                                @foreach (Session::get('errors') as $errors)
                                    {{ $errors }}<br>
                                @endforeach
                            </div>
                        @endif

                        <table class="table table-striped table-bordered table-hover" id="approverTable">
                            <thead>
                                <tr>
                                    <th>Approver</th>
                                    <th>No. Approver</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approverList as $approverData)
                                    <tr>
                                        <td>{{ $approverData->name }}</td>
                                        <td>{{ isset($approverData->approver->status_level) ? $approverData->approver->status_level : '' }}</td>
                                        <td>
                                            <div class="btn btn-group-sm">
                                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $approverData->id }}">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </div>
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

@component('components.footer')
@endcomponent

@foreach ($approverList as $approverData)
    <div class="modal fade" id="editModal-{{ $approverData->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Edit No. Approver</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form role="form" method="post" id="updateForm" action="/updateApprover/{{ $approverData->id }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $approverData->id }}">
                                <div class="form-group">
                                    <label>No. Approver</label>
                                    <select data-placeholder="-No Approver-" class="noApprover form-control" name="noApprover">
                                        <option value="">-No Approver-</option>
                                        <option value="1">1st Approver</option>
                                        <option value="2">2nd Approver </option>
                                        <option value="3">3rd Approver </option>
                                        <option value="4">4th Approver </option>
                                        <option value="5">5th Approver </option>
                                    </select>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary btn-rounded btn-block">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection

@push('scripts')
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>

{{-- Switch --}}
<script src="js/plugins/switchery/switchery.js"></script>

{{-- select2 --}}
<script src="js/plugins/select2/select2.full.min.js"></script>

{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {
        var userTable = $('#approverTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $(".noApprover").chosen({width: "100%"});

    })

</script>
@endpush