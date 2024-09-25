<div class="modal" id="newKpi">
    <div class="modal-dialog modal-lg" id="kpiModal">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">New KPI</h5>
            </div>
            <form method="POST" action="{{url('create')}}" onsubmit="show()" enctype="multipart/form-data">
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
                                                @foreach ($mdrSetup as $key=>$setup)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="name[]" value="{{$setup->name}}">
                                                            {!! nl2br($setup->name) !!}
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="target[]" value="{{$setup->target}}">
                                                            {!! nl2br($setup->target) !!}
                                                        </td>
                                                        <td>
                                                            <textarea name="actual[]" class="form-control" cols="30" rows="10" required>{{old('actual[]')}}</textarea>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="grade[]" class="form-control input-sm" maxlength="3" value="{{old('grade[]')}}" disabled required>
                                                        </td>
                                                        <td>
                                                            <textarea name="remarks[]" class="form-control input-sm" cols="30" rows="10" required>{{old('remarks[]')}}</textarea>
                                                        </td>
                                                        <td>
                                                            <input type="file" name="file[{{$key}}][]" class="form-control input-sm" multiple required>
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