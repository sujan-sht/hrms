<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Organization:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-office"></i></span>
                </span>
                {!! Form::select('organization_id', $organizationList, $value = null, [
                    'id' => 'organization_id',
                    'class' => 'form-control organization-filter3 organization-filter2',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Biometric ID:
            {{-- <span class="text-danger">*</span> --}}
        </label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-office"></i></span>
                </span>
                {!! Form::text('biometric_id', $value = null, [
                    'placeholder' => 'Enter Biometric ID',
                    'class' => 'form-control numeric',
                    'id' => 'biometric_id',
                    $is_edit && (isset($employees) && !empty($employees->biometric_id)) ? 'readonly' : '',
                ]) !!}
            </div>
            <span class="text-danger">{{ $errors->first('biometric_id') }}</span>
            <span class="error_biometricid"></span>


        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Employee Code:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-vcard"></i></span>
                </span>
                {!! Form::text('employee_code', $is_edit ? null : $newEmpCode, [
                    'id' => 'employee_code',
                    'placeholder' => 'Enter Employee Code',
                    'class' => 'form-control',
                    $is_edit ? 'readonly' : '',
                ]) !!}
            </div>
            <span class="text-danger">{{ $errors->first('employee_code') }}</span>
            <span class="error_empid"></span>
        </div>
    </div>

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">First Name:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-user"></i></span>
                </span>
                {!! Form::text('first_name', $value = null, [
                    'id' => 'first_name',
                    'placeholder' => 'Enter First Name',
                    'class' => 'form-control',
                ]) !!}
            </div>
            <span class="text-danger">{{ $errors->first('first_name') }}</span>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Middle Name:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                </span>
                {!! Form::text('middle_name', $value = null, [
                    'id' => 'middle_name',
                    'placeholder' => 'Enter Middle Name',
                    'class' => 'form-control',
                ]) !!}
            </div>
            <span class="text-danger">{{ $errors->first('middle_name') }}</span>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Last Name:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-pencil"></i></span>
                </span>
                {!! Form::text('last_name', $value = null, [
                    'id' => 'last_name',
                    'placeholder' => 'Enter Last Name',
                    'class' => 'form-control',
                ]) !!}
            </div>
            <span class="text-danger">{{ $errors->first('last_name') }}</span>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Gender:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {{-- <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-man-woman"></i></span>
                </span> --}}
                @if ($is_edit)
                    {!! Form::select('gender', $gender, $value = null, [
                        'id' => 'gender',
                        'class' => 'form-control select-search',
                        'placeholder' => 'Select Gender',
                        'disabled',
                    ]) !!}
                    {!! Form::hidden('gender', $employees->gender) !!}
                @else
                    {!! Form::select('gender', $gender, $value = null, [
                        'id' => 'gender',
                        'class' => 'form-control select-search',
                        'placeholder' => 'Select Gender',
                    ]) !!}
                @endif
            </div>
            <span class="text-danger">{{ $errors->first('gender') }}</span>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Day Off:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            @php
                $selectedDayOff = !empty($employee_day_shift) ? $employee_day_shift : null;
            @endphp
            <div class="input-group">
                {!! Form::select(
                    'dayoff',
                    [
                        'Sunday' => 'Sunday',
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                        'N/A' => 'N/A',
                    ],
                    $selectedDayOff,
                    [
                        'id' => 'dayoff',
                        'multiple' => 'multiple',
                        'name' => 'dayoff[]',
                        'class' => 'form-control select-day-off',
                    ],
                ) !!}
            </div>
            <span class="text-danger">{{ $errors->first('dayoff') }}</span>
        </div>
    </div>
</div>

{{-- <div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Joining Date:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-tree5"></i></span>
                </span>
                @php
                    $classData = 'form-control';
                @endphp
                @if (setting('calendar_type') == 'BS')
                    @php
                        $classData1 =
                            $is_edit && auth()->user()->user_type != 'super_admin'
                                ? $classData
                                : 'form-control nepali-calendar';
                    @endphp
                    {!! Form::text('nepali_join_date', $value = null, [
                        'placeholder' => 'Enter given Date',
                        $is_edit ? 'readonly' : '',
                        'class' => $classData1,
                    ]) !!}
                @else
                    @php
                        $classData1 =
                            $is_edit && auth()->user()->user_type != 'super_admin'
                                ? $classData
                                : 'form-control daterange-single';
                    @endphp
                    {!! Form::text('join_date', $value = null, [
                        'placeholder' => 'Enter given Date',
                        $is_edit ? 'readonly' : '',
                        'class' => $classData1,
                    ]) !!}
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">End Date:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                </span>
                @if (setting('calendar_type') == 'BS')
                    {!! Form::text('nep_end_date', $value = null, [
                        'placeholder' => 'Enter end date',
                        'class' => 'form-control nepali-calendar',
                    ]) !!}
                @else
                    {!! Form::text('end_date', $value = null, [
                        'placeholder' => 'Enter end date',
                        'class' => 'form-control daterange-single',
                    ]) !!}
                @endif
            </div>
        </div>
    </div>
</div> --}}
<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Joining Date:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group row">
                <div class="col-lg-2 pr-0">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            {!! Form::select('join_date_calendar_type', ['BS' => 'BS', 'AD' => 'AD'], setting('calendar_type') ?? null, [
                                'id' => 'join_date_calendar_type',
                                'placeholder' => 'Select Calendar',
                                'style' => 'width: 100%; padding-right:0; margin-right:0;',
                            ]) !!}
                        </span>
                    </span>
                </div>
                <div class="col-lg-10 mx-0 px-0">
                    <div id="calendar_date_input">

                        <!-- Nepali Calendar Date Input -->
                        <div id="nepali-calendar-input" class="calendar-input" style="display:none;">
                            @php
                                $classData = 'form-control';
                                $classData1 =
                                    $is_edit && auth()->user()->user_type != 'super_admin'
                                        ? $classData
                                        : 'form-control nepali-calendar';
                            @endphp
                            {!! Form::text('nepali_join_date', $value = null, [
                                'placeholder' => 'Enter given Date',
                                $is_edit ? 'readonly' : '',
                                'id' => 'nepali_join_date',
                                'class' => $classData1,
                            ]) !!}
                        </div>

                        <!-- Gregorian Calendar Date Input -->
                        <div id="gregorian-calendar-input" class="calendar-input" style="display:none;">
                            @php
                                $classData1 =
                                    $is_edit && auth()->user()->user_type != 'super_admin'
                                        ? $classData
                                        : 'form-control daterange-single';
                            @endphp
                            {!! Form::text('join_date', $value = null, [
                                'placeholder' => 'Enter given Date',
                                'id' => 'join_date',
                                $is_edit ? 'readonly' : '',
                                'class' => $classData1,
                            ]) !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">End Date:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group row">
                <div class="col-lg-2 pr-0">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            {!! Form::select('end_date_calendar_type', ['BS' => 'BS', 'AD' => 'AD'], setting('calendar_type') ?? null, [
                                'id' => 'end_date_calendar_type',
                                'placeholder' => 'Select Calendar',
                                'style' => 'width: 100%; padding-right:0; margin-right:0;',
                            ]) !!}
                        </span>
                    </span>
                </div>
                <div class="col-lg-10  mx-0 px-0">
                    <div id="calendar_date_input">

                        <!-- Nepali Calendar Date Input -->
                        <div id="end-nepali-calendar-input" class="calendar-input" style="display:none;">
                            @php
                                $classData = 'form-control';
                                $classData1 =
                                    $is_edit && auth()->user()->user_type != 'super_admin'
                                        ? $classData
                                        : 'form-control nepali-calendar';
                            @endphp
                            {!! Form::text('nep_end_date', $value = null, [
                                'placeholder' => 'Enter given Date',
                                $is_edit ? 'readonly' : '',
                                'class' => $classData1,
                            ]) !!}
                        </div>

                        <!-- Gregorian Calendar Date Input -->
                        <div id="end-gregorian-calendar-input" class="calendar-input" style="display:none;">
                            @php
                                $classData1 =
                                    $is_edit && auth()->user()->user_type != 'super_admin'
                                        ? $classData
                                        : 'form-control daterange-single';
                            @endphp
                            {!! Form::text('end_date', $value = null, [
                                'placeholder' => 'Enter given Date',
                                $is_edit ? 'readonly' : '',
                                'class' => $classData1,
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    {{-- <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">DOB:<span class="text-danger">*</span> </label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                </span>
                @if (setting('calendar_type') == 'BS')
                    {!! Form::text('nep_dob', $value = null, [
                        'placeholder' => 'Enter DOB',
                        'class' => 'form-control nepali-calendar nepali-dob-calender',
                    ]) !!}
                @else
                    {!! Form::text('dob', $value = null, [
                        'placeholder' => 'Enter DOB',
                        'class' => 'form-control daterange-single',
                    ]) !!}
                @endif
            </div>
        </div>
    </div> --}}
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">DOB:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group row">
                <div class="col-lg-2 pr-0">
                    <span class="input-group-prepend">
                        <span class="input-group-text">
                            {!! Form::select('dob_calendar_type', ['BS' => 'BS', 'AD' => 'AD'], setting('calendar_type') ?? null, [
                                'id' => 'dob_calendar_type',
                                'placeholder' => 'Select Calendar',
                                'style' => 'width: 100%; padding-right:0; margin-right:0;',
                            ]) !!}
                        </span>
                    </span>
                </div>
                <div class="col-lg-10 mx-0 px-0">
                    <div id="calendar_date_input">

                        <!-- Nepali Calendar Date Input -->
                        <div id="dob-nepali-calendar-input" class="calendar-input" style="display:none;">

                            {!! Form::text('nep_dob', $value = null, [
                                'placeholder' => 'Enter given Date',
                                'class' => 'form-control nepali-calendar nepali-dob-calender',
                            ]) !!}
                        </div>

                        <!-- Gregorian Calendar Date Input -->
                        <div id="dob-gregorian-calendar-input" class="calendar-input" style="display:none;">

                            {!! Form::text('dob', $value = null, [
                                'placeholder' => 'Enter given Date',
                                'class' => 'form-control daterange-single',
                            ]) !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Blood Group:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::select('blood_group', $blood_group, $value = null, [
                    'id' => 'blood_group',
                    'class' => 'form-control select-search',
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Age:</label><label class="text-danger"></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-person"></i></span>
                </span>
                <input type="text" readonly name="age" id="age-show" class="form-control"
                    value="{{ $value = null }}">
            </div>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Telephone No:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-phone2"></i></span>
                </span>
                {!! Form::text('telephone', $value = null, [
                    'id' => 'telephone',
                    'placeholder' => 'Enter Telephone No.',
                    'class' => 'form-control',
                ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Phone (CUG No.): </label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-phone"></i></span>
                </span>
                {!! Form::text('phone', $value = null, [
                    'id' => 'phone',
                    'placeholder' => 'Enter Employee CUG No.',
                    'class' => 'form-control numeric',
                    // 'maxlength' => 10,
                ]) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Mobile 1 :</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-phone2"></i></span>
                </span>
                {!! Form::text('mobile', $value = null, [
                    'id' => 'mobile',
                    'placeholder' => 'Enter Employee Mobile 1',
                    'class' => 'form-control numeric',
                    'maxlength' => 10,
                ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Personal Email :</label>
        <div id="email_unique" class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group" id="email_unique_group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-envelop3"></i></span>
                </span>
                {!! Form::text('personal_email', $value = null, [
                    'id' => 'personal_email',
                    'placeholder' => 'Enter Employee Personal Email',
                    'class' => 'form-control',
                ]) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Official Email :</label>
        <div id="email_unique" class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group" id="email_unique_group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-envelop3"></i></span>
                </span>
                {!! Form::email('official_email', $value = null, [
                    'id' => 'official_email',
                    'placeholder' => 'Enter Official Email',
                    'class' => 'form-control',
                ]) !!}
            </div>
        </div>
    </div>

</div>

<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Citizenship No:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-vcard"></i></span>
                </span>
                {!! Form::text('citizenship_no', $value = null, [
                    'placeholder' => 'Enter Citizenship NO',
                    'class' => 'form-control',
                    $is_edit ? '' : 'id' => 'citizenship_no',
                ]) !!}
            </div>
        </div>
    </div>

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Nationality:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-user"></i></span>
                </span>
                {!! Form::text('nationality', $value = null, [
                    'placeholder' => 'Enter Nationality',
                    'class' => 'form-control',
                    $is_edit ? '' : 'id' => 'nationality',
                ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    {{-- <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Status:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::select('job_status', $jobStatusList, null, [
                    'class' => 'form-control select-search',
                    'placeholder' => 'Select Status',
                ]) !!}
            </div>
        </div>
    </div> --}}

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Passport No:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::text('passport_no', $value = null, [
                    'placeholder' => 'Enter Passport Number',
                    'class' => 'form-control',
                    $is_edit ? '' : 'id' => 'passport_no',
                ]) !!}
            </div>
        </div>
    </div>

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Religion:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::select('religion', $religionList, null, [
                    'class' => 'form-control select-search',
                    'placeholder' => 'Select Religion',
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Marital Status:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {{-- @if ($is_edit)
                    {!! Form::select('marital_status_old',$marital_status, $value = null, ['id'=>'marital_status','class'=>'form-control', 'disabled']) !!}
                @else --}}
                {!! Form::select('marital_status', $marital_status, $value = null, [
                    'id' => 'marital_status',
                    'class' => 'form-control',
                ]) !!}
                {{-- @endif --}}
            </div>
        </div>
    </div>

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Ethnicity:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::text('ethnicity', $value = null, [
                    'placeholder' => 'Enter Ethnicity',
                    'class' => 'form-control',
                    $is_edit ? '' : 'id' => 'ethnicity',
                ]) !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">National ID:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::text('national_id', $value = null, [
                    'placeholder' => 'Enter National ID',
                    'class' => 'form-control',
                    $is_edit ? '' : 'id' => 'national_id',
                ]) !!}
            </div>
        </div>
    </div>

    {{-- <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Shift:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                @php
                    $default = app\Modules\Shift\Entities\Shift::where('default', 'yes')->first();
                    $default_id = $default ? $default->id : null;
                @endphp
                {!! Form::select('shift_id', @$shiftList, $shiftGroup->group_id ?? $default_id, [
                    'id' => 'shift_id',
                    'class' => 'form-control select-search',
                    'placeholder' => 'Select Shift',
                    'required',
                ]) !!}
            </div>
        </div>
    </div> --}}

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3">Shift Group:<span class="text-danger">*</span></label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {{-- @dd($employees) --}}
                {!! Form::select('shift_group_id', @$shiftGroupList, $shiftGroup->group_id ?? null, [
                    'id' => 'shift_group_id',
                    'class' => 'form-control select-search',
                    'placeholder' => 'Select Shift Group',
                    'required',
                ]) !!}
            </div>
        </div>
    </div>
</div>



<div class="form-group row">
    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3"> Languages:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::select(
                    'languages',
                    [
                        'Nepali' => 'Nepali',
                        'English' => 'English',
                        'Hindi' => 'Hindi',
                    ],
                    explode(',', @$employees->languages),
                    [
                        'id' => 'languages',
                        'multiple' => 'multiple',
                        'name' => 'languages[]',
                        'class' => 'form-control select-languages',
                    ],
                ) !!}
            </div>
        </div>
    </div>

    <div class="col-lg-6 row">
        <label class="col-form-label col-lg-3"> Retirement Age:</label>
        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
            <div class="input-group">
                {!! Form::text('retirement_age', @$employees->retirement_age, [
                    'class' => 'form-control numeric',
                ]) !!}
            </div>
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-12 form-group-feedback form-group-feedback-right">
        <div class="form-check input-group form-check-inline">
            <input type="checkbox" name="not_affect_on_payroll" id='affectPayroll' class='form-check-input'
                {{ isset($employees->not_affect_on_payroll) && $employees->not_affect_on_payroll == 1 ? 'checked' : '' }}>
            <label class="col-form-label col-lg-11">Do not affect on payroll </label>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        var dob = $('.nepali-dob-calender').val();

        if (dob != '' && dob != null) {
            $.ajax({
                type: 'GET',
                url: "{{ route('employee.dob.convert') }}",
                data: {
                    date: dob
                },
                success: function(response) {
                    $("#age-show").val(response.age);

                }
            });
        }

        $(".nepali-dob-calender").nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            onChange: function(data) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('employee.dob.convert') }}",
                    data: {
                        date: data.bs
                    },
                    success: function(response) {

                        $("#age-show").val(response.age);

                    },
                    error: function(xhr, status, error) {
                        // Handle the error
                    }
                });


            }
        });
        $('.select-day-off').select2({
            placeholder: "Choose Day Off"
        });

        //==============select Languages========================
        $('.select-languages').select2({
            placeholder: "Choose Language",
            ajax: {
                url: "{{ route('employee.languages') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            },
            tags: true, // Allow adding new items
            createTag: function(params) {
                return {
                    id: params.term,
                    text: params.term,
                    newOption: true
                };
            },
            templateResult: function(data) {
                if (data.newOption) {
                    return `New Language Add: "${data.text}"`;
                }
                return data.text;
            }
        });
        $('.select-languages').on('select2:select', function(e) {
            var data = e.params.data;
            if (data.newOption) {
                $.ajax({
                    url: "{{ route('employee.language.create') }}",
                    method: 'POST',
                    data: {
                        name: data.text,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // var newOption = new Option(response.name, response.id, true, true);
                    },
                    error: function(error) {
                        alert('Error adding language');
                    }
                });
            }
        });
        //============end select Languages=======================



        $('#personal_email').keyup(function() {
            var personal_email = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('employee.checkAvailability') }}",
                data: "email=" + personal_email,
                success: function(res) {
                    if (res == 1) {
                        $('#email_unique').removeClass('text-success');
                        $('#email_unique').find('.form-control-feedback').remove();
                        $('#personal_email').removeClass('border-success');
                        $('#email_unique').addClass('text-danger');
                        $('#email_unique').append(
                            '<em id="personal_email-error" class="error help-block">Email Already Exists</em>'
                        );

                    } else {
                        $('#email_unique').removeClass('text-danger');
                        $('#personal_email-error').html('');
                        $('#email_unique').addClass('text-success');
                        $('#personal_email').addClass('border-success');
                    }
                }
            })
        })

        $(document).on('change', '#salutation_title', function() {
            var salutation_title = $(this).val();

            if (salutation_title === '0') {
                $(".gender").val('0');
            } else {
                $(".gender").val('1');
            }
        });
        $('.select-search').select2();

        $('#affectPayroll').click(function() {
            if ($(this).prop("checked")) {
                $(this).val(1)
            }
        });

        // On page load, check which calendar type is selected and show the appropriate input
        var selectedCalendar = $('#join_date_calendar_type').val();
        var selectedEndCalendar = $('#end_date_calendar_type').val();
        var selectedDobCalendar = $('#dob_calendar_type').val();

        // Toggle the appropriate calendar inputs based on the selected calendar type
        toggleCalendarInput(selectedCalendar, '#nepali-calendar-input', '#gregorian-calendar-input');
        toggleCalendarInput(selectedEndCalendar, '#end-nepali-calendar-input', '#end-gregorian-calendar-input');
        toggleCalendarInput(selectedDobCalendar, '#dob-nepali-calendar-input', '#dob-gregorian-calendar-input');

        // When the calendar type is changed, toggle the appropriate input field
        $('#join_date_calendar_type').change(function() {
            var selectedCalendar = $(this).val();
            toggleCalendarInput(selectedCalendar, '#nepali-calendar-input',
                '#gregorian-calendar-input');
        });

        $('#end_date_calendar_type').change(function() {
            var selectedEndCalendar = $(this).val();
            toggleCalendarInput(selectedEndCalendar, '#end-nepali-calendar-input',
                '#end-gregorian-calendar-input');
        });

        $('#dob_calendar_type').change(function() {
            var selectedDobCalendar = $(this).val();
            toggleCalendarInput(selectedDobCalendar, '#dob-nepali-calendar-input',
                '#dob-gregorian-calendar-input');
        });

        // Function to show and hide the calendar input based on selected calendar
        function toggleCalendarInput(calendarType, nepaliSelector, gregorianSelector) {
            if (calendarType === 'BS') {
                $(nepaliSelector).show();
                $(gregorianSelector).hide();
            } else {
                $(nepaliSelector).hide();
                $(gregorianSelector).show();
            }
        }

    });
</script>
<script>
    $(document).ready(function() {
        const initialOrgId = $('#organization_id').val();
        $('#organization_id').on('change', function() {
            const orgId = $(this).val();
            fetchShiftGroup(orgId);
        });

        if (initialOrgId) {
            fetchShiftGroup(initialOrgId);
        }

        function fetchShiftGroup(orgId) {
            if (!orgId) return;

            $.ajax({
                url: '{{ route('getShiftgroupByOrganization') }}',
                method: 'GET',
                data: {
                    organization_id: orgId
                },
                success: function(response) {
                    const $shiftGroup = $('#shift_group_id');
                    $shiftGroup.empty().append('<option value="">Select Shift Group</option>');

                    $.each(response.data, function(id, name) {
                        const selected = (response.selected_id == id) ? 'selected' : '';
                        $shiftGroup.append(
                            `<option value="${id}" ${selected}>${name}</option>`);
                    });
                    $shiftGroup.trigger('change.select2');
                },
                error: function() {
                    alert('Failed to load shift groups. Please try again.');
                }
            });
        }


    });
</script>
