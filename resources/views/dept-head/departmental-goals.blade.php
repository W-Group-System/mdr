@if($departmentKpiData->name == "Departmental Goals")
<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            @if(Session::get('kpiErrors'))
                <div class="alert alert-danger">
                    @foreach (Session::get('kpiErrors') as $errMsg)
                        {{ $errMsg }} <br>
                    @endforeach
                </div>
            @endif
            
            <div class="table-responsive">
                <p><b>I:</b> <span class="period">{{ $departmentKpiData->name }}</span></p>
                <div class="alert alert-info">
                    <strong>Note: </strong> Attach a file first before submitting a KPI
                </div>
                <form action="{{ url('create') }}" method="post">
                    @csrf

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>KPI</th>
                                <th>Target</th>
                                <th>Actual</th>
                                <th>Grade</th>
                                <th>Remarks</th>
                                <th>Attachments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departmentKpiData->departmentKpi as $item)
                                <tr>
                                    <input type="hidden" name="department_kpi_id[]" value="{{ $item->id }}">
                                    <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                    <td width="300">{!! nl2br($item->name) !!}</td>
                                    <td width="300">{!! nl2br($item->target) !!}</td>
                                    <td>
                                        <textarea name="actual[]" id="actual" cols="30" rows="10" class="form-control" placeholder="Input an actual" required></textarea>
                                    </td>
                                    <td>
                                        <input type="text" name="grade[]" id="grade" class="form-control input-sm" placeholder="Input grade (use percentage)" required>
                                    </td>
                                    <td>
                                        <textarea name="remarks[]" id="remarks" cols="30" rows="10" class="form-control" placeholder="Input a remarks" required></textarea>
                                    </td>
                                    <td width="10">
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $item->id }}">
                                            <i class="fa fa-upload"></i>
                                        </button>

                                        <div class="kpi-attachment-container-{{ $item->id }}">
                                            @foreach ($item->attachments as $attachment)
                                                {{-- @if($dptGoals->department_kpi_id == $attachment->department_kpi_id)
                                                @endif --}}
                                                <div class="attachment-kpi-{{ $attachment->id }}">
                                                    <a href="{{ url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-danger" name="deleteKpiAttachments" data-id="{{ $attachment->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-sm btn-primary pull-right" type="submit">Submit KPI</button>
                </form>
            </div>
        </div>
        
    </div>
</div>

@foreach ($departmentKpiData->departmentKpi as $item)
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
                            {{-- <form action="/uploadAttachments/{{ $item->id }}" class="dropzone" id="dropzoneForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>  --}}

                            <form action="{{ url('uploadAttachments/'. $item->id) }}" method="post" class="uploadKpiAttachmentForm" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                <div class="form-group">
                                    <input type="file" name="file[]" id="file" class="form-control" multiple required>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-sm btn-primary btn-block" type="submit">Add Files</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endif
