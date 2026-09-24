
<!-- Innovation -->
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
                            <th>Project Title</th>
                            <th>Project Expectations/Benefits</th>
                            <th>Attachment</th>
                            <th>Grade</th>
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
                            <td>
                                0.00
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End -->
 
<!-- MDR Score -->
<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-title">
            <h5>MDR Score</h5>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-bordered" id="innovationTable">
                    @php $yearAndMonth = request('yearAndMonth'); @endphp
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Operational</th>
                            <th>Timeliness</th>
                            <th>Innovation</th>
                            <th>Total Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                {{ $yearAndMonth ? \Carbon\Carbon::createFromFormat('Y-m', $yearAndMonth)->format('F Y') : '' }}
                            </td>
                            <td>0.00</td>
                            <td>0.00</td>
                            <td>0.00</td>
                            <td>0.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End -->


@include('dept-head.add_innovation')
@foreach ($innovations as $innovation)
@include('dept-head.edit_innovation')
@endforeach
