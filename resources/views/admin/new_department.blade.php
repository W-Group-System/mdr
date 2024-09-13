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
                                @foreach ($user->where('role', 'Department Head') as $headData)
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>