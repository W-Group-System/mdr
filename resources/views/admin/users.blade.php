@extends('layouts.app')

@section('css')
    {{-- switch --}}
    <link href="css/plugins/switchery/switchery.css" rel="stylesheet">

    {{-- chosen --}}
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">

    <!-- Sweet Alert -->
    <link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
@endsection

@section('content')

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-left">Add Users</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form role="form" method="post" id="addForm" action="{{ route('addUserAccounts') }}">
                            @csrf
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department" id="department" class="form-control">
                                    <option value="">-Department-</option>
                                    @foreach ($department as $deptData)
                                        <option value="{{ $deptData->id }}">{{ $deptData->dept_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" placeholder="Enter name" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Enter email" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="Enter password" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="Enter password" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label>Account Role</label>
                                <select name="accountRole" id="accountRole" class="form-control">
                                    <option value="">-Account Role-</option>
                                    <option value="0">Admin</option>
                                    <option value="1">Approver</option>
                                    <option value="2">Department Head</option>
                                    <option value="3">Users</option>
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
                    <div class="table-responsive">
                        @if (Session::has('errors'))
                            <div class="alert alert-danger">
                                @foreach (Session::get('errors') as $errors)
                                    {{ $errors }}<br>
                                @endforeach
                            </div>
                        @endif

                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add Users</button>

                        <table class="table table-striped table-bordered table-hover" id="userTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Account Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userList as $userData)
                                    <tr>
                                        <td>{{ isset($userData->dept_name->dept_name) ? $userData->dept_name->dept_name : '' }}</td>
                                        <td>{{ $userData->name }}</td>
                                        <td>{{ $userData->email }}</td>
                                        @switch($userData->account_role )
                                            @case(0)
                                                <td>Admin</td>
                                                @break
                                            @case(1)
                                                <td>Approver</td>
                                                @break
                                            @case(2)
                                                <td>Department Head</td>
                                                @break
                                            @case(3)
                                                <td>Users</td>
                                                @break
                                            @default
                                                
                                        @endswitch
                                        <td>
                                            <input type="hidden" name="id" id="id" value="{{ $userData->id }}">
                                            <input type="checkbox" class="js-switch" name="account_status" {{ $userData->account_status == 1 ? 'checked' : '' }}/>
                                        </td>
                                        <td>
                                            <div class="btn btn-group-sm">
                                                <button class="btn btn-warning" data-toggle="modal" data-target="#editModal-{{ $userData->id }}">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                                {{-- @if($userData->id !== Auth::user()->id)
                                                    <button class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif --}}
                                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#changePasswordModal-{{ $userData->id }}">
                                                    <i class="fa fa-key"></i>
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

@foreach ($userList as $userData)
    <div class="modal fade" id="editModal-{{ $userData->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-left">Edit Users</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form role="form" method="post" action="/updateUserAccounts/{{ $userData->id }}">
                                @csrf
                                <div class="form-group">
                                    <label>Department</label>
                                    <select name="department" id="department" class="form-control">
                                        <option value="">-Department-</option>
                                        @foreach ($department as $deptData)
                                            <option value="{{ $deptData->id }}" {{ $userData->department_id == $deptData->id ? 'selected' : '' }}>{{ $deptData->dept_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" placeholder="Enter name" class="form-control input-sm" value="{{ $userData->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" placeholder="Enter email" class="form-control input-sm" value="{{ $userData->email }}">
                                </div>
                                <div class="form-group">
                                    <label>Account Role</label>
                                    <select name="accountRole" id="accountRole" class="form-control">
                                        <option value="">-Account Role-</option>
                                        <option value="0" {{ $userData->account_role == 0 ? 'selected' : '' }}>Admin</option>
                                        <option value="1" {{ $userData->account_role == 1 ? 'selected' : '' }}>Approver</option>
                                        <option value="2" {{ $userData->account_role == 2 ? 'selected' : '' }}>Department Head</option>
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

    <div class="modal fade" id="changePasswordModal-{{ $userData->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-left">Change Passwords</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form role="form" method="post" action="/changePassword/{{ $userData->id }}">
                                @csrf
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" placeholder="Enter password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" placeholder="Enter password" class="form-control">
                                </div>
                                <div>
                                    <button class="btn btn-primary btn-rounded btn-block">Change</button>
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

{{-- Switch --}}
<script src="js/plugins/switchery/switchery.js"></script>

{{-- chosen --}}
<script src="js/plugins/chosen/chosen.jquery.js"></script>

<!-- Sweet alert -->
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        var userTable = $('#userTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        var elems = document.querySelectorAll('.js-switch');
        elems.forEach(function(elem) {
            new Switchery(elem, { color: '#1AB394' });
        })

        $(".switchery").on('click', function() {
            var id = $(this).siblings().eq(0).val()
            
            $.ajax({
                type: "POST",
                url: "{{ route('changeAccountStatus') }}",
                data: { 
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    swal({
                        title: res.status > 0 ? 'SUCCESS' : 'ERROR',
                        type: 'success',
                        text: res.status > 0 ? 'The user is activate.' : 'The user is deactivated.',
                    })
                }
            })
        });

        $("[name='department']").chosen({width: "100%"});

        $("#accountRole").chosen({width: "100%"});
    })
</script>
@endpush