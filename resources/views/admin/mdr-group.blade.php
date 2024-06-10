@extends('layouts.app')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Department Group
                    </button>

                </div>
                <div class="ibox-content">
                    @if (Session::has('errors'))
                        <div class="alert alert-danger">
                            @foreach (Session::get('errors') as $errors)
                                {{ $errors }}<br>
                            @endforeach
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentGroupTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentGroupList as $departmentGroupData)
                                    <tr>
                                        <td>{{ $departmentGroupData->name }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $departmentGroupData->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>

                                            <form action="/deleteDepartmentGroups/{{ $departmentGroupData->id }}" method="post" role="form" onsubmit="show()">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $departmentGroupData->id }}">

                                                <button class="btn btn-sm btn-danger" id="deleteBtn" type="submit">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
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

<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Add MDR Group</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form role="form" method="post" action="{{url('addDepartmentGroups')}}" onsubmit="show()">
                            @csrf

                            <div class="form-group">
                                <label>MDR Groups</label>
                                <input type="text" name="departmentGroupName" placeholder="Enter mdr groups" class="form-control input-sm" required>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-primary btn-rounded btn-block">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($departmentGroupList as $departmentGroupData)
<div class="modal" id="editModal-{{ $departmentGroupData->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Edit MDR Group</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form method="post" action="{{url('updateDepartmentGroups/'.$departmentGroupData->id)}}" onsubmit="show()">
                            @csrf
                            <div class="form-group">
                                <label>Department KPI Groups</label>
                                <input type="text" name="departmentGroupName" placeholder="Enter department kpi groups" class="form-control" value="{{ $departmentGroupData->name }}">
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
@include('components.footer')
@endsection

@push('scripts')
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>
<!-- Sweet alert -->
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>

{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {
        $('#departmentGroupTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
        
        // $("[name='departmentHead']").chosen({width: "100%"});
    })
</script>
@endpush