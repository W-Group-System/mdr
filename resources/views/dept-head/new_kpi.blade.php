<div class="modal" id="newKpi">
    <div class="modal-dialog modal-lg" id="kpiModal">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">New KPI</h5>
            </div>
            <form method="POST" action="{{url('create')}}" onsubmit="show()" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="yearAndMonth" value="{{$yearAndMonth}}">
                {{-- <input type="hidden" name="target_date" value="{{auth()->user()->department->target_date}}"> --}}

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
                                                    {{-- <th>Grade</th> --}}
                                                    <th>Remarks</th>
                                                    <th>Attachments</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($department_kpis as $key=>$department_kpi)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="department_kpi_id[{{ $key }}]" value="{{$department_kpi->id}}">
                                                            {!! nl2br($department_kpi->name) !!}
                                                        </td>
                                                        <td>
                                                            <textarea name="target[{{ $key }}]" class="form-control" cols="30" rows="10" required>{{$department_kpi->target}}</textarea>
                                                        </td>
                                                        <td>
                                                            <textarea name="actual[{{ $key }}]" class="form-control" cols="30" rows="10" required></textarea>
                                                        </td>
                                                        {{-- <td>
                                                            <input type="number" name="grade[]" class="form-control input-sm" maxlength="3" value="{{old('grade[]')}}" disabled required>
                                                        </td> --}}
                                                        <td>
                                                            <textarea name="remarks[{{ $key }}]" class="form-control input-sm" cols="30" rows="10" required></textarea>
                                                        </td>
                                                        <td>
                                                            <input type="file" name="file[{{ $key }}][]" class="form-control input-sm" multiple required>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" onclick="deactivateMdr(this)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
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