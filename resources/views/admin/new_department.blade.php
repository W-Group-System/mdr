<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Add Department</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{ url('addDepartments') }}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Department Code :
                            <input type="text" name="departmentCode" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Department Name :
                            <input type="text" name="departmentName" class="form-control input-sm" required> 
                        </div>
                        <div class="col-md-12">
                            Department Head :
                            <select name="departmentHead" id="departmentHead" class="form-control cat">
                                <option value="">-Department Head-</option>
                                @foreach ($user->whereIn('role', ['Department Head', 'Business Process Manager']) as $headData)
                                    <option value="{{ $headData->id }}">{{ $headData->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Target Date :
                            <select name="targetDate" id="targetDate" name="targetDate" class="form-control cat" required>
                                <option value="">- Target Date -</option>
                                @foreach ($targetDate as $key=>$td)
                                    <option value="{{$key}}">{{$td}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-md-12">
                            <hr>
                            <button type="button" class="btn btn-xs btn-primary addApprover" onclick="add_approver()">
                                <i class="fa fa-plus-square-o"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-danger deleteApprover" onclick="delete_approver()">
                                <i class="fa fa-minus-square-o"></i>
                            </button>
                            <span>&nbsp;</span>
                            <div class='approvers-data form-group'>
                                <div class='row mb-2 mt-2 form-group ' id='approver_1'>
                                    <div class='col-md-1  text-right'>
                                        <small class='align-items-center'>1</small>
                                    </div>
                                    <div class='col-md-11'>
                                        <select name='approver[]' class='form-control-sm form-control cat' required>
                                            <option value=""></option>
                                            @foreach($user->whereIn('role', ['Approver', 'Business Process Manager', 'Department Head']) as $approverData)
                                                <option value="{{ $approverData->id }}">{{ $approverData->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <script>
    function add_approver()
    {
        var lastItemID = $('.approvers-data').children().last().attr('id');
        var last_id = lastItemID.split("_");
        var finalLastId = parseInt(last_id[1]) + 1;
        
        $('.approvers-data').append(`
            <div class='row mb-2 mt-2 form-group ' id='approver_${finalLastId}'>
                <div class='col-md-1  text-right'>
                    <small class='align-items-center'>${finalLastId}</small>
                </div>
                <div class='col-md-11'>
                    <select name='approver[]' class='form-control-sm form-control cat' required>
                        <option value=""></option>
                        @foreach($user->whereIn('role', ['Approver', 'Business Process Manager', 'Department Head']) as $approverData)
                            <option value="{{ $approverData->id }}" {{ $approverData->id == $approver->user_id ? 'selected' : '' }}>{{ $approverData->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        `)

        $(".cat").chosen({width: "100%"});
        
    }

    function add_edit_approver(deptId)
    {
        var lastItemID = $('.approvers-data-'+deptId).children().last().attr('id');
        
        if(lastItemID)
        {
            var last_id = lastItemID.split("_");
            finalLastId = parseInt(last_id[2]) + 1;
        }
        else
        {
            var finalLastId = 1;
        }

        $('.approvers-data-'+deptId).append(`
            <div class='row mb-2 mt-2 form-group ' id='approver_${deptId}_${finalLastId}'>
                <div class='col-md-1  text-right'>
                    <small class='align-items-center'>${finalLastId}</small>
                </div>
                <div class='col-md-11'>
                    <select name='approver[]' class='form-control-sm form-control cat' required>
                        <option value=""></option>
                        @foreach($user->whereIn('role', ['Approver', 'Business Process Manager', 'Department Head']) as $approverData)
                            <option value="{{ $approverData->id }}" {{ $approverData->id == $approver->user_id ? 'selected' : '' }}>{{ $approverData->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        `)

        $(".cat").chosen({width: "100%"});
    }

    function delete_approver()
    {
        if($('.approvers-data .row').length > 1)
        {
            var lastItemID = $('.approvers-data').children().last().attr('id');
            $('#'+lastItemID).remove();
        }
    }

    function delete_edit_approver(deptId)
    {
        if($('.approvers-data-'+deptId+' .row').length > 1)
        {
            var lastItemID = $('.approvers-data-'+deptId).children().last().attr('id');
            
            $('#'+lastItemID).remove();
        }
    }
</script> --}}