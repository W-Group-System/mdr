<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-title">
            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#newKpi" @if($departmentalGoals->isNotEmpty()) disabled @endif>
                <i class="fa fa-plus"></i>
                Add KPI
            </button>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="departmentalGoals">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Key Performance Indicator</th>
                            <th>Target</th>
                            <th>Actual</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                            <th>Attachments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departmentalGoals as $dptGoals)
                            <tr>
                                <td>
                                    <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#editKpi{{$dptGoals->id}}">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
                                <td>{!! nl2br($dptGoals->kpi_name) !!}</td>
                                <td>{!! nl2br($dptGoals->target) !!}</td>
                                <td>{{$dptGoals->actual}}</td>
                                <td>{{$dptGoals->grade}}</td>
                                <td>{!! nl2br($dptGoals->remarks) !!}</td>
                                <td>
                                    @foreach ($dptGoals->attachments as $key=>$attachment)
                                        <span>{{$key+1}}. </span>
                                        <a href="{{url($attachment->file_path)}}" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                        <br>
                                    @endforeach
                                </td>
                            </tr>

                            @include('dept-head.edit_kpi')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('dept-head.new_kpi')

{{-- @foreach ($departmentKpiData->mdrSetup as $item)
    <div class="modal" id="uploadModal-{{ $item->id }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Attachments</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <label>File Upload</label>
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
@endforeach --}}
