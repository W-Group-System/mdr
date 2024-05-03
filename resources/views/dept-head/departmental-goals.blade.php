<div class="col-lg-12">
    @if($departmentalGoalsData->name == "Departmental Goals")
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
                    <p><b>I:</b> <span class="period">{{ $departmentalGoalsData->name }}</span></p>

                    <form action="{{ url('submitKpi') }}" method="post">
                        @csrf
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>KPI</th>
                                    <th>Target</th>
                                    <th>Grade</th>
                                    {{-- <th>Actual</th> --}}
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
                                        <input type="hidden" name="departmental_goals_id[]" value="{{ $item->id }}">
                                        <tr>
                                            <td width="300">{!! nl2br($item->kpi_name) !!}</td>
                                            <td width="300">{!! nl2br($item->target) !!}</td>
                                            <td>
                                                <textarea name="grade[]" id="grade" class="form-control" cols="30" rows="10"></textarea>
                                            </td>
                                            {{-- <td>
                                                <textarea name="actual[]" id="actual" cols="30" rows="10" class="form-control">{{ $item->actual }}</textarea>
                                            </td> --}}
                                            <td>
                                                <textarea name="remarks[]" id="remarks" cols="30" rows="10" class="form-control"></textarea>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#uploadModal-{{ $item->id }}">
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
                        <button class="btn btn-sm btn-primary pull-right" type="button" data-toggle="modal" data-target="#submitModal">Submit KPI</button>

                        <div class="modal fade" id="submitModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title">Select a Month</h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    {{-- <div class="form-group" id="data_4">
                                                        <label class="font-normal">Month select</label>
                                                        <div class="input-group date">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="07/01/2014">
                                                        </div>
                                                    </div> --}}
                                                    
                                                    <select name="month" id="month" class="form-control">
                                                        <option value="">- Month -</option>
                                                        @foreach ($months as $key => $month)
                                                            <option value="{{ $key }}" {{ $currentMonth >= $key ? '' : 'disabled' }}>
                                                                {{ $month }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-sm btn-primary btn-block" type="submit">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@foreach ($departmentalGoalsData->departmentalGoals as $item)
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
    <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <script>
        $(document).ready(function() {
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

                            setTimeout(() => {
                                location.reload()
                            }, 1000);
                        }
                    })
                });
            })

            $(".uploadModal").on('hidden.bs.modal', function() {
                location.reload()
            })

            $("[name='grade[]']").keypress(function(event) {
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 37) {
                }
                else {
                    if (event.keyCode < 48 || event.keyCode > 57) {
                        event.preventDefault(); 
                    }   
                }
            });

            $("#month").chosen({width: "100%"})

            
        })
    </script>
@endpush