<div class="modal" id="changePasswordModal{{$userData->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Change Password</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{ url('changePassword/'.$userData->id) }}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Password :
                            <input type="password" name="password" class="form-control input-sm" required>
                        </div>
                        <div class="col-md-12">
                            Confirm Password :
                            <input type="password" name="password_confirmation" class="form-control input-sm" required>
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