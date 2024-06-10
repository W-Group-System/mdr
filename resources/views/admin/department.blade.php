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
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Departments</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departmentList)}}</h1>
                    <small>Total Departments</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departmentList->where('status', 1))}}</h1>
                    <small>Total Active</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Inactive</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($departmentList->where('status', 0))}}</h1>
                    <small>Total Inactive</small>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Department
                    </button>

                    <div class="modal" id="addModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title text-left">Add Department</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <form role="form" method="post" id="addForm" action="{{ url('addDepartments') }}" onsubmit="show()">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Department Code</label>
                                                    <input type="text" name="departmentCode" placeholder="Enter department code" class="form-control input-sm" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Department Name</label>
                                                    <input type="text" name="departmentName" placeholder="Enter department name" class="form-control input-sm" required> 
                                                </div>
                                                <div class="form-group">
                                                    <label>Department Head</label>
                                                    <select name="departmentHead" id="departmentHead" class="form-control cat">
                                                        <option value="">-Department Head-</option>
                                                        @foreach ($user->where('role', 'Department Head') as $headData)
                                                            <option value="{{ $headData->id }}">{{ $headData->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Target Date</label>
                                                    <select name="targetDate" id="targetDate" name="targetDate" class="form-control cat">
                                                        <option value="">- Target Date -</option>
                                                        @foreach (range(1, 31) as $item)
                                                            <option value="{{ sprintf("%02d", $item) }}">{{ $item }}</option>
                                                        @endforeach
                                                    </select>
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
                </div>

                <div class="ibox-content">
                    @include('components.error')

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentTable">
                            <thead>
                                <tr>
                                    <th>Department Code</th>
                                    <th>Department Name</th>
                                    <th>Department Head</th>
                                    <th>Target Date</th>
                                    <th>Approvers</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentList as $departmentData)
                                    <tr>
                                        <td>{{ $departmentData->code }}</td>
                                        <td>{{ $departmentData->name }}</td>
                                        <td>{{ isset($departmentData->user->name) ? $departmentData->user->name : '' }}</td>
                                        <td>{{ $departmentData->target_date }}</td>
                                        <td>
                                            @foreach ($departmentData->approver as $approver)
                                                <p>{{ $approver->status_level .'. ' . $approver->user->name }}</p>
                                            @endforeach
                                        </td>
                                        <td>
                                            <div class="label label-{{$departmentData->status == 0 ? 'danger' : 'primary'}}">{{$departmentData->status == 0 ? 'Inactive' : 'Active'}}</div>
                                        </td>
                                        <td>
                                            @if($departmentData->status == 1)
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $departmentData->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            
                                            <form action="{{url('deactivate/'.$departmentData->id)}}" method="post" onsubmit="show()">
                                                @csrf

                                                <input type="hidden" name="status" value="0">

                                                <button class="btn btn-sm btn-danger" type="submit">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{url('activate/'.$departmentData->id)}}" method="post" role="form" onsubmit="show()">
                                                @csrf

                                                <input type="hidden" name="status" value="1">

                                                <button class="btn btn-sm btn-success" type="submit">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
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

@foreach ($departmentList as $departmentData)
    <div class="modal" id="editModal-{{ $departmentData->id }}" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-left">Edit Department</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form role="form" method="post" id="updateForm" action="/updateDepartments/{{ $departmentData->id }}" onsubmit="show()">
                                @csrf
                                <div class="form-group">
                                    <label>Department Code</label>
                                    <input type="text" name="departmentCode" placeholder="Enter department code" class="form-control input-sm" value="{{ $departmentData->code }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Department Name</label>
                                    <input type="text" name="departmentName" placeholder="Enter department name" class="form-control input-sm" value="{{ $departmentData->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Department Head</label>
                                    <select name="departmentHead" id="departmentHead" class="form-control cat">
                                        <option value="">-Department Head-</option>
                                        @foreach ($user->where('role', 'Department Head') as $headData)
                                            <option value="{{ $headData->id }}" {{ $headData->id == $departmentData->user_id ? 'selected' : '' }}>{{ $headData->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Target Date</label>
                                    <select name="targetDate" id="targetDate" name="targetDate" class="form-control cat">
                                        <option value="">- Target Date -</option>
                                        @foreach (range(1, 31) as $item)
                                            <option value="{{ sprintf("%02d", $item) }}" {{ $item == $departmentData->target_date ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary addApprover">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger deleteApprover">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <div class="form-group">
                                    <label for="approver">Approver</label>

                                    <div class="approverFormGroup">
                                        @foreach ($departmentData->approver as $approver)
                                            <select name="approver[]" id="" class="form-control cat approver">
                                                <option value=""></option>
                                                @foreach($user->where('role', 'Approver') as $approverData)
                                                    <option value="{{ $approverData->id }}" {{ $approverData->id == $approver->user_id ? 'selected' : '' }}>{{ $approverData->name }}</option>
                                                @endforeach
                                            </select>
                                        @endforeach
                                    </div>
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
<script src="js/plugins/dataTables/datatables.min.js"></script>
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<script>
    $(document).ready(function() {
        $(".cat").chosen({width: "100%"});

        var userTable = $('#departmentTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
        

        $(".addApprover").on('click', function() {
            
            $(".approverFormGroup").append(`
                <select name="approver[]" id="" class="form-control approver" style="margin-bottom: 10px;" required="">
                    <option value=""></option>
                    @foreach($user->where('role', 'Approver') as $approverData)
                        <option value="{{ $approverData->id }}">{{ $approverData->name }}</option>
                    @endforeach
                </select>
            `)

            $(".approver").chosen({width: "100%"});
        })

        $(".deleteApprover").on('click', function() {
            $(".approverFormGroup").children(":last-child").remove()
        })

    })
</script>
@endpush