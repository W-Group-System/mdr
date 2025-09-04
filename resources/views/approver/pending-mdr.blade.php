@extends('layouts.app')
@section('css')
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>All</h5>
                    <div class="pull-right">
                        <span class="label label-success">as of{{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="GET" action="{{ url('mdr_list') }}">
                        <input type="hidden" name="filter" value="all">
                        <button type="submit" class="btn btn-link p-0">
                            <h1 class="no-margins">{{ count($mdrs) }}</h1>
                        </button>
                    </form>
                    <small>Total</small>
                </div>
            </div>
        </div>
        @php
            $returnedCount = $mdrs->filter(function ($mdr) {
                $approvers = $mdr->mdrApprover;

                $level1Pending = $approvers->where('level', 1)->where('status', 'Pending')->isNotEmpty();
                $hasReturned   = $approvers->where('status', 'Returned')->isNotEmpty();

                return $level1Pending && $hasReturned;
            })->count();

            $approvedCount = $mdrs->filter(function ($mdr) {
                $approvers = $mdr->mdrApprover;

                $lastApprover = $approvers->sortByDesc('level')->first();

                return $lastApprover && $lastApprover->status === 'Approved';
            })->count();

            $pendingCount = $mdrs->filter(function ($mdr) {
                $approvers = $mdr->mdrApprover;

                $lastApprover = $approvers->sortByDesc('level')->first();
                $hasReturned  = $approvers->where('status', 'Returned')->isNotEmpty();

                return ! $hasReturned && $lastApprover && $lastApprover->status !== 'Approved';
            })->count();
        @endphp
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Pending</h5>
                    <div class="pull-right">
                        <span class="label label-warning">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="GET" action="{{ url('mdr_list') }}">
                        <input type="hidden" name="filter" value="pending">
                        <button type="submit" class="btn btn-link p-0">
                            <h1 class="no-margins">{{ $pendingCount }}</h1>
                        </button>
                    </form>
                    <small>Total Pending</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Returned</h5>
                    <div class="pull-right">
                        <span class="label label-danger">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                
                <div class="ibox-content">
                    <form method="GET" action="{{ url('mdr_list') }}">
                        <input type="hidden" name="filter" value="returned">
                        <button type="submit" class="btn btn-link p-0">
                            <h1 class="no-margins">{{ $returnedCount }}</h1>
                        </button>
                    </form>
                    <small>Total Returned</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Approved</h5>
                    <div class="pull-right">
                        <span class="label label-primary">as of {{ date('Y-m-d') }}</span>
                    </div>
                </div>
                <div class="ibox-content">
                    <form method="GET" action="{{ url('mdr_list') }}">
                        <input type="hidden" name="filter" value="approved">
                        <button type="submit" class="btn btn-link p-0">
                            <h1 class="no-margins">{{ $approvedCount }}</h1>
                        </button>
                    </form>
                    <small>Total Approved</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <table class="table table-bordered" id="pendingApprovalTable">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Department</th>
                                <th>PIC</th>
                                <th>Month</th>
                                <th>Deadline</th>
                                <th>Submission Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredMdrs as $mdr)
                                <tr>
                                    @php
                                        $fullTargetDate = getAdjustedTargetDate($mdr->month, $mdr->year, $mdr->departments->target_date);
                                    @endphp
                                    <td width="10">
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#mdrStatusModal{{$mdr->id}}">
                                            <i class="fa fa-history"></i>
                                        </button>
                                        <a href="{{url('list_of_mdr/'.$mdr->id)}}" class="btn btn-warning btn-sm" onclick="show()">
                                            <i class="fa fa-pencil-square-o"></i>                                            
                                        </a>
                                    </td>
                                    <td>{{ $mdr->departments->name }}</td>
                                    <td>
                                        @if ($mdr->departments->user)
                                            {{ $mdr->departments->user->name}}
                                        @endif
                                    </td>
                                    <td>{{ DateTime::createFromFormat('!m', $mdr->month)->format('F') }} {{ $mdr->year }}</td>
                                    <td>{{ $fullTargetDate->format('F d, Y') }}</td>
                                    <td>{{ date('F d, Y', strtotime($mdr->created_at)) }}</td>
                                </tr>
                                @include('dept-head.view_mdr_status')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#pendingApprovalTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });
    })
</script>
@endpush
