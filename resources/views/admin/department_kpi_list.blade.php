@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="css/plugins/chosen/bootstrap-chosen.css">
    <style>
        /* Force textareas to fill the entire width of the table cell */
        #departmentKpiTable textarea.form-control {
            width: 100% !important;
            max-width: 100% !important;
            min-width: 100% !important;
            resize: vertical;
        }

        /* Make the table container scrollable */
        .table-scrollable-container {
            max-height: 600px; 
            overflow-y: auto;
            overflow-x: auto;
        }

        /* Make Status column fit just the badge size */
        #departmentKpiTable th.col-status,
        #departmentKpiTable td.col-status {
            width: 1% !important;
            white-space: nowrap !important;
            text-align: center;
        }

        /* Minimize and fit Action column tightly */
        #departmentKpiTable th.col-action,
        #departmentKpiTable td.col-action {
            width: 1% !important;
            white-space: nowrap !important;
            text-align: center !important;
            vertical-align: middle !important;
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
    </style>
@endsection

@section('content')
<div class="wrapper wrapper-content">
  <form action="{{ route('kpi.addBulk', ['department' => $department, 'month' => $selectedMonth, 'year' =>$selectedYear]) }}" method="POST">
        @csrf
        <div class="row">
            <!-- Active -->
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Active</h5>
                        <div class="pull-right">
                            <span class="label label-success">
                                As of
                                {{ date('F', mktime(0, 0, 0, (int) $selectedMonth, 1)) }} {{$selectedYear }}
                            </span>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <h1>{{ count($department_kpis->where('status', 'Active')) }}</h1>
                        <small>Total Active</small>
                    </div>
                </div>
            </div>

            <!-- Inactive -->
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Deactivate</h5>
                        <div class="pull-right">
                            <span class="label label-danger">
                                As of
                                {{ date('F', mktime(0, 0, 0, (int) $selectedMonth, 1)) }} {{$selectedYear }}
                            </span>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <h1> {{ count($department_kpis->where('status', 'Inactive')) }} </h1>
                        <small>Total Deactivate</small>
                    </div>
                </div>
            </div>
            <!-- Department KPI Header -->
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    @php
                        $departmentData = $departmentList->firstWhere('id',$department);
                    @endphp
                    <div class="ibox-title">
                        <h5>
                            Department KPI's -
                            {{ $departmentData->code ?? '' }}
                            {{ $departmentData->name ?? '--' }}
                        </h5>
                        <div class="pull-right">
                            <span class="label label-primary">
                                As of
                                {{ date('F', mktime(0, 0, 0, (int) $selectedMonth, 1)) }} {{$selectedYear }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- KPI Table -->
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <!-- Action Buttons Header -->
                    <div class="ibox-title clearfix d-flex align-items-center gap-2">
                        @if(check_access('Department KPI', 'create'))
                            <button type="submit" class="btn btn-sm btn-primary">
                                <span><i class="fa fa-save"></i></span>
                                &nbsp;Save
                            </button>
                        @endif
                        
                        <button type="button" id="addRowBtn" class="btn btn-sm btn-success">
                            <span><i class="fa fa-plus"></i></span>
                            &nbsp;Add Row
                        </button>
                        
                        <button type="button" id="duplicateBtn" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#duplicateModal">
                            <span><i class="fa fa-plus"></i></span>
                            &nbsp;Duplicate
                        </button>
                    </div>
                    <!-- End Action Buttons Header -->

                    <div class="ibox-content">
                        @include('components.error')

                        <!-- Status Filter Dropdown Control (Active selected by default) -->
                        <div class="row m-b-sm">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="statusFilter">Filter by Status:</label>
                                    <select id="statusFilter" class="form-control input-sm">
                                        <option value="">All Statuses</option>
                                        <option value="Active" selected>Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive table-scrollable-container">
                            <table
                                class="table table-striped table-bordered table-hover"
                                id="departmentKpiTable" style="width:100%;">

                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="col-action">Actions</th>
                                        <th>KPI</th>
                                        <th>Target</th>
                                        <th>Weight</th>
                                        <th>Attachment Needed</th>
                                        <th class="col-status">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($department_kpis as$department_kpi)
                                        <tr>
                                            <!-- Number Loop -->
                                            <td>{{ $loop->iteration }}</td>
                                            
                                            <td class="col-action">
                                                @if(check_access('Department KPI', 'update'))
                                                    @if($department_kpi->status != "Inactive")
                                                        <button type="button" class="btn btn-sm btn-danger action-btn" data-url="{{ url('deactivate_mdr_setup/'.$department_kpi->id) }}" title="Deactivate">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-success action-btn" data-url="{{ url('activate_mdr_setup/'.$department_kpi->id) }}" title="Activate">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    @endif
                                                @endif

                                                <!-- Hidden input to track existing IDs for updating -->
                                                <input type="hidden" name="id[]" value="{{ $department_kpi->id }}">
                                            </td>
                                            <!-- DKPI -->
                                            <td>
                                                <textarea name="name[]" class="form-control" rows="3">{{ $department_kpi->name }}</textarea>
                                            </td>
                                            <!-- Target -->
                                            <td>
                                                <textarea name="target[]" class="form-control numerical" rows="3">{{ $department_kpi->target }}</textarea>
                                            </td>
                                            <!-- Weight -->
                                            <td>
                                                <textarea name="weight[]" class="form-control numerical" rows="3">{{ $department_kpi->weight }}</textarea>
                                            </td>
                                            <!-- Attachment needed -->
                                            <td>
                                                <textarea name="attachment_description[]" class="form-control" rows="3">{{ $department_kpi->attachment_description }}</textarea>
                                            </td>
                                            <!-- Status -->
                                            <td class="col-status">
                                                <div class="label label-{{ 
                                                    $department_kpi->status == 'Inactive'
                                                        ? 'danger'
                                                        : 'primary'
                                                }}">
                                                    {{ 
                                                        $department_kpi->status == 'Inactive'
                                                            ? 'Inactive'
                                                            : 'Active'
                                                    }}
                                                </div>
                                                <input type="hidden" name="status[]" value="{{ $department_kpi->status }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End KPI Table -->
        </div>
    </form>
    @include('admin.duplicate')
