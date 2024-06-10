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
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Users</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($userList)}}</h1>
                    <small>Total Users</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($userList->where('status', 1))}}</h1>
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
                    <h1 class="no-margins">{{count($userList->where('status', 0))}}</h1>
                    <small>Total Inactive</small>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Users
                    </button>

                    <div class="modal" id="addModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title text-left">Add Users</h1>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <form role="form" method="post" id="addForm" action="{{ url('addUserAccounts') }}" onsubmit="show()">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="name" placeholder="Enter name" class="form-control input-sm" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email" placeholder="Enter email" class="form-control input-sm" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Company</label>
                                                    <select name="company" id="company" class="form-control cat">
                                                        <option value="">-Company-</option>
                                                        @foreach ($company as $c)
                                                            <option value="{{$c->id}}">{{ $c->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select name="department" id="department" class="form-control cat">
                                                        <option value="">-Department-</option>
                                                        @foreach ($department as $deptData)
                                                            <option value="{{ $deptData->id }}">{{ $deptData->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Account Role</label>
                                                    <select name="role" id="role" class="form-control cat">
                                                        <option value="">-Role-</option>
                                                        <option value="Administrator">Administrator</option>
                                                        <option value="Approver">Approver</option>
                                                        <option value="Department Head">Department Head</option>
                                                        <option value="Users">Users</option>
                                                        <option value="Human Resources">Human Resources</option>
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
                    <div class="table-responsive">
                        @if (Session::has('errors'))
                            <div class="alert alert-danger">
                                @foreach (Session::get('errors') as $errors)
                                    {{ $errors }}<br>
                                @endforeach
                            </div>
                        @endif

                        <table class="table table-striped table-bordered table-hover" id="userTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Companies</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Account Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userList as $userData)
                                    <tr>
                                        <td>{{ $userData->name }}</td>
                                        <td>{{ $userData->email }}</td>
                                        <td>{{isset($userData->company->name) ? $userData->company->name : ''}}</td>
                                        <td>{{ isset($userData->name->name) ? $userData->name->name : '' }}</td>
                                        <td>{{$userData->role}}</td>
                                        <td>
                                            <input type="hidden" name="id" id="id" value="{{ $userData->id }}">
                                            <input type="checkbox" class="js-switch" name="account_status" {{ $userData->status == 1 ? 'checked' : '' }}/>
                                        </td>
                                        <td>
                                            <div class="btn btn-group-sm">
                                                <button class="btn btn-warning" data-toggle="modal" data-target="#editModal-{{ $userData->id }}">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
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
    <div class="modal" id="editModal-{{ $userData->id }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-left">Edit Users</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form role="form" method="post" action="{{url('updateUserAccounts/'.$userData->id)}}" onsubmit="show()">
                                @csrf
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="name" placeholder="Enter name" class="form-control input-sm" value="{{ $userData->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" placeholder="Enter email" class="form-control input-sm" value="{{ $userData->email }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Company</label>
                                    <select name="company" id="company" class="form-control cat">
                                        <option value="">-Company-</option>
                                        @foreach ($company as $c)
                                            <option value="{{$c->id}}" {{$userData->company_id == $c->id ? 'selected' : ''}}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Department</label>
                                    <select name="department" id="department" class="form-control cat">
                                        <option value="">-Department-</option>
                                        @foreach ($department as $deptData)
                                            <option value="{{ $deptData->id }}" {{ $userData->department_id == $deptData->id ? 'selected' : '' }}>{{ $deptData->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Account Role</label>
                                    <select name="role" id="role" class="form-control cat" required>
                                        <option value="">-Account Role-</option>
                                        <option value="Administrator" {{ $userData->role == "Administrator" ? 'selected' : '' }}>Admin</option>
                                        <option value="Approver" {{ $userData->role == "Approver" ? 'selected' : '' }}>Approver</option>
                                        <option value="Department Head" {{ $userData->role == "Department Head" ? 'selected' : '' }}>Department Head</option>
                                        <option value="Users" {{ $userData->role == "Users" ? 'selected' : '' }}>User</option>
                                        <option value="Human Resources" {{ $userData->role == "Human Resources" ? 'selected' : '' }}>Human Resources</option>
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

    <div class="modal" id="changePasswordModal-{{ $userData->id }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title text-left">Change Passwords</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form role="form" method="post" action="/changePassword/{{ $userData->id }}" onsubmit="show()">
                                @csrf
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" placeholder="Enter password" class="form-control input-sm" required>
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" placeholder="Enter password" class="form-control input-sm" required>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary btn-rounded btn-block">Change</button>
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
<script src="js/plugins/switchery/switchery.js"></script>
<script src="js/plugins/chosen/chosen.jquery.js"></script>
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
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
                        title: "SUCCESS",
                        type: 'success',
                        text: res.status > 0 ? 'The user is activate.' : 'The user is deactivated.',
                    })
                }
            })
        });
        
        var userTable = $('#userTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        $(".cat").chosen({width: "100%"});
    })
</script>
@endpush