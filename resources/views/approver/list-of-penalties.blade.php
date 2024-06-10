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
        <div class="col-lg-2">
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

                        <table class="table table-striped table-bordered table-hover" id="penaltiesTable">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Department Head</th>
                                    <th>Month</th>
                                    <th>Total Rating</th>
                                    <th>Uploaded By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mdrSummary as $mdrSummaryData)
                                <tr>
                                    <td>{{ $mdrSummaryData->departments->name }}</td>
                                    <td>{{ $mdrSummaryData->departments->user->name }}</td>
                                    <td>{{ date('F Y', strtotime($mdrSummaryData->year.'-'.$mdrSummaryData->month)) }}</td>
                                    <td>{{ $mdrSummaryData->rate }}</td>
                                    <td>{{ !empty($mdrSummaryData->nteAttachments->users->name) ? $mdrSummaryData->nteAttachments->users->name : '' }}</td>
                                    <td width="100">
                                        @if(!empty($mdrSummaryData->nteAttachments))
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#viewModal-{{$mdrSummaryData->id}}" type="button" title="View">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @foreach ($mdrSummary as $mdrSummaryData)
                        @if(!empty($mdrSummaryData->nteAttachments))
                        <div class="modal" id="viewModal-{{$mdrSummaryData->id}}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">View Status</h1>
                                    </div>
                                    <div class="modal-body">
                                        @if(!empty($mdrSummaryData->nteAttachments))
                                        <div class="row">
                                            <div class="col-md-4">
                                                NTE Files :
                                                <span>
                                                    <a href="{{$mdrSummaryData->nteAttachments->filepath}}" target="_blank">{{$mdrSummaryData->nteAttachments->filename}}</a>
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                Acknowledge by :
                                                <span>{{isset($mdrSummaryData->nteAttachments->acknowledge->name)?$mdrSummaryData->nteAttachments->acknowledge->name:''}}</span>
                                            </div>
                                            <div class="col-md-4">
                                                Status :
                                                <span>{{$mdrSummaryData->nteAttachments->status}}</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($mdrSummaryData->nodAttachments))
                                        <div class="row">
                                            <div class="col-md-4">
                                                NOD Files :
                                                <span>
                                                    <a href="{{$mdrSummaryData->nodAttachments->filepath}}" target="_blank">{{$mdrSummaryData->nodAttachments->filename}}</a>
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                Acknowledge by :
                                                <span>{{isset($mdrSummaryData->nodAttachments->acknowledge->name)?$mdrSummaryData->nodAttachments->acknowledge->name:''}}</span>
                                            </div>
                                            <div class="col-md-4">
                                                Status :
                                                <span>{{$mdrSummaryData->nodAttachments->status}}</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($mdrSummaryData->pipAttachments))
                                        <div class="row">
                                            <div class="col-md-4">
                                                PIP Files :
                                                <span>
                                                    <a href="{{$mdrSummaryData->nteAttachments->filepath}}" target="_blank">{{$mdrSummaryData->nteAttachments->filename}}</a>
                                                </span>
                                            </div>
                                            <div class="col-md-4">
                                                Acknowledge by :
                                                <span>{{isset($mdrSummaryData->nteAttachments->acknowledge->name)?$mdrSummaryData->nteAttachments->acknowledge->name:''}}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
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