
<div class="col-lg-12">
    @if($departmentKpiData->name == "Process Development")
        <div class="ibox float-e-margins" style="margin-top: 10px;">
            <div class="ibox-content">
                <div class="table-responsive">
                    <p><b>II:</b> <span class="period">{{ $departmentKpiData->name }}</span></p>
                    @if (Session::has('pdError'))
                        <div class="alert alert-danger">
                            @foreach (Session::get('pdError') as $errors)
                                {{ $errors }}<br>
                            @endforeach
                        </div>
                    @endif
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addProcessDevelopment">Add Process Development</button>

                    <table class="table table-bordered table-hover" id="processDevelopmentTable">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Accomplished Date</th>
                                <th>Attachments</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departmentKpiData->processDevelopment as $processDevelopmentData)
                                <tr>
                                    <td>{{ $processDevelopmentData->description }}</td>
                                    <td>{{ date('F d, Y', strtotime($processDevelopmentData->accomplished_date )) }}</td>
                                    <td>
                                        <a href="{{ asset('file/' . $processDevelopmentData->pd_attachments->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-warning">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addProcessDevelopment">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Process Development</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ url('addProcessDevelopment') }}" method="post" enctype="multipart/form-data">
                                    @csrf
        
                                    <input type="hidden" name="pd_id" value="{{ $departmentKpiData->id }}">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" name="description" id="description" class="form-control input-sm">
                                    </div>
                                    <div class="form-group" id="accomplishedDate">
                                        <label for="accomplishedDate">Accomplished Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="accomplishedDate" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="file">Upload an Attachments</label>
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="fa fa-file"></i>
                                            <span class="fileinput-filename"></span>
                                            </div>
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">Select file</span>
                                                <span class="fileinput-exists">Change</span>
                                                <input type="file" name="file"/>
                                            </span>
                                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
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
    @endif
</div>


{{-- @foreach ($businessPlanList as $businessPlanData)
    @foreach ($businessPlanData->businessPlans as $item)
        <div class="modal fade" id="businessPlanEdit-{{ $item->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Edit Business Plan</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="/updateBusinessPlan/{{ $item->id }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="activities">Activities</label>
                                        <input type="text" name="activities" id="activities" class="form-control input-sm" value="{{ $item->activities }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="baseOnPlanned">Base on Planned</label>
                                        <select name="baseOnPlanned" id="baseOnPlanned" class="form-control input-sm">
                                            <option value="">- Is Base on Planned ? -</option>
                                            <option value="1" {{ $item->isBasedOnPlanned == 1 ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ $item->isBasedOnPlanned == 0 ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="proofOfCompletion">Proof of Completion</label>
                                        <input type="text" name="proofOfCompletion" id="proofOfCompletion" class="form-control input-sm" value="{{ $item->proof_of_completion }}">
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
                                    <div class="form-group" id="actualDate">
                                        <label for="actualDate">Actual Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="actualDate" autocomplete="off" value="{{ $item->end_date }}">
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
@endforeach --}}

@push('scripts')
<script src="js/plugins/dataTables/datatables.min.js"></script>

<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script>
    $(document).ready(function() {
        var dateToday = new Date();

        $('#accomplishedDate .input-group.date').datepicker({
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