@extends('layouts.app')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Companies</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($company)}}</h1>
                    <small>Total Companies</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($company->where('status', 1))}}</h1>
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
                    <h1 class="no-margins">{{count($company->where('status', 0))}}</h1>
                    <small>Total Inactive</small>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">
                        <span><i class="fa fa-plus"></i></span>&nbsp;
                        Add Company
                    </button>

                    <div class="modal" id="addModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title text-left">Add Company</h1>
                                </div>
                                <form action="{{url('add_company')}}" method="POST" onsubmit="show()">
                                    @csrf

                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Code</label>
                                                    <input type="text" name="code" class="form-control input-sm" placeholder="Enter code" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control input-sm" placeholder="Enter name" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="submit">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="ibox-content">
                    <div class="table-responsive">
                        @include('components.error')

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company as $c)
                                    <tr>
                                        <td>{{$c->code}}</td>
                                        <td>{{$c->name}}</td>
                                        <td>
                                            <div class="label label-{{$c->status == 0 ? 'danger' : 'primary'}}">{{$c->status == 0 ? 'Inactive' : 'Active'}}</div>
                                        </td>
                                        <td>
                                            @if($c->status == 1)
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $c->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            
                                            <form action="{{url('deactivate_company/'.$c->id)}}" method="post" onsubmit="show()">
                                                @csrf

                                                <input type="hidden" name="status" value="0">

                                                <button class="btn btn-sm btn-danger" type="submit">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{url('activate_company/'.$c->id)}}" method="post" role="form" onsubmit="show()">
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

@foreach ($company as $c)
<div class="modal" id="editModal-{{$c->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title text-left">Add Company</h1>
            </div>
            <form action="{{url('update_company/'.$c->id)}}" method="POST" onsubmit="show()">
                @csrf

                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control input-sm" placeholder="Enter code" value="{{$c->code}}" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control input-sm" placeholder="Enter name" value="{{$c->name}}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script>
    $(document).ready(function() {
        $('.table').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });
    })
</script>
@endpush