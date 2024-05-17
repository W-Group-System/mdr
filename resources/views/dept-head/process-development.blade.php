
@if($departmentKpiData->name == "Process Development")
<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            <div class="table-responsive">
                <p><b>III:</b> <span class="period">{{ $departmentKpiData->name }}</span></p>
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
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $processDevelopmentList = $departmentKpiData->processDevelopment()
                                ->where('department_id', auth()->user()->department_id)
                                ->where('year', date('Y'))
                                ->where('month', date('m'))
                                ->where('final_approved', 0)
                                ->get();
                        @endphp
                        @foreach ($processDevelopmentList as $processDevelopmentData)
                            <tr>
                                <td>{{ $processDevelopmentData->description }}</td>
                                <td>{{ date('F d, Y', strtotime($processDevelopmentData->accomplished_date )) }}</td>
                                <td>
                                    <a href="{{ asset('file/' . $processDevelopmentData->pd_attachments->filename) }}" class="btn btn-sm btn-info" target="_blank">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                                <td>{{ $processDevelopmentData->remarks }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editPdModal-{{ $processDevelopmentData->id }}" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <form action="{{ url('deleteProcessDevelopment/' . $processDevelopmentData->id) }}" method="post">
                                        @csrf

                                        <input type="hidden" name="department_id" value="{{ $processDevelopmentData->department_id }}">
                                        <input type="hidden" name="year" value="{{ $processDevelopmentData->year }}">
                                        <input type="hidden" name="month" value="{{ $processDevelopmentData->month }}">

                                        <button type="submit" class="btn btn-sm btn-danger" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
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
                                    <label for="monthOf">Month Of</label>
                                    <input type="month" name="monthOf" id="monthOf" class="form-control input-sm" max="{{ date('Y-m') }}">
                                </div>
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10"></textarea>
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

    @foreach ($processDevelopmentList as $pd)
        <div class="modal fade" id="editPdModal-{{ $pd->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Edit Process Development</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ url('updateProcessDevelopment/' . $pd->id) }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <input type="hidden" name="pd_id" value="{{ $pd->id }}">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" name="description" id="description" class="form-control input-sm" value="{{ $pd->description }}">
                                    </div>
                                    <div class="form-group" id="accomplishedDate">
                                        <label for="accomplishedDate">Accomplished Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="accomplishedDate" autocomplete="off" value="{{ $pd->accomplished_date }}">
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
                                        <label for="remarks">Remarks</label>
                                        <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10">{{ $pd->remarks }}</textarea>
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
</div>
@endif

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