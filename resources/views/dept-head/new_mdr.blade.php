<div class="modal" id="monthModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select a Month</h5>
            </div>
            <form method="GET" action="{{url('new-mdr')}}" onsubmit="show()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            @php
                                $mdr_score = $mdrScore->first();
                            @endphp
                            Select Year & Month :
                            <input type="month" name="yearAndMonth" min="{{date('Y-m', strtotime("+1 month", strtotime($mdr_score->yearAndMonth)))}}" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Department Approvers
                                </div>
                                <div class="panel-body">
                                    <div class='row text-center'>
                                        <div class='col-md-6 border border-primary border-top-bottom border-left-right'>
                                            <strong>Level</strong>
                                        </div>
                                        <div class='col-md-6 border border-primary border-top-bottom border-left-right'>
                                            <strong>Approver</strong>
                                        </div>
                                    </div>
                                    @foreach ($department_approvers as $approver)
                                        <div class="row text-center">
                                            <div class='col-md-6 border border-primary border-top-bottom border-left-right'>
                                                {{$approver->status_level}}
                                            </div>
                                            <div class='col-md-6 border border-primary border-top-bottom border-left-right'>
                                                {{$approver->user->name}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
            
            {{-- <div class="row">
                <div class="col-lg-12">
                    <form action="{{ url('new-mdr') }}" method="get">
                        <div class="form-group">
                            <input type="month" name="yearAndMonth" min="{{ date(" Y-m", strtotime("+1month",
                                strtotime($yearAndMonth))) }}" max="{{ date('Y-m') }}" class="form-control input-sm"
                                required>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-primary btn-block" type="submit">Next</button>
                        </div>
                    </form>
                </div>
            </div> --}}
        </div>
    </div>
</div>