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
                            <th>Project Charter</th>
                            <th>Project Benefit</th>
                            <th>Accomplishment Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($innovations as $innovation)
                        <tr>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#edit{{ $innovation->id }}">
                                    <i class="fa fa-pencil-square-o"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                            <td>{{ $innovation->project_charter }}</td>
                            <td>{{ $innovation->project_benefit }}</td>
                            <td>
                                @foreach ($innovation->innovationAttachments as $attachment)
                                    <a href="{{ url($attachment->filepath) }}" target="_blank">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    <br>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('dept-head.add_innovation')
@foreach ($innovations as $innovation)
@include('dept-head.edit_innovation')
@endforeach
