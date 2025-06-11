<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ $route }}" method="GET">
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">Leave Year <span class="text text-danger">*</span></label>
                    {!! Form::select('leave_year_id', $leaveYearList, $value = request('leave_year_id') ?: null, [
                        'class' => 'form-control select-search leave_year_id',
                        'id' => 'leave_year_id',
                        'required',
                    ]) !!}
                </div>
                @if (setting('calendar_type') == 'BS')
                    <div class="col-md-3">
                        <label class="d-block font-weight-semibold">From Date:</label>
                        <div class="input-group">
                            {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar from_nep_date',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="d-block font-weight-semibold">To Date:</label>
                        <div class="input-group">
                            {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar to_nep_date',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>
                @else
                    <div class="col-md-3 mb-2">
                        <label for="example-email" class="form-label">Date Range</label>
                        {!! Form::text('date_range', $value = request('date_range') ?: null, [
                            'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                            'class' => 'form-control leaveDateRange',
                            'autocomplete' => 'on',
                        ]) !!}
                    </div>
                @endif


                @if (Auth::user()->user_type == 'super_admin' ||
                        Auth::user()->user_type == 'admin' ||
                        Auth::user()->user_type == 'hr' ||
                        Auth::user()->user_type == 'division_hr')
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Organization</label>
                        {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: null, [
                            // 'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search organization-filter organization-filter2',
                        ]) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Unit</label>
                        {!! Form::select('branch_id', $branchList, $value = request('branch_id') ?: null, [
                            'placeholder' => 'Select Unit',
                            'class' => 'form-control select-search branch-filter',
                        ]) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Employee</label>
                        {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select-search employee-filter',
                        ]) !!}
                    </div>
                @elseif (Auth::user()->user_type == 'supervisor')
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Employee</label>
                        {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select-search',
                        ]) !!}
                    </div>
                @endif

                <div class="col-md-3 mb-2">
                    <label class="form-label">Leave Type</label>
                    {!! Form::select('leave_type_id', $leaveTypeList, $value = request('leave_type_id') ?: null, [
                        'placeholder' => 'Select Leave Type',
                        'class' => 'form-control select-search leave-type-filter',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Leave Category</label>
                    {!! Form::select('leave_kind', $leaveKindList, $value = request('leave_kind') ?: null, [
                        'placeholder' => 'Select Leave Category',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Status</label>
                    {!! Form::select('status', $statusList, $value = request('status') ?: null, [
                        'placeholder' => 'Select Status',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() . '?leave_year_id=' . getCurrentLeaveYearId() }}"
                    class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {

        dateRange = "{{ request('date_range') }}";

        $('#leave_year_id').on('change', function(e, data) {
            var id = $(this).val();
            if (data && typeof data.checkDateRange !== 'undefined') {
                $('.leaveDateRange').val(dateRange);
            } else {
                $('.leaveDateRange').val('');
            }
            $('.organization-filter').trigger('change');

            $.get('/leaveYearSetup/getLeaveYearById/' + id, function(response) {
                    start_date = response.data.start_date_english;
                    end_date = response.data.end_date_english;
                    dateRangePicker(start_date, end_date)
                })
                .fail(function(xhr, status, error) {
                    console.error(error);
                });

        });

        $('#leave_year_id').trigger('change', [{
            checkDateRange: true
        }]);

        function dateRangePicker(minDate, maxDate) {
            $('.leaveDateRange').daterangepicker({
                parentEl: '.content-inner',
                autoUpdateInput: false,
                showDropdowns: true,
                minDate: minDate,
                maxDate: maxDate,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
            }).on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        }

    })
</script>
