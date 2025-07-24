@extends('layouts.app')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>MDR Group</h5>
                    <div class="pull-right">
                        <span class="label label-success">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1>{{count($departmentGroupList->where('status',"Active"))}}</h1>
                    <small>Total Active</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                    <div class="pull-right">
                        <span class="label label-primary">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1>{{count($departmentGroupList->where('status',"Active"))}}</h1>
                    <small>Total Active</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Deactivate</h5>
                    <div class="pull-right">
                        <span class="label label-danger">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1>{{count($departmentGroupList->where('status',"Deactive"))}}</h1>
                    <small>Total Deactivate</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    @if(check_access('MDR Group','create'))
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Department Group
                    </button>
                    @endif

                </div>
                <div class="ibox-content">
                    @include('components.error')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentGroupTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentGroupList as $departmentGroupData)
                                    <tr>
                                        <td>
                                            @if($departmentGroupData->status != "Inactive")
                                                @if(check_access('MDR Group','update'))
                                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $departmentGroupData->id }}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                                @endif
                                            
                                                @if(check_access('MDR Group', 'delete'))
                                                <form action="{{url('deactivate_mdr_group/'.$departmentGroupData->id)}}" method="post" style="display: inline-block;" onsubmit="show()">
                                                    @csrf

                                                    <button class="btn btn-sm btn-danger" type="submit" title="Deactivate">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            @else
                                                @if(check_access('MDR Group','delete'))
                                                <form action="{{url('activate_mdr_group/'.$departmentGroupData->id)}}" method="post" style="display: inline-block;" onsubmit="show()">
                                                    @csrf

                                                    <button class="btn btn-sm btn-success" type="submit" title="Activate">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $departmentGroupData->name }}</td>
                                        <td>
                                            @if($departmentGroupData->status == "Active")
                                                <div class="label label-primary">Active</div>
                                            @else
                                                <div class="label label-danger">Inactive</div>
                                            @endif
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
                <h5 class="modal-title">Add MDR Group</h5>
            </div>
            <form role="form" method="post" action="{{url('addDepartmentGroups')}}" onsubmit="show()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @csrf
                            <div class="form-group">
                                MDR Groups :
                                <input type="text" name="departmentGroupName" class="form-control input-sm" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($departmentGroupList as $departmentGroupData)
<div class="modal" id="editModal-{{ $departmentGroupData->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit MDR Group</h5>
            </div>
            <form method="post" action="{{url('updateDepartmentGroups/'.$departmentGroupData->id)}}" onsubmit="show()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            @csrf
                            <div class="form-group">
                                MDR Group :
                                <input type="text" name="departmentGroupName" placeholder="Enter mdr groups" class="form-control input-sm" value="{{ $departmentGroupData->name }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
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