</div>

@endsection

@push('scripts')
<script src="js/plugins/dataTables/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        // Custom search filter
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'departmentKpiTable') {
                return true;
            }

            const selectedStatus = $('#statusFilter').val();
            if (!selectedStatus) {
                return true; // Show all if nothing is selected
            }

            const rowNode = settings.aoData[dataIndex].nTr;
            const statusCell = $(rowNode).find('td.col-status');
            const statusText = statusCell.find('.label').text().trim() || statusCell.find('input[name="status[]"]').val();

            return statusText === selectedStatus;
        });

        const table = $('#departmentKpiTable').DataTable({
            paging: false,
            info: true,
            ordering: false,
            responsive: false,
            stateSave: false,
            autoWidth: false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [],
            columnDefs: [
                { width: '30px', targets: 0 },  
                { className: 'col-action', targets: 1 },
                { className: 'col-status', targets: 6 } 
            ]
        });

        // Trigger row number re-indexing right away based on default Active filter
        updateRowNumbers();

        // Trigger table redraw when status filter dropdown changes
        $('#statusFilter').on('change', function() {
            table.draw();
            updateRowNumbers();
            table.columns.adjust();
        });

        //Re-index sequence numbers dynamically based on visible rows
        function updateRowNumbers() {
            $('#departmentKpiTable tbody tr:visible').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Column re-adjustment after initial load
        setTimeout(function() {
            table.columns.adjust();
        }, 200);

        // Recalculate columns when sidebar toggle button is clicked
        $('.navbar-minimalize').on('click', function() {
            setTimeout(function() {
                table.columns.adjust();
            }, 300);
        });

        function observeTextarea(textarea) {
            let isResizing = false;
            const observer = new ResizeObserver(entries => {
                if (isResizing) return;
                for (let entry of entries) {
                    const newHeight = entry.target.style.height;
                    if (newHeight) {
                        isResizing = true;
                        const row = entry.target.closest('tr');
                        if (row) {
                            const rowTextareas = row.querySelectorAll('textarea.form-control');
                            rowTextareas.forEach(t => {
                                if (t !== entry.target) {
                                    t.style.height = newHeight;
                                }
                            });
                        }
                        setTimeout(() => { isResizing = false; }, 50);
                    }
                }
            });
            observer.observe(textarea);
        }

        const textareas = document.querySelectorAll('#departmentKpiTable textarea.form-control');
        textareas.forEach(textarea => observeTextarea(textarea));

        // Add row
        $('#addRowBtn').on('click', function() {
            $('#statusFilter').val('Active');
            table.draw();
            
            const newRowHtml = `
                <tr>
                    <td></td>
                    <td class="col-action">
                        <input type="hidden" name="id[]" value="">
                        <button type="button" class="btn btn-sm btn-danger remove-row-btn" title="Remove Row">
                            <span class="text-bold">X</span>
                        </button>
                    </td>
                    <td><textarea name="name[]" class="form-control" rows="3"></textarea></td>
                    <td><textarea name="target[]" class="form-control numerical" rows="3"></textarea></td>
                    <td><textarea name="weight[]" class="form-control numerical" rows="3"></textarea></td>
                    <td><textarea name="attachment_description[]" class="form-control" rows="3"></textarea></td>
                    <td class="col-status">
                        <div class="label label-primary">Active</div>
                        <input type="hidden" name="status[]" value="Active">
                    </td>
                </tr>
            `;

            const addedNode = table.row.add($(newRowHtml)).node();
            table.draw(false);
            updateRowNumbers();

            const newRowTextareas = $(addedNode).find('textarea.form-control');
            newRowTextareas.each(function() {
                observeTextarea(this);
            });

            table.columns.adjust();

            const scrollContainer = $('.table-scrollable-container')[0];
            if (scrollContainer) {
                $(scrollContainer).animate({
                    scrollTop: scrollContainer.scrollHeight
                }, {
                    duration: 300,
                    complete: function() {
                        table.columns.adjust();
                    }
                });
            }
        });

        // Remove Row
        $('#departmentKpiTable').on('click', '.remove-row-btn', function() {
            const tr = $(this).closest('tr');
            
            if (table.row(tr).length) {
                table.row(tr).remove().draw(false);
            } else {
                tr.remove();
            }
            
            updateRowNumbers();
            table.columns.adjust();
        });

        $(document).on('click', '.action-btn', function() {
            const actionUrl = $(this).data('url');
            if (typeof show === 'function') {
                show();
            }

            const form = $('<form>', {
                'action': actionUrl,
                'method': 'POST'
            });

            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': '{{ csrf_token() }}'
            }));

            $('body').append(form);
            form.submit();
        });

        // Toggle all checkboxes in the duplicate modal
        $(document).on('change', '#selectAllDuplicate', function() {
            $('#duplicateModal tbody input[type="checkbox"]').prop('checked', this.checked);
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
@endpush