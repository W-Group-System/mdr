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
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Users
                    </h5>
                    <div class="pull-right">
                        <span class="label label-success">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($userList)}}</h1>
                    <small>Total Users</small>
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
                    <h1 class="no-margins">{{count($userList->where('status', "Active"))}}</h1>
                    <small>Total Active</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Inactive</h5>
                    <div class="pull-right">
                        <span class="label label-danger">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($userList->where('status', "Inactive"))}}</h1>
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
                                    <th>Actions</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Companies</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Account Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userList as $userData)
                                <tr>
                                    <td>
                                        <a href="{{ url('user_access_module/'.$userData->id) }}" class="btn btn-success btn-sm" title="User Access Module">
                                            <i class="fa fa-key"></i>
                                        </a>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                            data-target="#editModal{{ $userData->id }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#changePasswordModal{{ $userData->id }}">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                    </td>
                                    <td>{{ $userData->name }}</td>
                                    <td>{{ $userData->email }}</td>
                                    <td>{{isset($userData->company->name) ? $userData->company->name : ''}}</td>
                                    <td>{{ isset($userData->department->name) ? $userData->department->name : '' }}</td>
                                    <td>{{$userData->role}}</td>
                                    <td>
                                        <input type="hidden" name="id" id="id" value="{{ $userData->id }}">
                                        <input type="checkbox" class="js-switch" name="account_status" {{
                                            $userData->status == "Active" ? 'checked' : '' }}/>
                                    </td>
                                </tr>

                                @include('admin.edit_user')
                                @include('admin.change_password')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.new_user')
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
            new Switchery(elem, { color: '#1AB394', size: 'small' })
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
                    }).then(() => {
                        location.reload()
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