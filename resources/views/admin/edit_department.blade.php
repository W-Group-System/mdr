<div class="modal" id="editModal-{{ $departmentData->id }}" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Edit Department</h5>
            </div>
            <form role="form" method="post" id="updateForm" action="{{url('updateDepartments/'.$departmentData->id)}}" onsubmit="show()">
                @csrf   
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Department Code :
                            <input type="text" name="departmentCode" class="form-control input-sm" value="{{ $departmentData->code }}" required>
                        </div>
                        <div class="col-md-12">
                            Department Name :
                            <input type="text" name="departmentName" class="form-control input-sm" value="{{ $departmentData->name }}" required>
                        </div>
                        <div class="col-md-12">
                            Department Head :
                            <select name="departmentHead" id="departmentHead" class="form-control cat">
                                <option value="">-Department Head-</option>
                                @foreach ($user->whereIn('role', ['Department Head', 'Business Process Manager']) as $headData)
                                    <option value="{{ $headData->id }}" {{ $headData->id == $departmentData->user_id ? 'selected' : '' }}>{{ $headData->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Target Date :
                            <select name="targetDate" id="targetDate" name="targetDate" class="form-control cat">
                                <option value="">- Target Date -</option>
                                @foreach ($targetDate as $key=>$td)
                                    <option value="{{$key}}"  {{$departmentData->target_date == $key ? 'selected' : ''}}>{{$td}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-12">
                            <button type="button" class="btn btn-sm btn-primary addApprover">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger deleteApprover">
                                <i class="fa fa-trash"></i>
                            </button>
                            <div class="form-group">
                                Department Approver :
                                <div class="approverFormGroup">
                                    @foreach ($departmentData->approver as $approver)
                                        <div class="select-container">
                                            <select name="approver[]" class="form-control cat approver">
                                                <option value=""></option>
                                                @foreach($user->whereIn('role', ['Approver', 'Business Process Manager', 'Department Head']) as $approverData)
                                                    <option value="{{ $approverData->id }}" {{ $approverData->id == $approver->user_id ? 'selected' : '' }}>{{ $approverData->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-12">
                            <hr>
                            <button type="button" class="btn btn-xs btn-primary addApprover" onclick="add_edit_approver({{$departmentData->id}})">
                                <i class="fa fa-plus-square-o"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-danger deleteApprover" onclick="delete_edit_approver({{$departmentData->id}})">
                                <i class="fa fa-minus-square-o"></i>
                            </button>
                            <span>&nbsp;</span>
                            <div class='approvers-data-{{$departmentData->id}} form-group'>
                                @foreach ($departmentData->approver as $approver)
                                <div class='row mb-2 mt-2 form-group' id='approver_{{$departmentData->id}}_{{$approver->status_level}}'>
                                    <div class='col-md-1  text-right'>
                                        <small class='align-items-center'>{{$approver->status_level}}</small>
                                    </div>
                                    <div class='col-md-11'>
                                        <select name='approver[]' class='form-control-sm form-control cat' required>
                                            <option value=""></option>
                                            @foreach($user->whereIn('role', ['Approver', 'Business Process Manager', 'Department Head']) as $approverData)
                                                <option value="{{ $approverData->id }}" @if($approver->user_id == $approverData->id) selected @endif>{{ $approverData->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>