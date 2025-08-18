<div class="modal" id="editKpi">
    <div class="modal-dialog modal-lg" id="kpiModal">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Edit KPI</h5>
            </div>
            <form method="POST" action="{{url('addGradeAndRemarks')}}" onsubmit="show()">
                @csrf
                <input type="hidden" name="yearAndMonth" value="{{$mdrSummary->yearAndMonth}}">
                <input type="hidden" name="target_date" value="{{$mdrSummary->departments->target_date}}">
                <input type="hidden" name="department" value="{{$mdrSummary->department_id}}">
                <input type="hidden" name="mdr_id" value="{{$mdrSummary->id}}">

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
                                                    <th>Weight</th>
                                                    <th>Grade</th>
                                                    <th>Remarks</th>
                                                    {{-- <th>Attachments</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mdrSummary->departmentalGoals as $key=>$dptGoals)
                                                    <input type="hidden" name="department_goals_id[]" value="{{$dptGoals->id}}">

                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="name[]" value="{{$dptGoals->departmentKpi->name}}">
                                                            {!! nl2br($dptGoals->departmentKpi->name) !!}
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="target[]" value="{{$dptGoals->departmentKpi->target}}">
                                                            {!! nl2br($dptGoals->departmentKpi->target) !!}
                                                        </td>
                                                        <td>
                                                            {{$dptGoals->actual}}
                                                        </td>
                                                        <td>
                                                            <input type="number" name="weight[]" class="form-control input-sm" step=".01" value="{{$dptGoals->weight}}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="grade[]" class="form-control input-sm" step=".01" value="{{$dptGoals->grade}}" required>
                                                        </td>
                                                        <td>
                                                            <textarea name="remarks[]" class="form-control input-sm" cols="30" rows="10" required>{{$dptGoals->remarks}}</textarea>
                                                        </td>
                                                        {{-- <td>
                                                            <input type="file" name="file[{{$key}}][]" class="form-control input-sm" multiple>
                                                        </td> --}}
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