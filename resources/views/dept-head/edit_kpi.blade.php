{{-- <div class="modal" id="editKpi{{$dptGoals->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Edit KPI</h5>
            </div>
            <form method="POST" action="{{url('update_kpi/'.$dptGoals->id)}}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Actual :
                            <textarea name="actual" class="form-control" cols="30" rows="10" required>{!! nl2br($dptGoals->actual) !!}</textarea>
                        </div>
                        <div class="col-md-12">
                            Grade :
                            <input type="number" name="grade" class="form-control input-sm" value="{{$dptGoals->grade}}" required>
                        </div>
                        <div class="col-md-12">
                            Remarks :
                            <textarea name="remarks" class="form-control" cols="30" rows="10" required>{!! nl2br($dptGoals->remarks) !!}</textarea>
                        </div>
                        <div class="col-md-12">
                            Attachments :
                            <input type="file" name="file" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<div class="modal" id="editKpi">
    <div class="modal-dialog modal-lg" id="kpiModal">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Edit KPI</h5>
            </div>
            <form method="POST" action="{{url('update_kpi')}}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="yearAndMonth" value="{{$yearAndMonth}}">
                <input type="hidden" name="target_date" value="{{auth()->user()->department->target_date}}">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    Department KPI
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-bordered">
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
                                                @foreach ($departmentalGoals as $key=>$dptGoals)
                                                    <input type="hidden" name="department_goals_id[]" value="{{$dptGoals->id}}">

                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="name[]" value="{{$dptGoals->kpi_name}}">
                                                            {!! nl2br($dptGoals->kpi_name) !!}
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="target[]" value="{{$dptGoals->target}}">
                                                            {!! nl2br($dptGoals->target) !!}
                                                        </td>
                                                        <td>
                                                            <textarea name="actual[]" class="form-control" cols="30" rows="10" required>{{$dptGoals->actual}}</textarea>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="grade[]" class="form-control input-sm" maxlength="3" value="{{$dptGoals->grade}}" disabled required>
                                                        </td>
                                                        <td>
                                                            <textarea name="remarks[]" class="form-control input-sm" cols="30" rows="10" required>{{$dptGoals->remarks}}</textarea>
                                                        </td>
                                                        <td>
                                                            <input type="file" name="file[{{$key}}][]" class="form-control input-sm" multiple>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>