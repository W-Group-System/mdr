<div class="modal" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-left">Add MDR Setup</h5>
            </div>
            <form role="form" method="post" id="addForm" action="{{url('addDepartmentKpi')}}" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            Departments :
                            <select name="department" id="department" class="form-control">
                                <option value="">-Departments-</option>
                                @foreach ($departmentList as $departmentData)
                                    <option value="{{ $departmentData->id }}">{{ $departmentData->code.' - '.$departmentData->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12">
                            Department KPI :
                            <textarea name="kpiName" id="" class="form-control" cols="30" rows="10" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    Target :
                                    <textarea name="target" id="" class="form-control numerical" cols="30" rows="10" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    Weight :
                                    <textarea name="weight" id="" class="form-control numerical" cols="30" rows="10" required></textarea>
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-12">
                            Attachment Needed
                            <input name="attachment_description" id="attachment_description" class="form-control">
                            <small id="descError" class="text-danger" style="display:none;">Max 50 characters allowed.</small>
                        </div>


                        <div class="col-md-6">
                            Month:
                            <select name="month" id="month" class="form-control" required>
                                @foreach (range(1, 12) as $m)
                                    @php
                                        $monthValue = str_pad($m, 2, '0', STR_PAD_LEFT);
                                        $monthName = date('F', mktime(0, 0, 0, $m, 1)); 
                                    @endphp
                                    <option value="{{ $monthValue }}"
                                        {{ date('m') == $monthValue ? 'selected' : '' }}>
                                        {{ $monthName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="col-md-6">
                            Year:
                            <select name="year" id="year" class="form-control" required>
                                @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}" {{ now()->year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('attachment_description');
        const error = document.getElementById('descError');

        input.addEventListener('input', function() {
            if (this.value.length > 50) {
                error.style.display = 'inline';
            } else {
                error.style.display = 'none';
            }
        });
        
        // Numerical input restriction
        $(document).on('keypress', 'textarea.numerical', function (e) {
            // Allow numbers
            if (e.which >= 48 && e.which <= 57) {
                return true;
            }
            // Allow decimal point
            if (e.which === 46) {
                return true;
            }
            // Allow Enter
            if (e.which === 13) {
                return true;
            }
            e.preventDefault();
        });

        // Prevent invalid characters when pasting
        $(document).on('input', 'textarea.numerical', function () {
            let value = $(this).val();
            // Keep only numbers and decimal point
            value = value.replace(/[^0-9.]/g, '');

            // Allow only one decimal point
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            $(this).val(value);
        });
    });
</script>