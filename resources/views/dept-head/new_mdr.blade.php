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
                            Select Year & Month :
                            @if($year_and_month)
                            <input type="month" name="yearAndMonth" min="{{date('Y-m', strtotime("+1 month", strtotime($year_and_month)))}}" class="form-control input-sm" required>
                            @else
                            <input type="month" name="yearAndMonth" class="form-control input-sm" required>
                            @endif
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
                                    @foreach ($department_approvers->where('status','Active') as $approver)
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
        </div>
    </div>
</div>