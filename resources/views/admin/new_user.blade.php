<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Add Users</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{ url('addUserAccounts') }}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Name :
                            <input type="text" name="name" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Email :
                            <input type="email" name="email" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Company :
                            <select name="company" id="company" class="form-control cat">
                                <option value="">-Company-</option>
                                @foreach ($company as $c)
                                    <option value="{{$c->id}}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Department :
                            <select name="department" id="department" class="form-control cat">
                                <option value="">-Department-</option>
                                @foreach ($department as $deptData)
                                    <option value="{{ $deptData->id }}">{{ $deptData->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            Role :
                            <select name="role" id="role" class="form-control cat">
                                <option value="">-Role-</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Approver">Approver</option>
                                <option value="Department Head">Department Head</option>
                                <option value="Users">Users</option>
                                <option value="Human Resources">Human Resources</option>
                            </select>
                        </div>
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