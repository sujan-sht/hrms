<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0">
        <h5 class="card-title text-light text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        {!! Form::open([
            'method' => 'GET',
            'route' => ['warning.index'],
            'class' => 'form-horizontal',
            'role' => 'form',
        ]) !!}
        <div class="row">
            @if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'hr')
                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="col-form-label">Organization:</label>
                        {!! Form::select('organization_id', $organizationList, $value = $filter['organization_id'] ?? null, [
                            'placeholder' => 'Select Organization',
                            'class' => 'form-control',
                            'id' => 'organizationId',
                        ]) !!}

                    </div>
                </div>
                
            @endif
            @if (auth()->user()->user_type != 'employee')

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="col-form-label">Employee :</label>

                    {!! Form::select(
                        'employee_id[]',
                        isset($employeeList) ? $employeeList : [],
                        isset($employee_id) ? $employee_id : '',
                        [
                            'id' => 'employeeId',
                            'class' => 'form-control multiselect-select-all-filtering',
                            'multiple' => 'multiple',
                        ],
                    ) !!}
                </div>
            </div>
            @endif
            
            @if (setting('calendar_type') == 'BS')
                <div class="col-md-3 mt-2">
                    <label class="d-block font-weight-semibold">From Date:</label>
                    <div class="input-group">
                        {!! Form::text('from_date', $value = request('from_date') ?: null, [
                            'placeholder' => 'e.g : YYYY-MM-DD',
                            'class' => 'form-control nepali-calendar from_date',
                            'autocomplete' => 'on',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3 mt-2">
                    <label class="d-block font-weight-semibold">To Date:</label>
                    <div class="input-group">
                        {!! Form::text('to_date', $value = request('to_date') ?: null, [
                            'placeholder' => 'e.g : YYYY-MM-DD',
                            'class' => 'form-control nepali-calendar to_date',
                            'autocomplete' => 'on',
                        ]) !!}
                    </div>
                </div>
            @else
                <div class="col-md-3 mt-2">
                    <label for="example-email" class="form-label">Date Range</label>
                    {!! Form::text('date_range', $value = request('date_range') ?: null, [
                        'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                        'class' => 'form-control daterange-buttons',
                        'autocomplete' => 'on',
                    ]) !!}
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn bg-yellow mr-1"><i class="icons icon-filter3 mr-1"></i>Filter</button>
            <a href="{{ route('warning.index') }}" class="btn bg-secondary text-white"><i
                    class="icons icon-reset mr-1"></i>Reset</a>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $('.select2').select2();
</script>
