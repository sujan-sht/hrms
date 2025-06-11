<div class="row">
    <div class="col-md-12 anonymous-section">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Do you want to remain anonymous? <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <div class="p-1 rounded">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input is_anonymous chooseAnonymous"
                                                id="radio2" name="is_anonymous" type="radio" value="11">
                                            <label class="custom-control-label" for="radio2">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input class="custom-control-input is_anonymous chooseAnonymous"
                                                id="radio1" name="is_anonymous" type="radio" value="10">
                                            <label class="custom-control-label" for="radio1">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <div class="input-group">

                                        {{ Form::radio('is_anonymous', 11, false, ['class' => 'custom-control-input chooseAnonymous', 'id' => 'radio1']) }}
                                        <label class="custom-control-label" for="radio1">{{ 'Yes' }}</label>
                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        {{ Form::radio('is_anonymous', 10, false, ['class' => 'custom-control-input chooseAnonymous', 'id' => 'radio2']) }}
                                        <label class="custom-control-label" for="radio2">{{ 'No' }}</label>
                                    </div>
                                </div>

                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Subject :<span class="text-danger">
                                    *</span></label>
                            @php
                                $subjectLists = [
                                    1 => 'Grievances',
                                    2 => 'Disciplinary Action',
                                    3 => 'Suggestions',
                                    4 => 'Others',
                                ];
                            @endphp
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('subject_type', $subjectLists, null, [
                                        'class' => 'form-control select-search',
                                        'placeholder' => 'Select Subject',
                                        'id' => 'subject_type',
                                    ]) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Attachment:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::file('attachment', ['id' => 'attachment', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <fieldset id="grievances-detail" class="grievances-detail d-none">
                    <legend class="text-uppercase font-size-sm font-weight-bold">GRIEVANCES DETAILS</legend>
                    <div class="form-group row">
                        <div class="col-lg-12 mb-1">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Subject related to Grievances:<span
                                        class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('subject[related_grievances]', null, [
                                            'class' => 'form-control',
                                            'id' => 'related_grievances',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-1">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Grievances Details :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::textarea('subject[detail]', $value ?? null, ['class' => 'form-control', 'id' => 'subject_detail']) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>


                <fieldset id="disciplinary-detail" class="disciplinary-detail d-none">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Disciplinary DETAILS</legend>
                    <div class="form-group row">
                        <div class="col-lg-6 mb-3">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Employee involved in Misconduct :<span
                                        class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('disciplinary[emp_name]', null, [
                                            'class' => 'form-control',
                                            'id' => 'disciplinary_emp_name',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Sub-Function :</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('disciplinary[dept]', $value ?? null, ['class' => 'form-control']) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Type of Misconduct :<span class="text-danger">
                                        *</span></label>
                                @php
                                    $misconductTypes = [
                                        1 => 'Theft',
                                        2 => 'Harassment',
                                        3 => 'Legal Compliance',
                                        4 => 'Others',
                                    ];
                                @endphp
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('disciplinary[misconduct_type]', $misconductTypes, null, [
                                            'class' => 'form-control select-search',
                                            'placeholder' => 'Select Type',
                                            'id' => 'disciplinary_misconduct_type',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Date of Misconduct:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('disciplinary[date]', null, [
                                            'placeholder' => 'YYYY-MM-DD',
                                            'class' => 'form-control daterange-single',
                                            'id' => 'disciplinary_date',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3 mb-1">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Time:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::time('disciplinary[time]', null, [
                                            'class' => 'form-control',
                                            'id' => 'disciplinary_time',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Location :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('disciplinary[location]', $value ?? null, [
                                            'class' => 'form-control',
                                            'id' => 'disciplinary_location',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Was there any Witness in the place of Misconduct?
                                    :<span class="text-danger">
                                        *</span></label>
                                @php
                                    $checkWitness = [1 => 'No', 2 => 'Yes'];
                                @endphp
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('disciplinary[is_witness_present]', $checkWitness, null, [
                                            'class' => 'form-control select-search',
                                            'id' => 'disciplinary_is_present',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3 addWitnessName d-none">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-4">Name of Witness :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('disciplinary[witness_name]', $value ?? null, [
                                            'class' => 'form-control',
                                            'id' => 'disciplinary_witness_name',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Details of Misconduct:<span
                                        class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::textarea('disciplinary[detail]', $value ?? null, [
                                            'class' => 'form-control',
                                            'id' => 'disciplinary_detail',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>



                <fieldset id="suggestion-detail" class="suggestion-detail d-none">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Suggestion Details</legend>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-42">Suggestion Details :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::textarea('suggestion[detail]', null, [
                                            'class' => 'form-control',
                                            'id' => 'suggestion_detail',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset id="other-detail" class="other-detail d-none">
                    <legend class="text-uppercase font-size-sm font-weight-bold">OTHER DETAILS</legend>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <div class="form-group row">
                                <label class="col-form-label col-lg-2">Other Details :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::textarea('other[detail]', null, [
                                            'class' => 'form-control',
                                            'id' => 'other_detail',
                                        ]) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

            </div>

        </div>






    </div>

    <div class="col-md-4 emp-section d-none">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">EMPLOYEE DETAILS</legend>
                @if ($is_employee)
                    {!! Form::hidden('employee[emp_id]', $employee->id) !!}
                    {!! Form::hidden('employee[division_id]', $employee->organization_id) !!}
                    {!! Form::hidden('employee[department_id]', $employee->department_id) !!}
                    {!! Form::hidden('employee[designation_id]', $employee->designation_id) !!}
                @endif
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Employee :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('employee[emp_id]', $employeeList, $is_employee ? $employee->id : null, [
                                        'class' => 'form-control select-search',
                                        'placeholder' => 'Select Employee',
                                        'id' => 'employee_id',
                                        $is_employee ? 'disabled' : '',
                                    ]) !!}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Division Type : <span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('employee[division_id]', $divisionTypeList, $is_employee ? $employee->organization_id : null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Select Division',
                                        'id' => 'emp_division_type',
                                        $is_employee ? 'disabled' : '',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endif --}}
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Sub-Function :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('employee[department_id]', $departmentList, $is_employee ? $employee->department_id : null, [
                                        'class' => 'form-control',
                                        'id' => 'department_id',
                                        'placeholder' => 'Select Sub-Function',
                                        $is_employee ? 'disabled' : '',
                                    ]) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <label class="col-form-label col-lg-4">Designation :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('employee[designation_id]', $designationList, $is_employee ? $employee->designation_id : null, [
                                        'class' => 'form-control',
                                        'id' => 'designation_id',
                                        'placeholder' => 'Select Designation',
                                        $is_employee ? 'disabled' : '',
                                    ]) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

</div>

<div class="text-center">
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

<script>
    $(document).ready(function() {

        $('.select-search').select2();

        $('.chooseAnonymous').click(function(e) {
            var checked = $(this).val();
            $('#grievance_submit').validate()
            removeRules(['employee_id', 'emp_division_type', 'department_id', 'designation_id'])

            if (checked == '11') {
                $('.anonymous-section').removeClass('col-md-8').addClass('col-md-12');

                $('.emp-section').addClass('d-none');
                toastr.warning('Your personal details will not be shared with anyone');
            } else {
                addRules(['employee_id', 'emp_division_type', 'department_id', 'designation_id'])

                $('.anonymous-section').removeClass('col-md-12').addClass('col-md-8');
                $('.emp-section').removeClass('d-none');
            }

            // console.log($('#grievance_submit').validate().settings);

        })

        //fetch emp details
        $('#employee_id').on('change', function() {
            $.ajax({
                type: "GET",
                url: "{{ route('grievance.findEmployee') }}",
                dataType: 'json',
                data: {
                    'employee_id': $(this).val()
                },
                success: function(resp) {
                    if (resp) {
                        $('#emp_division_type').val(resp.organization_id)
                        $('#department_id').val(resp.department_id)
                        $('#designation_id').val(resp.designation_id)
                    }
                },
            })
        })

        $('#subject_type').on('change', function(e) {
            var selected_val = parseInt($(this).val());
            $('.grievances-detail').addClass('d-none');
            $('.disciplinary-detail').addClass('d-none');
            $('.suggestion-detail').addClass('d-none');
            $('.other-detail').addClass('d-none');

            $('#grievance_submit').validate();

            removeRules(
                ['related_grievances',
                    'subject_detail',
                    'disciplinary_emp_name',
                    'disciplinary_detail',
                    'suggestion_detail',
                    'other_detail',
                    'disciplinary_misconduct_type',
                    'disciplinary_date',
                    'disciplinary_time',
                    'disciplinary_location',
                    'disciplinary_is_present',
                    'disciplinary_witness_name'
                ]
            )

            switch (selected_val) {
                case 1:
                    $('.grievances-detail').removeClass('d-none');
                    addRules(['related_grievances', 'subject_detail'])
                    break;
                case 2:
                    addRules(['disciplinary_emp_name', 'disciplinary_misconduct_type',
                        'disciplinary_date', 'disciplinary_time', 'disciplinary_location',
                        'disciplinary_is_present', 'disciplinary_witness_name',
                        'disciplinary_detail'
                    ])
                    $('.disciplinary-detail').removeClass('d-none');
                    break;
                case 3:
                    addRules(['suggestion_detail'])
                    $('.suggestion-detail').removeClass('d-none');
                    break;
                case 4:
                    addRules(['other_detail'])
                    $('.other-detail').removeClass('d-none');
                    break;
                default:
                    break;
            }
        })

        $('#disciplinary_is_present').on('change', function() {
            var is_present = $(this).val()
            if (is_present == 2) {
                $('.addWitnessName').removeClass('d-none')
            } else {
                $('.addWitnessName').addClass('d-none')
            }
        })
    });

    function addRules(array) {
        array.forEach(function(key) {
            name = key.replace("_", " ");
            $('#grievance_submit #' + key).rules("add", {
                required: true,
                messages: {
                    required: capitalizeFirstLetter(name) + ' is required'
                }
            });
        })

    }

    function removeRules(rulesObj) {
        rulesObj.forEach(function(key) {
            $('#' + key).rules('remove');

        })
    }

    function capitalizeFirstLetter(string) {
        return string && string.charAt(0).toUpperCase() + string.substring(1);
    };
</script>
