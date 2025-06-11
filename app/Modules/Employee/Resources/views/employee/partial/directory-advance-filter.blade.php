<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>

<div class="card">
    <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
        <h5 class="card-title text-uppercase font-weight-semibold">Advance Filter</h5>
    </div>
    @php

        $calendar_type =
            !empty($search_value) && isset($search_value['calendar_type']) ? $search_value['calendar_type'] : 'nep';
    @endphp
    <div class="card-body">
        <form id="directorySearchForm">
            <input type="hidden" name="switch_view" id="switch_view" value="list-view">
            @if (Auth::user()->user_type == 'super_admin' ||
                    Auth::user()->user_type == 'admin' ||
                    Auth::user()->user_type == 'hr' ||
                    Auth::user()->user_type == 'division_hr')
                @include('employee::employee.partial.filter-columns-for-role')
                <div class="moreFieldsAdd" style="display: none;">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="d-block font-weight-semibold">Calendar Type:</label>
                            <div class="input-group">
                                <select name="calendar_type" id="calendarType" class="form-control">
                                    <option value="eng" {{ $calendar_type == 'eng' ? 'selected' : '' }}>English
                                    </option>
                                    <option value="nep" {{ $calendar_type == 'nep' ? 'selected' : '' }}>Nepali
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="example-email" class="form-label">Employee Code</label>
                                @php
                                    if (isset($_GET['employee_code'])) {
                                        $employeeCode = $_GET['employee_code'];
                                    } else {
                                        $employeeCode = null;
                                    }
                                @endphp
                                {!! Form::text('employee_code', $value = $employeeCode, [
                                    'placeholder' => 'Enter Employee Code',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3 list-view-only-columns">
                            <div class="form-group">
                                <label class="d-block font-weight-semibold">Columns:</label>
                                <div class="input-group">
                                    @php $columns = isset(request()->columns) ? request()->columns : null; @endphp
                                    {!! Form::select('columns[]', $column_lists, $value = $columns, [
                                        'class' => 'form-control multiselect-filtering',
                                        'multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mt-2">
                            <label for="example-email" class="form-label text-center">Age Range</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="age_from" class="form-control">
                                        <option value="" selected>From Age</option>
                                        @foreach (range(1, 100) as $item)
                                            <option
                                                value="{{ $item }}"{{ request('age_from') == $item ? 'selected' : '' }}>
                                                {{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select name="age_to" class="form-control">
                                        <option value="" selected>To Age</option>
                                        @foreach (range(1, 100) as $item)
                                            <option value="{{ $item }}"
                                                {{ request('age_to') == $item ? 'selected' : '' }}>
                                                {{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="example-email" class="form-label">Phone</label>
                                @php
                                    if (isset($_GET['phone'])) {
                                        $employeeValue = $_GET['phone'];
                                    } else {
                                        $employeeValue = null;
                                    }
                                @endphp
                                {!! Form::number('phone', $value = $employeeValue, [
                                    'placeholder' => 'Enter Phone',
                                    'class' => 'form-control',
                                    'id' => 'phone',
                                ]) !!}
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-3 mt-2">
                            <label for="example-email" class="form-label text-center">Joining Date Range</label>
                            <div class="row nepaliCalendar">
                                <div class="col-md-6">
                                    {!! Form::text('tenure_nep_date[]', $value = request('nep_tenure_from') ?? null, [
                                        'class' => 'form-control nepali-calendar',
                                        'placeholder' => 'From',
                                    ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::text('tenure_nep_date[]', $value = request('nep_tenure_to') ?? null, [
                                        'class' => 'form-control nepali-calendar',
                                        'placeholder' => 'To',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row englishCalendar">
                                <div class="col-md-6">
                                    {!! Form::text('tenure_eng_date[]', $value = request('tenure_from') ?? null, [
                                        'class' => 'form-control daterange-single',
                                        'placeholder' => 'From',
                                    ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::text('tenure_eng_date[]', $value = request('tenure_to') ?? null, [
                                        'class' => 'form-control daterange-single',
                                        'placeholder' => 'To',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                        @if (Auth::user()->user_type == 'employee' || Auth::user()->user_type == 'supervisor')
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="example-email" class="form-label">Search By Keyword </label>
                                    @php
                                        if (isset($_GET['name'])) {
                                            $employeeValue = $_GET['name'];
                                        } else {
                                            $employeeValue = null;
                                        }
                                    @endphp
                                    {!! Form::text('name', $value = $employeeValue, ['placeholder' => 'Enter keyword', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                @php
                                    if (isset($_GET['job_status'])) {
                                        $statusValue = $_GET['job_status'];
                                    } else {
                                        $statusValue = null;
                                    }
                                @endphp
                                {!! Form::select('job_status', $jobStatusList, $value = $statusValue, [
                                    'placeholder' => 'Select Status',
                                    'class' => 'form-control select2',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            {{-- <div class="mb-3">
                                <label class="form-label">GPA</label>
                                @php
                                    if (isset($_GET['gpa_enable'])) {
                                        $gpaValue = $_GET['gpa_enable'];
                                    } else {
                                        $gpaValue = null;
                                    }
                                @endphp
                                {!! Form::select('gpa_enable', [11 => 'Yes', 10 => 'No'], $value = $gpaValue, [
                                    'placeholder' => 'Select Option',
                                    'class' => 'form-control select2',
                                ]) !!}
                            </div> --}}
                        </div>
                        <div class="col-md-3">
                            <label for="example-email" class="form-label text-center">Date Range</label>
                            <div class="row nepaliCalendar">
                                <div class="col-md-6">
                                    {!! Form::text('nepDateRange[]', $value = request('nep_from_date') ?? null, [
                                        'class' => 'form-control nepali-calendar',
                                        'placeholder' => 'From',
                                    ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::text('nepDateRange[]', $value = request('nep_to_date') ?? null, [
                                        'class' => 'form-control nepali-calendar',
                                        'placeholder' => 'To',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="row englishCalendar">
                                <div class="col-md-6">
                                    {!! Form::text('engDateRange[]', $value = request('from_date') ?? null, [
                                        'class' => 'form-control daterange-single',
                                        'placeholder' => 'From',
                                    ]) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::text('engDateRange[]', $value = request('to_date') ?? null, [
                                        'class' => 'form-control daterange-single',
                                        'placeholder' => 'To',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="moreFieldsAdd" style="display: none;">
                <div class="row">
                    {{-- <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">GMI</label>
                            @php
                                if (isset($_GET['gmi_enable'])) {
                                    $gmiValue = $_GET['gmi_enable'];
                                } else {
                                    $gmiValue = null;
                                }
                            @endphp
                            {!! Form::select('gmi_enable', [11 => 'Yes', 10 => 'No'], $value = $gmiValue, [
                                'placeholder' => 'Select Option',
                                'class' => 'form-control select2',
                            ]) !!}
                        </div>
                    </div> --}}
                    <div class="col-md-3 mb-1">
                        <label for="example-email" class="form-label">Role</label>

                        <select name="role_name" id="" class="form-control">
                            <option value="">Select Role</option>

                            @foreach ($rolesLists as $role)
                                <option value="{{ $role->user_type }}"
                                    {{ Request::get('role_name') == $role->user_type ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="example-email" class="form-label">State/Province</label>
                        @php
                            if (isset($_GET['permanentprovince'])) {
                                $provinceValue = $_GET['permanentprovince'];
                            } else {
                                $provinceValue = null;
                            }
                        @endphp
                        {!! Form::select('permanentprovince', $state, $value = $provinceValue, [
                            'placeholder' => 'Select State/Province',
                            'class' => 'form-control select2',
                        ]) !!}
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Job Type</label>
                        @php $selected_job_type = isset(request()->job_type) ? request()->job_type : null ; @endphp
                        {!! Form::select('job_type[]', $jobTypeList, $selected_job_type, [
                            // 'placeholder' => 'Select Job Type',
                            'class' => 'form-control multiselect-filtering',
                            'multiple',
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mt-4 d-none" id="bulk_active">
                    <input type="checkbox" name="active_user" id="active_user"> <span>Multi-User Select</span>
                </div>
            </div>
            <!-- Read More / Read Less button -->
            <div class="d-flex justify-content-center mt-3">
                <button type="button" id="toggleButton" class="btn btn-secondary"><i class="icon-arrow-down16"></i>
                    Show
                    More Filters</button>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button class="btn bg-yellow mr-2" type="submit">
                    <i class="icons icon-filter3 mr-1"></i>Filter
                </button>
                <a href="{{ url()->current() }}" class="btn bg-secondary text-white"><i
                        class="icons icon-reset mr-1"></i>Reset</a>
            </div>
        </form>

    </div>
</div>
<script type="text/javascript">
    $('#calendarType').change(function() {
        var calendar_type = $(this).val();
        if (calendar_type == 'nep') {
            $('.englishCalendar').hide();
            $('.nepaliCalendar').show();

        } else {
            $('.englishCalendar').show();
            $('.nepaliCalendar').hide();
        }
    });

    $('#calendarType').trigger('change');
</script>
