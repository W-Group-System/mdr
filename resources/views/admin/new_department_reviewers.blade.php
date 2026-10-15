<div class="modal" id="newReviewers">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Add new department reviewer</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{url('store-department-reviewers')}}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            User :
                            <select data-placeholder="Select reviewer" name="reviewer" class="form-control cat" required>
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12">
                            Department :
                            <select name="departments[]" data-placeholder="Select" class="form-control cat" multiple>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">
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

