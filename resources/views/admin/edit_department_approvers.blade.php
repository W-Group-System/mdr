<div class="modal" id="edit{{ $department_approver->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Edit department approvers</h5>
            </div>
            <form role="form" method="post" action="{{url('update-department-approvers/'.$department_approver->id)}}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            User :
                            <select data-placeholder="Select approver" name="approver" class="form-control cat" required>
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @if($user->id == $department_approver->user_id) selected @endif>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12">
                            Level :
                            <input type="number" name="level" class="form-control level" data-id="{{ $department_approver->id }}" value="{{ $department_approver->status_level }}" required>
                        </div>
                        <div class="col-lg-12"
                            id="companySection{{ $department_approver->id }}"
                            @if($department_approver->status_level != 1) style="display:none;" @endif>

                            Department Companies :

                            @php
                                $selectedCompanies = $department_approver->company_id
                                    ? explode(',', $department_approver->company_id)
                                    : [];
                            @endphp
                            <select name="companies[]" class="cat" multiple>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}"
                                        {{ in_array($company->id, $selectedCompanies) ? 'selected' : '' }}>
                                        {{ $company->name }}
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