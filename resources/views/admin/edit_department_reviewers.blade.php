<div class="modal" id="editreviewer{{ $department_reviewer->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Edit department reviewer</h5>
            </div>
            <form role="form" method="post" action="{{url('update-department-reviewers/'.$department_reviewer->id)}}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            User :
                            <select data-placeholder="Select reviewer" name="reviewer" class="form-control cat" required>
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if($user->id == $department_reviewer->user_id) selected @endif>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12"
                            id="departmentSection{{ $department_reviewer->id }}">

                            Department :

                            @php
                                $selectedDepartments = $department_reviewer->department_id
                                    ? explode(',', $department_reviewer->department_id)
                                    : [];
                            @endphp
                            <select name="departments[]" class="cat" multiple>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ in_array($department->id, $selectedDepartments) ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
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