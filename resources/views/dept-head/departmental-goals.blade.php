<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            <div class="table-responsive">
                <p><b>I:</b> <span class="period">{{$goal->name}}</span></p>
                
                @if($goal->name == "Departmental Goals")
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>KPI</th>
                            <th>Target</th>
                            <th>Actual</th>
                            <th>Remarks</th>
                            <th>Attachments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goal->departmentalGoals as $key => $depgoals)
                            <tr>
                                <td>{{$depgoals->kpi_name}}</td>
                                <td>{{$depgoals->target}}</td>
                                <td><textarea class='form-control' name='actual[{{$depgoals->id}}]' >{{$depgoals->actual}}</textarea></td>
                                <td>{{count($goal->departmentalGoals)}}</td>
                                <td>Attachments</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                @if(count($goal->innovations) < 2)
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
                            @foreach ($goal->innovations as $item)
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
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- 
@foreach ($departmentalGoalsList as $departmentalGoalsData)
    @foreach ($departmentalGoalsData->departmentalGoals as $item)
        <div class="modal fade" id="uploadModal-{{ $item->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Attachments</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>File Upload</label>
                                <form action="/uploadAttachments/{{ $item->id }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="fa fa-file fileinput-exists"></i>
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
                                        <button class="btn btn-primary pull-right" type="submit">Submit</button>
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
    <script>
        $(document).ready(function() {
            $('[name="actual"]').on('change',function() {
                $(this).parent()[0].submit()
            })

            $('[name="remarks"]').on('change',function() {
                $(this).parent()[0].submit()
            })
        })
    </script>
@endpush