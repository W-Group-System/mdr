@extends('layouts.app')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Companies</h5>
                    <div class="pull-right">
                        <span class="label label-success">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($company)}}</h1>
                    <small>Total Companies</small>
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
                    <h1 class="no-margins">{{count($company->where('status', "Active"))}}</h1>
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
                    <h1 class="no-margins">{{count($company->where('status', "Inactive"))}}</h1>
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
                </div>
                
                <div class="ibox-content">
                    <div class="table-responsive">
                        @include('components.error')

                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company as $c)
                                    <tr>
                                        <td>
                                            @if($c->status == "Active")
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $c->id }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </button>
                                            
                                            <form action="{{url('deactivate_company/'.$c->id)}}" method="post" onsubmit="show()" style="display: inline-block;">
                                                @csrf

                                                <input type="hidden" name="status" value="0">

                                                <button class="btn btn-sm btn-danger" type="submit">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{url('activate_company/'.$c->id)}}" method="post" role="form" onsubmit="show()" style="display: inline-block;">
                                                @csrf

                                                <input type="hidden" name="status" value="1">

                                                <button class="btn btn-sm btn-success" type="submit">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </td>
                                        <td>{{$c->code}}</td>
                                        <td>{{$c->name}}</td>
                                        <td>
                                            @if($c->status == "Active")
                                            <span class="label label-primary">
                                            @elseif($c->status == "Inactive")
                                            <span class="label label-danger">
                                            @endif
                                            {{$c->status}}
                                            </span>
                                            {{-- <div class="label label-{{$c->status == 0 ? 'danger' : 'primary'}}">{{$c->status == 0 ? 'Inactive' : 'Active'}}</div> --}}
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

@include('admin.new_companies')
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