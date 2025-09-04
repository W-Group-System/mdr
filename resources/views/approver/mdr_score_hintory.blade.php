<div class="modal fade" id="remarksHistory{{ $mdrSummary->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">View Remarks History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mdrSummary->mdrHistoryLogs->where('action', 'Edit Innovation Score') as $logs)
                                <tr>
                                    <td>{{ $logs->remarks }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center text-muted">No remarks found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
