<div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h5 class="modal-title text-left">
                    Duplicate Department KPI
                </h5>
            </div>
            <form action="{{ route('kpi.duplicate', ['department' => $department]) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Target Month and Year Selection Controls -->
                    <div class="row m-b-md">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="target_month">Month:</label>
                                <select name="target_month" id="target_month" class="form-control" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ sprintf('%02d', $m) }}" {{ $m == (int)$selectedMonth ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="target_year">Year:</label>
                                <select name="target_year" id="target_year" class="form-control" required>
                                    @php $currentYear = date('Y'); @endphp
                                    @for ($y = $currentYear - 2; $y <= $currentYear + 5; $y++)
                                        <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted">Select the KPIs you want to duplicate:</p>
                    
                    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">
                                        <input type="checkbox" id="selectAllDuplicate" checked>
                                    </th>
                                    <th>KPI Name</th>
                                    <th>Target</th>
                                </tr>
                            </thead>
                            <tbody>
                               <!-- Display only in the duplicate those active status -->
                                @forelse ($department_kpis->where('status', 'Active') as $kpi)
                                    <tr>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <input type="checkbox" name="selected_kpis[]" value="{{ $kpi->id }}" checked>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            {{ $kpi->name }}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            {{ strlen($kpi->target) > 50 ? substr($kpi->target, 0, 50) . '...' : $kpi->target }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">No KPIs available to duplicate.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Duplicate Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>