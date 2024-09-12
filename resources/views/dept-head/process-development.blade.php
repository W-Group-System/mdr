<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-title">
            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#addProcessDevelopment">
                <i class="fa fa-plus"></i>
                Add Process Improvement
            </button>
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                @include('components.error')

                <table class="table table-bordered table-hover" id="processDevelopmentTable">
                    <thead>
                        <tr>
                            <th>Actions</th>
                            <th>Description</th>
                            <th>Accomplished Date</th>
                            <th>Remarks</th>
                            <th>Attachments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($process_improvement as $processDevelopmentData)
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editProcessDevelopment{{ $processDevelopmentData->id }}">
                                        <i class="fa fa-pencil"></i>
                                    </button>

                                    <form action="{{ url('deleteProcessDevelopment/' . $processDevelopmentData->id) }}" method="post" onsubmit="show()" style="display: inline-block;">
                                        @csrf

                                        <input type="hidden" name="department_id" value="{{ $processDevelopmentData->department_id }}">
                                        <input type="hidden" name="yearAndMonth" value="{{ $processDevelopmentData->year.'-'.$processDevelopmentData->month }}">

                                        <button type="submit" class="btn btn-sm btn-danger" {{ $processDevelopmentData->status_level != 0 ? 'disabled' : '' }}>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $processDevelopmentData->description }}</td>
                                <td>{{ date('F d, Y', strtotime($processDevelopmentData->accomplished_date )) }}</td>
                                <td>{!! nl2br($processDevelopmentData->remarks) !!}</td>
                                <td>
                                    @foreach ($processDevelopmentData->pdAttachments as $key=>$pdFile)
                                        <span>{{$key+1}}. </span>
                                        <a href="{{ url($pdFile->filepath) }}" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                        <br>
                                    @endforeach
                                </td>
                            </tr>

                            @include('dept-head.edit_process_improvement')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('dept-head.add_process_improvement')

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