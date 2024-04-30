<div class="col-lg-12">
    @if($departmentalGoalsData->name == "Departmental Goals")
        <div class="ibox float-e-margins" style="margin-top: 10px;">
            <div class="ibox-content">
                <div class="table-responsive">
                    <p><b>I:</b> <span class="period">{{ $departmentalGoalsData->name }}</span></p>
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
                            @if(count($departmentalGoalsData->departmentalGoals) > 0)
                                @php
                                    $goalsList = $departmentalGoalsData->departmentalGoals->where('department_id', auth()->user()->department_id);
                                @endphp
                                @foreach ($goalsList as $item)
                                    <tr>
                                        <td width="300">{!! nl2br($item->kpi_name) !!}</td>
                                        <td width="300">{!! nl2br($item->target) !!}</td>
                                        <td>
                                            <form action="/addActual/{{ $item->id }}" method="post">
                                                @csrf
                                                <textarea name="actual" id="actual" cols="30" rows="10" class="form-control">{{ $item->actual }}</textarea>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="/addRemarks/{{ $item->id }}" method="post">
                                                @csrf
                                                <textarea name="remarks" id="remarks" cols="30" rows="10" class="form-control">{{ $item->remarks }}</textarea>
                                            </form>
                                        </td>
                                        <td width="100">
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $item->id }}">
                                                <i class="fa fa-upload"></i>
                                            </button>

                                            @foreach ($item->attachments as $file)
                                                <div>
                                                    <a href="{{ asset('file/' . $file->file_name) }}" class="btn btn-sm btn-info" target="_blank">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    <button type="button" class="btn btn-sm btn-danger" name="deleteAttachments" data-id="{{ $file->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="text-center">No data available</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

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
                            {{-- <form action="/uploadAttachments/{{ $item->id }}" method="post" enctype="multipart/form-data">
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
                                            <input type="file" name="file" multiple/>
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div> 
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary pull-right" type="submit">Submit</button>
                                </div>
                            </form> --}}
                            <form action="/uploadAttachments/{{ $item->id }}" class="dropzone" id="dropzoneForm" method="POST">
                                @csrf

                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
    <script>
        $(document).ready(function() {
            // $('[name="actual"]').on('change',function() {
            //     $(this).parent()[0].submit()
            // })

            // $('[name="remarks"]').on('change',function() {
            //     $(this).parent()[0].submit()
            // })

            $("[name='deleteAttachments']").on('click', function() {
                var id = $(this).data('id')

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

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
                        url: "/deleteAttachments",
                        data: {
                            id: id
                        },
                        success: function() {
                            swal("Deleted!", "Your imaginary file has been deleted.", "success");

                            location.reload()
                        }
                    })
                });
            })

        })
    </script>
@endpush