<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    {!! Form::open([
        'method' => 'GET',
        'route' => ['substituteLeave.index'],
        'class' => 'form-horizontal',
        'role' => 'form',
    ]) !!}
    <div class="card-body">
        <div class="row">
            @if (leaveYearSetup('calendar_type') == 'BS')
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
                        'class' => 'form-control daterange-buttons',
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
                    {!! Form::select('organization_id[]', $organizationList, $value = request('organization_id') ?: null, [
                        'placeholder' => 'Select Organization',
                        'class' => 'form-control multiselect-filtering',
                        'multiple' => 'multiple',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Unit</label>
                    {!! Form::select('branch_id[]', $branchList, $value = request('branch_id') ?: null, [
                        'placeholder' => 'Select Unit',
                        'class' => 'form-control multiselect-filtering',
                        'multiple' => 'multiple',
                    ]) !!}
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Employee</label>
                    {!! Form::select('employee_id[]', $employeeList, $value = request('employee_id') ?: null, [
                        'placeholder' => 'Select Employee',
                        'class' => 'form-control multiselect-filtering',
                        'multiple',
                    ]) !!}
                </div>
            @elseif (Auth::user()->user_type == 'supervisor')
                <div class="col-md-3 mb-2">
                    <label class="form-label">Employee</label>
                    {!! Form::select('employee_id[]', $employeeList, $value = request('employee_id') ?: null, [
                        'placeholder' => 'Select Employee',
                        'class' => 'form-control multiselect-filtering',
                        'multiple',
                    ]) !!}
                </div>
            @endif

        </div>
        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                    class="icons icon-reset mr-1"></i>Reset</a>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<script>
    $('.select2').select2();
</script>
