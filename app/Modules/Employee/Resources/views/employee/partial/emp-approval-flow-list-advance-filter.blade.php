
<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                {{-- @if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'hr') --}}
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Organization</label>
                            @php
                                if (isset($_GET['organization_id'])) {
                                    $orgValue = $_GET['organization_id'];
                                } else {
                                    $orgValue = null;
                                }
                            @endphp
                            {!! Form::select('organization_id', $organizationList, $value = $orgValue, [
                                'placeholder' => 'Select Organization',
                                'class' => 'form-control select-search organization-filter',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="example-email" class="form-label">Employee</label>
                            @php
                                if (isset($_GET['employee_id'])) {
                                    $employeeValue = $_GET['employee_id'];
                                } else {
                                    $employeeValue = null;
                                }
                            @endphp
                            {!! Form::select('employee_id', $employeeList, $value = $employeeValue, [
                                'placeholder' => 'Select Employee',
                                'class' => 'form-control select-search employee-filter',
                            ]) !!}
                        </div>
                    </div>
                {{-- @endif --}}

                {{-- <div class="col-md-3">
                    <div class="mb-3">
                        <label for="example-email" class="form-label">Type</label>
                        @php
                            if (isset($_GET['type'])) {
                                $selectedType = $_GET['type'];
                            } else {
                                $selectedType = null;
                            }
                        @endphp
                        {!! Form::select('type', $type, $value = $selectedType, [
                            'placeholder' => 'Select Type',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                </div> --}}

                @if (setting('calendar_type') == 'BS')
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="d-block font-weight-semibold">From Date:</label>
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
                        <div class="mb-3">
                            <label class="d-block font-weight-semibold">To Date:</label>
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
                        <div class="mb-3">
                            <label class="d-block font-weight-semibold">Date Range:</label>
                            <div class="input-group">
                                {!! Form::text('date_range', $value = request('date_range') ?: null, [
                                'placeholder' => 'e.g : YYYY-MM-DD to YYYY-MM-DD',
                                'class' => 'form-control dateRange',
                                'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="col-md-3 mb-2">
                    <label class="form-label">Job Type</label>
                    {!! Form::select('job_type', $jobTypeList, $value = request('job_type') ?: null, [
                        'placeholder' => 'Select Job Type',
                        'class' => 'form-control select-search'
                    ]) !!}
                </div>
                
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-1" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>

                <a href="{{ request()->url('') }}" class="btn bg-secondary text-white"><i
                        class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        </form>

    </div>
</div>
<script>
    $(document).ready(function() {
        $('.dateRange').daterangepicker({
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

@section('script')
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

@endsection
