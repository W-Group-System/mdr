<div class="modal" id="editKpi">
    <div class="modal-dialog modal-lg" id="kpiModal">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Edit KPI</h5>
            </div>
            <form method="POST" id="gradeForm" action="{{url('addGradeAndRemarks')}}" enctype="multipart/form-data">
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
                                        <table class="table table-hover table-striped table-bordered" id="editKpiTable">
                                            <thead>
                                                <tr>
                                                    <th>KPI</th>
                                                    <th>Target</th>
                                                    <th>Actual</th>
                                                    <th>Weight</th>
                                                    <th>Grade</th>
                                                    <th>Remarks</th>
                                                    <th>PMO Attachments</th>
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
                                                        <td class="target">
                                                            {{-- <input type="hidden" name="target[]" value="{{$dptGoals->departmentKpi->target}}">
                                                            {{-- {!! nl2br($dptGoals->departmentKpi->target) !!} --}}
                                                            <input type="number" name="target[]" value="{{$dptGoals->target}}">
                                                            {{-- {!! nl2br($dptGoals->target) !!} --}}
                                                        </td>
                                                        <td>
                                                            {{-- {!! nl2br($dptGoals->actual) !!} --}}
                                                            <input type="text" name="actual[]" class="form-control input-sm actual numerical" step="0.001" value="{{ $dptGoals->actual }}" required>
                                                        </td>
                                                        <td class="weight-edit">
                                                            <input type="number" name="weight[]" class="form-control input-sm" step="0.001" value="{{ $dptGoals->weight }}">
                                                            {{-- {!! nl2br($dptGoals->weight) !!} --}}
                                                        </td>
                                                        <td class="edit-weighted-score">
                                                            <input type="hidden" name="grade[]" class="form-control input-sm grade" step="0.001" value="">
                                                            <span class="weighted-score-span">0</span>
                                                        </td>
                                                        {{-- <td>
                                                            {!! nl2br($dptGoals->weight) !!}
                                                            <-- <input type="number" name="weight[]" class="form-control input-sm" step="0.001" value="c" required> -->
                                                        </td>
                                                        <td>
                                                            {!! nl2br($dptGoals->grade) !!}
                                                            <-- <input type="number" name="grade[]" class="form-control input-sm" step="0.001" value="{{$dptGoals->grade}}" max="{{ $dptGoals->weight }}" required> -->
                                                        </td> --}}
                                                        <td>
                                                            <textarea name="remarks[]" class="form-control input-sm" cols="30" rows="10" required>{{$dptGoals->remarks}}</textarea>
                                                        </td>
                                                        <td>
                                                            <input type="file" name="file[{{$key}}][]" class="form-control input-md" multiple>    
                                                        </td>
                                                        {{-- <td>
                                                            <input type="file" name="file[{{$key}}][]" class="form-control input-sm" multiple>
                                                        </td> --}}
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3"></td>
                                                    <td><b>Total Weight</b></td>
                                                    <td><b>Total Weighted Grade</b></td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><input type="hidden" name="final_grade" id="editTotalWeightedScoreHidden" value=""></td>
                                                    <td><h2><span id="editTotalWeight">0.00</span></h2></td>
                                                    <td><h2><span id="EditTotalWeightedScore">0.00</span></h2></td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tfoot>
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