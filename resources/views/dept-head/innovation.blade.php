<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            @if (Session::has('errors'))
                <div class="alert alert-danger">
                    @foreach (Session::get('errors') as $errors)
                        {{ $errors }}<br>
                    @endforeach
                </div>
            @endif
            <div class="table-responsive">
                <p><b>II:</b> <span class="period">Innovations (Accomplished)</span></p>
                @if($innovationCount < 2)
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addModal">Add Innovation</button>
                @endif
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Innovations / Projects</th>
                            <th>Project Summary</th>
                            <th>Job / Work Order Number</th>
                            <th>Start Date</th>
                            <th>Target Date of Completion</th>
                            <th>Actual Date of Completion</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($innovationList) > 0)
                            @foreach ($innovationList as $innovationData)
                                @foreach ($innovationData->innovations as $item)
                                    <tr>
                                        <td>{{ $item->projects }}</td>
                                        <td>{!! nl2br($item->project_summary) !!}</td>
                                        <td>{{ $item->work_order_number }}</td>
                                        <td>{{ date('F d, Y' , strtotime($item->start_date)) }}</td>
                                        <td>{{ date('F d, Y', strtotime($item->target_date ))}}</td>
                                        <td>{{ !empty($item->actual_date) ? date('F d, Y', strtotime($item->actual_date)) : null }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal-{{ $item->id }}">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <form action="/deleteInnovation/{{ $item->id }}" method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <td colspan="7" class="text-center">No data available.</td>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title">Add Innovation</h1>
            </div>
            <div class="modal-body p-4" >
                <div class="row">
                    <div class="col-lg-12">
                        <form action="/addInnovation" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="innovationProjects">Innovation Projects</label>
                                <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="projectSummary">Project Summary</label>
                                <textarea name="projectSummary" cols="30" rows="10" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="jobOrWorkNum">Job / Work Number</label>
                                <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm">
                            </div>
                            <div class="form-group" id="startDate">
                                <label for="startDate">Start Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="startDate">
                                </div>
                            </div>
                            <div class="form-group" id="targetDate">
                                <label for="targetDate">Target Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="targetDate">
                                </div>
                            </div>
                            <div class="form-group" id="actualDate">
                                <label for="actualDate">Actual Date</label>
                                <div class="input-group date">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input type="text" class="form-control input-sm" name="actualDate">
                                </div>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary btn-block" type="submit">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($innovationList as $innovationData)
    @foreach ($innovationData->innovations as $item)
        <div class="modal fade" id="editModal-{{ $item->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Edit Innovations</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="/updateInnovation/{{ $item->id }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="innovationProjects">Innovation Projects</label>
                                        <input type="text" name="innovationProjects" id="innovationProjects" class="form-control input-sm" value="{{ $item->projects }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="projectSummary">Project Summary</label>
                                        <textarea name="projectSummary" id="" cols="30" rows="10" class="form-control">{{ $item->project_summary }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="jobOrWorkNum">Job / Work Number</label>
                                        <input type="text" name="jobOrWorkNum" id="jobOrWorkNum" class="form-control input-sm" value="{{ $item->work_order_number }}">
                                    </div>
                                    <div class="form-group" id="startDate">
                                        <label for="startDate">Start Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="startDate" value="{{ $item->start_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group" id="targetDate">
                                        <label for="targetDate">Target Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="targetDate" value="{{ $item->target_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group" id="actualDate">
                                        <label for="actualDate">Actual Date</label>
                                        <div class="input-group date">
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            <input type="text" class="form-control input-sm" name="actualDate" value="{{ $item->actual_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary btn-block" type="submit"Update>Update</button>
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
<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

<script>
    $(document).ready(function() {
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
            startDate: dateToday
        });

        $('#actualDate .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            startDate: dateToday
        });
    })
</script>
@endpush