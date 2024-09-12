<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-title">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
                <i class="fa fa-plus"></i>
                Add Innovation
            </button>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-bordered" id="innovationTable">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Innovations / Projects</th>
                            <th>Project Summary</th>
                            <th>Job / Work Order Number</th>
                            <th>Start Date</th>
                            <th>Target Date of Completion</th>
                            <th>Actual Date of Completion</th>
                            <th>Remarks</th>
                            <th>Attachments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($innovation as $data)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{$data->id}}">
                                    <div class="fa fa-pencil-square-o"></div>
                                </button>
                            </td>
                            <td>{{ $data->projects }}</td>
                            <td>{{ $data->project_summary }}</td>
                            <td>{{ $data->work_order_number }}</td>
                            <td>{{ date('F m, Y', strtotime($data->start_date)) }}</td>
                            <td>{{ date('F m, Y', strtotime($data->target_date)) }}</td>
                            <td>{{ date('F m, Y', strtotime($data->actual_date)) }}</td>
                            <td>
                                {!! nl2br(e($data->remarks)) !!}
                            </td>
                            <td>
                                @foreach ($data->innovationAttachments as $key=>$file)
                                <span>{{ $key+1 }}. &nbsp;</span>
                                <a href="{{ url($file->filepath) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i>
                                </a>
                                <br>
                                @endforeach
                            </td>
                        </tr>

                        @include('dept-head.edit_innovation')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('dept-head.add_innovation')