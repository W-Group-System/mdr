@extends('layouts.app')
@section('content')
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Total of Penalties</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary)}}</h1>
                    <small>Total Penalties</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Waived</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary->where('penalty_status',"Waived"))}}</h1>
                    <small>Total Waived</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Notice of Explanation</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary->where('penalty_status',"For NTE"))}}</h1>
                    <small>Total NTE</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Notice of Disciplinary</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary->where('penalty_status',"For NOD"))}}</h1>
                    <small>Total NOD</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Process Improvement Plan</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($mdrSummary->where('penalty_status',"For PIP"))}}</h1>
                    <small>Total PIP</small>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        @include('components.error')

                        <table class="table table-bordered" id="penaltiesTable">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Department</th>
                                    <th>Department Head</th>
                                    <th>Month</th>
                                    <th>Total Rating</th>
                                    <th>Uploaded By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary as $mdrSummaryData)
                                <tr>
                                    <td>
                                        @if(!empty($mdrSummaryData->nteAttachments))
                                            <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#view{{$mdrSummaryData->id}}" type="button" title="View">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ $mdrSummaryData->departments->name }}</td>
                                    <td>{{ $mdrSummaryData->user->name }}</td>
                                    <td>{{ date('F Y', strtotime($mdrSummaryData->yearAndMonth)) }}</td>
                                    <td>{{ $mdrSummaryData->mdrScoreHasOne->total_rating }}</td>
                                    <td>{{ !empty($mdrSummaryData->nteAttachments->users->name) ? $mdrSummaryData->nteAttachments->users->name : '' }}</td>
                                </tr>

                                @include('approver.view_penalties')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="js/plugins/dataTables/datatables.min.js"></script>
    <script src="js/plugins/chosen/chosen.jquery.js"></script>

    <script>
        $(document).ready(function() {
            $('#penaltiesTable').DataTable({
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