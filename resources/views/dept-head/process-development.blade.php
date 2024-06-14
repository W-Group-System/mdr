
@if($departmentKpiData->id == 8)
<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-content">
            <div class="table-responsive">
                <p><span class="period">{{ $departmentKpiData->name }}</span></p>
                
                @include('components.error')

                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addProcessDevelopment">Add Process Improvement</button>

                <table class="table table-bordered table-hover" id="processDevelopmentTable">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Accomplished Date</th>
                            <th>Remarks</th>
                            <th>Attachments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departmentKpiData->processDevelopment as $processDevelopmentData)
                            <tr>
                                <td>{{ $processDevelopmentData->description }}</td>
                                <td>{{ date('F d, Y', strtotime($processDevelopmentData->accomplished_date )) }}</td>
                                <td>{{ $processDevelopmentData->remarks }}</td>
                                <td width="10">
                                    @foreach ($processDevelopmentData->pdAttachments as $key=>$pdFile)
                                        <div class="pd-attachments-{{ $pdFile->id }}">
                                            <a href="{{ $pdFile->filepath }}" class="btn btn-sm btn-info" target="_blank">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-sm btn-danger deletePdAttachments" data-id="{{ $pdFile->id }}" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </td>
                                <td width="10">
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editPdModal-{{ $processDevelopmentData->id }}" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <form action="{{ url('deleteProcessDevelopment/' . $processDevelopmentData->id) }}" method="post" onsubmit="show()">
                                        @csrf

                                        <input type="hidden" name="department_id" value="{{ $processDevelopmentData->department_id }}">
                                        <input type="hidden" name="yearAndMonth" value="{{ $processDevelopmentData->year.'-'.$processDevelopmentData->month }}">

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
    <div class="modal" id="addProcessDevelopment">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Process Improvement</h1>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ url('addProcessDevelopment') }}" method="post" enctype="multipart/form-data" onsubmit="show()">
                                @csrf

                                <input type="hidden" name="dptGroup" value="{{ $departmentKpiData->id }}">
                                <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" name="description" id="description" class="form-control input-sm" required>
                                </div>
                                <div class="form-group" id="accomplishedDate">
                                    <label for="accomplishedDate">Accomplished Date</label>
                                    <input type="date" class="form-control input-sm" name="accomplishedDate" autocomplete="off" required>
                                </div>
                                <div class="form-group">
                                    <label for="file">Upload an Attachments</label>
                                    <input type="file" name="file[]" id="file" class="form-control" multiple required>
                                </div>
                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" cols="30" rows="10" required></textarea>
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

    @foreach ($departmentKpiData->processDevelopment as $pd)
        <div class="modal" id="editPdModal-{{ $pd->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Edit Process Improvement</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form action="{{ url('updateProcessDevelopment/' . $pd->id) }}" method="post" enctype="multipart/form-data" onsubmit="show()">
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
                                        <input type="file" name="file[]" id="file" class="form-control" multiple>
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

        $(".deletePdAttachments").on('click', function() {

            var id = $(this).data('id');

            swal({
                title: "Are you sure?",
                text: "You will not be able to recover your file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                $.ajax({
                    type: "POST",
                    url: "{{ url('deletePdAttachments') }}",
                    data: {
                        file_id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal("Deleted!", response.message, "success");

                        $('.pd-attachments-'+id).remove();
                    }
                })
            });
        })
    })
</script>
@endpush