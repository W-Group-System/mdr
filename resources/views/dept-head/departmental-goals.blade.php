<div class="col-lg-12">
    <div class="ibox float-e-margins" style="margin-top: 10px;">
        <div class="ibox-title">
            @if(count($departmentalGoals->where('year', date('Y', strtotime($yearAndMonth)))->where('month', date('m', strtotime($yearAndMonth)))) > 0)
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editKpi" style="margin-top: 3px;">
                <i class="fa fa-pencil"></i>
                Edit KPI
            </button>
            @else
            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#newKpi" @if($departmentalGoals->isNotEmpty()) disabled @endif>
                <i class="fa fa-plus"></i>
                Add KPI
            </button>
            @endif
        </div>
        <div class="ibox-content">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="departmentalGoals">
                    <thead>
                        <tr>
                            {{-- <th>Actions</th> --}}
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
                                <td>{!! nl2br($dptGoals->departmentKpi->name) !!}</td>
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
                                        <form action="{{ url('deleteAttachment/' . $attachment->id) }}" 
                                            method="POST" 
                                            class="deleteAttachmentForm m-0 p-0" 
                                            style="display: contents;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                class="btn btn-danger btn-sm deleteAttachmentBtn p-1 d-flex align-items-center justify-content-center">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
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

<script>
    function deactivateMdr(e)
    {
        $(e).closest("tr").remove()
    }

    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.deleteAttachmentBtn');

        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This file will be permanently deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

@include('dept-head.new_kpi')
@include('dept-head.edit_kpi')
