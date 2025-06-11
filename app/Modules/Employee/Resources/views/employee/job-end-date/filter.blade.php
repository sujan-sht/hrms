<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                @if (Auth::user()->user_type == 'super_admin' ||
                        Auth::user()->user_type == 'admin' ||
                        Auth::user()->user_type == 'hr' ||
                        Auth::user()->user_type == 'division_hr')
                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Organization:</label>
                            <div class="input-group">
                                @php $selected_org_id = isset(request()->organization_id) ? request()->organization_id : null ; @endphp
                                {!! Form::select('organization_id', $organizationList, $selected_org_id, [
                                    'class' => 'form-control select2 organization-filter',
                                    'placeholder' => 'Select Organization',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Unit:</label>
                            <div class="input-group">
                                @php $selected_branch_id = isset(request()->branch_id) ? request()->branch_id : null ; @endphp
                                {!! Form::select('branch_id', $branchList, $selected_branch_id, [
                                    'class' => 'form-control select2 branch-filter',
                                    'placeholder' => 'Select Unit',
                                ]) !!}
                            </div>
                        </div>
                    </div> --}}

                    {{-- <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Department:</label>
                            <div class="input-group">
                                @php $selected_department_id= isset(request()->department_id) ? request()->department_id: null ; @endphp
                                {!! Form::select('department_id', $departmentList, $selected_department_id, [
                                    'id' => 'departmentId',
                                    'placeholder' => 'Select Department',
                                    'class' => 'form-control select2',
                                ]) !!}

                            </div>
                        </div>
                    </div> --}}

                    <div class="col-md-3 employee_id">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Select Employee:</label>
                            <div class="input-group">
                                @php $selected_emp_id = isset(request()->employee_id) ? request()->employee_id : null ; @endphp
                                {!! Form::select('employee_id', $employeeList, $selected_emp_id, [
                                    'class' => 'form-control select-search employee-filter',
                                    'placeholder' => 'Select Employee',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">Job Type</label>
                            <div class="input-group">
                                {!! Form::select('job_type', $jobTypeList, $value = request('job_type') ?: null, [
                                    'placeholder' => 'Select Job Type',
                                    'class' => 'form-control select2 select-search',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endif

                @if (setting('calendar_type') == 'BS')
                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">From End Date:</label>
                            <div class="input-group">
                                {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: null, [
                                    'placeholder' => 'e.g : YYYY-MM-DD',
                                    'class' => 'form-control nepali-calendar',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">To End Date:</label>
                            <div class="input-group">
                                {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: null, [
                                    'placeholder' => 'e.g : YYYY-MM-DD',
                                    'class' => 'form-control nepali-calendar',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-3">
                        <div class="form-group mb-0 pt-1 pb-1 pl-3 pr-3">
                            <label class="d-block font-weight-semibold">End Date Range:</label>
                            <div class="input-group">
                                {!! Form::text('date_range', $value = request('date_range') ?: null, [
                                    'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                    'class' => 'form-control endDateRange',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-1" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>

                <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                        class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        </form>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('.endDateRange').daterangepicker({
            parentEl: '.content-inner',
            autoUpdateInput: false,
            showDropdowns: true,
            // minDate: minDate,
            // maxDate: maxDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                'YYYY-MM-DD'));
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
