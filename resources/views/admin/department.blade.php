@extends('layouts.app')

@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-left">Add Department</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form role="form" method="post" id="addForm" action="{{ route('addDepartments') }}">
                            @csrf
                            <div class="form-group">
                                <label>Department Code</label>
                                <input type="text" name="departmentCode" placeholder="Enter department code" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label>Department Name</label>
                                <input type="text" name="departmentName" placeholder="Enter department name" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label>Department Head</label>
                                <select name="departmentHead" id="departmentHead" class="form-control">
                                    <option value="">-Department Head-</option>
                                    @foreach ($departmentHead as $headData)
                                        <option value="{{ $headData->id }}">{{ $headData->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Target Date</label>
                                <select name="targetDate" id="targetDate" name="targetDate" class="form-control input-sm">
                                    <option value="">- Target Date -</option>
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value={{ $i }}>{{ $i }}</option>
                                    @endfor
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

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    @if (Session::has('errors'))
                        <div class="alert alert-danger">
                            @foreach (Session::get('errors') as $errors)
                                {{ $errors }}<br>
                            @endforeach
                        </div>
                    @endif

                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add Department</button>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="departmentTable">
                            <thead>
                                <tr>
                                    <th>Department Code</th>
                                    <th>Department Name</th>
                                    <th>Department Head</th>
                                    <th>Target Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentList as $departmentData)
                                    <tr>
                                        <td>{{ $departmentData->dept_code }}</td>
                                        <td>{{ $departmentData->dept_name }}</td>
                                        <td>{{ isset($departmentData->user->name) ? $departmentData->user->name : '' }}</td>
                                        <td>{{ $departmentData->target_date }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $departmentData->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>

                                            <form action="/deleteDepartments/{{ $departmentData->id }}" method="post" role="form">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $departmentData->id }}">

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

@foreach ($departmentList as $departmentData)
    <div class="modal fade" id="editModal-{{ $departmentData->id }}" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-left">Edit Department</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form role="form" method="post" id="updateForm" action="/updateDepartments/{{ $departmentData->id }}">
                                @csrf
                                <div class="form-group">
                                    <label>Department Code</label>
                                    <input type="text" name="departmentCode" placeholder="Enter department code" class="form-control input-sm" value="{{ $departmentData->dept_code }}">
                                </div>
                                <div class="form-group">
                                    <label>Department Name</label>
                                    <input type="text" name="departmentName" placeholder="Enter department name" class="form-control input-sm" value="{{ $departmentData->dept_name }}">
                                </div>
                                <div class="form-group">
                                    <label>Department Head</label>
                                    <select name="departmentHead" id="departmentHead" class="form-control">
                                        <option value="">-Department Head-</option>
                                        @foreach ($departmentHead as $headData)
                                            <option value="{{ $headData->id }}" {{ $headData->id == $departmentData->dept_head_id ? 'selected' : '' }}>{{ $headData->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Target Date</label>
                                    <select name="targetDate" id="targetDate" name="targetDate" class="form-control input-sm">
                                        <option value="">- Target Date -</option>
                                        @foreach (range(1, 31) as $item)
                                            <option value="{{ sprintf("%02d", $item) }}" {{ $item == $departmentData->target_date ? 'selected' : '' }}>{{ $item }}</option>
                                        @endforeach
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


<div class="footer">
    <div class="pull-right">
        10GB of <strong>250GB</strong> Free.
    </div>
    <div>
        <strong>Copyright</strong> Example Company &copy; 2014-2017
    </div>
</div>

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
        var userTable = $('#departmentTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            stateSave: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: []
        });
        
        $("[name='departmentHead']").chosen({width: "100%"});
        $("[name='targetDate']").chosen({width: "100%"});
    })
</script>
@endpush