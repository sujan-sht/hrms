<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('leave.report') }}" method="GET">
            <div class="row">
                @if (setting('calendar_type') == 'BS')
                    <div class="col-md-3 mb-2">
                        <label class="d-block font-weight-semibold">From Date:</label>
                        <div class="input-group">
                            {!! Form::text('from_nep_date', $value = request('from_nep_date') ?: $from_nep_date, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar ',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="d-block font-weight-semibold">To Date:</label>
                        <div class="input-group">
                            {!! Form::text('to_nep_date', $value = request('to_nep_date') ?: $to_nep_date, [
                                'placeholder' => 'e.g : YYYY-MM-DD',
                                'class' => 'form-control nepali-calendar',
                                'autocomplete' => 'on',
                            ]) !!}
                        </div>
                    </div>
                @else
                    <div class="col-md-3 mb-2">
                        <label for="example-email" class="form-label">Date Range</label>
                        {!! Form::text('date_range', $value = request('date_range') ?: $date_range, [
                            'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                            'class' => 'form-control daterange-buttons',
                            'autocomplete' => 'on',
                        ]) !!}
                    </div>
                @endif
                {{-- <div class="col-md-3 mb-2">
                    <label class="form-label">Employee</label>
                    {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ? : null, ['placeholder'=>'Select Employee', 'class'=>'form-control select2']) !!}
                </div> --}}

                @if (Auth::user()->user_type == 'super_admin' || Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'hr')
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Organization <span class="text-danger">*</span></label>
                        {!! Form::select('organization_id', $organizationList, $value = request('organization_id') ?: $organization_id, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control select-search organization-filter organization-filter2',
                            'required',
                        ]) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Unit</label>
                        {!! Form::select('branch_id', $branchList, $value = request('branch_id') ?: null, [
                            'placeholder' => 'Select Unit',
                            'class' => 'form-control select2 branch-filter',
                        ]) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Employee</label>
                        {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select2 employee-filter',
                        ]) !!}
                    </div>
                @elseif (Auth::user()->user_type == 'supervisor')
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Employee</label>
                        {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                            'placeholder' => 'Select Employee',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                @endif

            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                    <i class="icons icon-reset mr-1"></i>Reset
                </a>
            </div>
        </form>

    </div>
</div>
