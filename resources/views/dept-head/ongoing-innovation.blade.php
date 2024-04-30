<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            <div class="table-responsive">
                <p><b>IV:</b> <span class="period">Ongoing Innovations</span></p>
                @if (Session::has('ongoingInnovationErrors'))
                    <div class="alert alert-danger">
                        @foreach (Session::get('ongoingInnovationErrors') as $errors)
                            {{ $errors }}<br>
                        @endforeach
                    </div>
                @endif
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addOngoingInnovationModal">Add Ongoing Innovation Plan</button>
                <table class="table table-bordered table-hover" id="ongoingInnovationTable">
                    <thead>
                        <tr>
                            <th>Innovations / Projects</th>
                            <th>Current Status</th>
                            <th>Job/Work Order Number</th>
                            <th>Start Date</th>
                            <th>Target Date of Completion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($ongoingInnovationList) > 0)
                            @foreach ($ongoingInnovationList as $ongoingInnovationData)
                                @foreach ($ongoingInnovationData->ongoingInnovation as $ongoingInnovation)
                                    <tr>
                                        <td>{{ $ongoingInnovation->innovation_projects }}</td>
                                        <td>{{ $ongoingInnovation->current_status }}</td>
                                        <td>{{ $ongoingInnovation->work_number }}</td>
                                        <td>{{ date('F d, Y', strtotime($ongoingInnovation->start_date )) }}</td>
                                        <td>{{ date('F d, Y', strtotime($ongoingInnovation->target_date )) }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#ongoingInnovationEdit-{{ $ongoingInnovation->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>

                                            <form action="deleteOngoingInnovation/{{ $ongoingInnovation->id }}" method="post">
                                                @csrf

                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addOngoingInnovationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Add Ongoing Innovations</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="addOngoingInnovation" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="innovationProjects">Innovation/Projects</label>
                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="currentStatus">Current Status</label>
                                <input type="text" name="currentStatus" id="currentStatus" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="jobWorkNumber">Job/Work Order Number</label>
                                <input type="text" name="jobWorkNumber" id="jobWorkNumber" class="form-control input-sm">
                            </div>
                            <div class="form-group" id="startDate">
                                <label for="startDate">Start Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="startDate" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group" id="targetDate">
                                <label for="targetDate">Actual Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="targetDate" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary btn-block">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($ongoingInnovationList as $ongoingInnovationData)
    @foreach ($ongoingInnovationData->ongoingInnovation as $item)
        <div class="modal fade" id="ongoingInnovationEdit-{{ $item->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Edit Ongoing Innovation Plan</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="/updateOngoingInnovation/{{ $item->id }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="innovationProjects">Innovation/Projects</label>
                                        <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" value="{{ $item->innovation_projects }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="currentStatus">Current Status</label>
                                        <input type="text" name="currentStatus" id="currentStatus" class="form-control input-sm" value="{{ $item->current_status }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="jobWorkNumber">Job/Work Order Number</label>
                                        <input type="text" name="jobWorkNumber" id="jobWorkNumber" class="form-control input-sm" value="{{ $item->work_number }}">
                                    </div>
                                    <div class="form-group" id="startDate">
                                        <label for="startDate">Start Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="startDate" autocomplete="off" value="{{ $item->start_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group" id="targetDate">
                                        <label for="targetDate">Actual Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="targetDate" autocomplete="off" value="{{ $item->target_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary btn-block">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

@push('scripts')
<!-- Mainly scripts -->
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function() {
        $('#ongoingInnovationTable').DataTable({
            pageLength: 10,
            ordering: false,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
        });

        var dateToday = new Date();

        $('#startDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday,
        });

        $('#targetDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday,
        });
    })
</script>
@endpush