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
                <form action="{{ url('create') }}" method="post" id="submitKpiForm">
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
                                    <td class="tdUpload">
                                        <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $item->id }}">
                                            <i class="fa fa-upload"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-sm btn-primary pull-right" type="submit" data-toggle="modal" data-target="#submitModal">Submit KPI</button>
                </form>
            </div>
        </div>
    </div>

    @foreach ($departmentKpiData->departmentKpi as $item)
        <div class="modal fade uploadModal" id="uploadModal-{{ $item->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Attachments</h1>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>File Upload</label>
                                <form action="/uploadAttachments/{{ $item->id }}" class="dropzone" id="dropzoneForm" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="yearAndMonth" value="{{ $yearAndMonth }}">
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
</div>
@endif

@push('scripts')
    <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function() {
            $("[name='deleteAttachments']").on('click', function() {
                var id = $(this).data('id')

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
                        url: "{{ url('deleteKpiAttachments') }}",
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            swal("Deleted!", "Your file has been deleted.", "success");

                            setTimeout(() => {
                                location.reload()
                            }, 1000);
                        }
                    })
                });
            })

            $("[name='grade[]']").keypress(function(event) {
                if ( event.keyCode == 46 || event.keyCode == 8) {
                }
                else {
                    if (event.keyCode < 48 || event.keyCode > 57) {
                        event.preventDefault(); 
                    }   
                }
            });

            $("#submitKpiForm").on('submit', function(e) {
                console.log('asd');
                e.preventDefault();

                var files = $(this).data();

                console.log(files);
            })

        })
    </script>
@endpush