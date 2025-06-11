@php
    $newGenderList['all'] = 'All';
    foreach ($genderList as $key => $data) {
        $newGenderList[$key] = $data;
    }

    $newMaritalStatusList['all'] = 'All';
    foreach ($maritalStatusList as $key => $data) {
        $newMaritalStatusList[$key] = $data;
    }
@endphp

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Organization:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                                @if ($errors->has('organization_id'))
                                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Leave Year:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('leave_year_id', $leaveYearList, $currentLeaveyear, [
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                @if ($errors->has('leave_year_id'))
                                    <div class="error text-danger">{{ $errors->first('leave_year_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Title:<span class="text-danger"> *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('name', null, ['placeholder' => 'Enter Title', 'class' => 'form-control']) !!}
                                </div>
                                @if ($errors->has('name'))
                                    <div class="error text-danger">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Code:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('code', null, ['placeholder' => 'Enter Code', 'class' => 'form-control', 'id' => 'code']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 maxSubstituteDays" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Leave Expiration Duration:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('max_substitute_days', null, [
                                        'placeholder' => 'Enter Number of Days',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('max_substitute_days'))
                                    <div class="error text-danger">{{ $errors->first('max_substitute_days') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Number of Days:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('number_of_days', null, [
                                        'placeholder' => 'Enter Number of Days',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                                @if ($errors->has('number_of_days'))
                                    <div class="error text-danger">{{ $errors->first('number_of_days') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Leave Type:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('leave_type', $leaveTypeList, null, [
                                        'class' => 'form-control
                                                                                                            select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Gender:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('gender', $newGenderList, null, [
                                        'class' => 'form-control
                                                                                                            select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Marital Status:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('marital_status', $newMaritalStatusList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Sub-Functions:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback">
                                <div class="input-group">
                                    @php
                                        if ($isEdit) {
                                            if (count($leaveTypeModel->departments) > 0) {
                                                foreach ($leaveTypeModel->departments as $model) {
                                                    $departmentValues[] = $model->department_id;
                                                }
                                            } else {
                                                $departmentValues = null;
                                            }
                                        } else {
                                            $departmentValues = null;
                                        }
                                    @endphp
                                    {!! Form::select('departmentArray[]', $departmentList, $departmentValues, [
                                        'class' => 'form-control multiselect-select-all-filtering',
                                        'id' => 'departmentArray',
                                        'multiple' => 'multiple',
                                        'data-fouc',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Grade: <span class="text-danger">*</span></label>
                            <div class="col-lg-8 form-group-feedback">
                                <div class="input-group">
                                    @php
                                        if ($isEdit) {
                                            if (count($leaveTypeModel->levels) > 0) {
                                                foreach ($leaveTypeModel->levels as $model) {
                                                    $levelValues[] = $model->level_id;
                                                }
                                            } else {
                                                $levelValues = null;
                                            }
                                        } else {
                                            $levelValues = null;
                                        }
                                    @endphp
                                    {!! Form::select('levelArray[]', $levelList, $levelValues, [
                                        'class' => 'form-control multiselect-select-all-filtering',
                                        'id' => 'levelArray',
                                        'multiple' => 'multiple',
                                        'data-fouc',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Description :</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('description', null, [
                                        'rows' => 4,
                                        'placeholder' => 'Write description here..',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-0">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Status :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('status', $statusList, null, [
                                        'class' => 'form-control
                                                                                                            select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 mt-0 d-none" id="employee-col">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Employees :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <select name="employee_ids[]" id="employee_id" class="form-control  d-none"
                                        multiple="multiple" data-fouc></select>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Setting Detail</legend>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Display on Employee?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('show_on_employee', $yesNoList, null, [
                                        'class' => 'form-control
                                                                                                            select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Enable Half Leave?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('half_leave_status', $noYesList, null, [
                                        'id' => 'halfLeave',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-12 mt-2 halfLeaveDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Which Half?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('half_leave_type', $halfLeaveList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Enable Prorata?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('prorata_status', $noYesList, null, [
                                        'class' => 'form-control
                                                                                                            select-search enableProrata',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3 allocationDiv" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Advance Allocation?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('advance_allocation', $noYesList, null, [
                                        // 'id' => 'enCashable',
                                        'class' => 'form-control select-search advanceAllocation',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Enable Encashable?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('encashable_status', $noYesList, null, [
                                        'id' => 'enCashable',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3 encashLimitDiv" style="display:none;">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Total Encashment Limit (days)</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('max_encashable_days', null, [
                                        'class' => 'form-control
                                                                                                            encashmentLimit numeric',
                                        'placeholder' => 'e.g: 1',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Enable Carry Forward?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('carry_forward_status', $noYesList, null, [
                                        'id' => 'carry_forward_status',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Enable Sandwich Rule?</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('sandwitch_rule_status', $noYesList, null, ['class' => 'form-control select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Leave request should be submitted before .....
                                Days</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('pre_inform_days', null, ['placeholder' => 'e.g: 1', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Maximum number of days per request</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('max_per_day_leave', null, ['placeholder' => 'e.g: 3', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Job Types <span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-3 form-group-feedback">
                                <div class="input-group">
                                    @php
                                        if ($isEdit) {
                                            if (count($leaveTypeModel->jobTypes) > 0) {
                                                foreach ($leaveTypeModel->jobTypes as $model) {
                                                    $jobTypeValues[] = $model->job_type_id;
                                                }
                                            } else {
                                                $jobTypeValues = null;
                                            }
                                        } else {
                                            $jobTypeValues = null;
                                        }
                                    @endphp
                                    {!! Form::select('jobTypeArray[]', $jobTypeList, $jobTypeValues, [
                                        'class' => 'form-control multiselect-select-all-filtering',
                                        'id' => 'jobTypeArray',
                                        'multiple' => 'multiple',
                                        'data-fouc',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-12 mt-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Contract Type</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('contract_type', $contractTypeList, null, ['class' => 'form-control
                                    select-search']) !!}
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-12 mt-3 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-9">Fixed Remaining Leave</label>
                            <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('fixed_remaining_leave', null, ['placeholder' => 'e.g: 5', 'class' => 'form-control numeric']) !!}
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

@section('script')
    {{-- <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script> --}}
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    {{-- <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script> --}}
    <script src="{{ asset('admin/validation/leaveType.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.enableProrata').on('change', function() {
                var enable_prorata = $(this).val()
                if (enable_prorata == 11) {
                    $('.allocationDiv').css('display', 'block')
                } else {
                    $('.allocationDiv').css('display', 'none')
                }
            })
            $('.enableProrata').trigger('change')

            $('#enCashable').on('change', function() {
                var is_encash = $(this).val()
                if (is_encash == 11) {
                    $('.encashLimitDiv').css('display', 'block')
                } else {
                    $('.encashLimitDiv').css('display', 'none')
                }
            })
            $('#enCashable').trigger('change')

            $('#code').on('keyup', function() {
                var code = $(this).val()
                if (code == 'SUBLV') {
                    $('.maxSubstituteDays').show()
                } else {
                    $('.maxSubstituteDays').hide()
                }
            })
            $('#code').trigger('keyup')

            fnEncashable();
            fnHalfLeave();

            $('#enCashable').on('change', function() {
                fnEncashable();
            });

            $('#halfLeave').on('change', function() {
                fnHalfLeave();
            });

            $('#carry_forward_status').on('change', function() {
                // fnCarryForward();
            });

            function fnEncashable() {
                var value = $('#enCashable').val();
                if (value == '11') {
                    // $('.enCashableDiv').show();
                    // $('#carry_forward_status').val(10).trigger('change');

                } else {
                    // $('.enCashableDiv').hide();
                }
            }

            function fnCarryForward() {
                var value = $('#carry_forward_status').val();
                if (value == '11') {
                    $('#enCashable').val(10).trigger('change');

                }
            }

            function fnHalfLeave() {
                var value = $('#halfLeave').val();
                if (value == '11') {
                    $('.halfLeaveDiv').show();
                } else {
                    $('.halfLeaveDiv').hide();
                }
            }
        });
    </script>


    {{-- --}}

    <script>
        $(document).ready(function() {
            const $codeInput = $('#code');
            const $departmentSelect = $('#departmentArray');
            const $employeeSelect = $('#employee_id');
            const getEmployeeUrl = "{{ route('leave.getEmployeeDepartmentWise') }}";

            const isEdit = {{ isset($isEdit) && $isEdit ? 'true' : 'false' }};
            const preselectedEmployees = {!! json_encode($leaveTypeModel->employee_ids ?? []) !!};

            // Initialize Select2


            $employeeSelect.select2({
                placeholder: "Select employees",
                allowClear: true,
                multiple: true
            });

            function fetchEmployees(preselect = false) {
                const sublv = $codeInput.val().trim();
                const departmentIds = $departmentSelect.val();

                if (sublv !== '' && departmentIds && departmentIds.length > 0) {
                    $.ajax({
                        url: getEmployeeUrl,
                        method: 'GET',
                        data: {
                            sublv: sublv,
                            department_id: departmentIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Employees:', response);

                            $('#employee-col').removeClass('d-none');
                            $employeeSelect.empty();

                            response.forEach(employee => {
                                const isSelected = preselect && preselectedEmployees.includes(
                                    employee.id.toString());

                                const option = new Option(employee.full_name, employee.id,
                                    isSelected, isSelected);
                                $employeeSelect.append(option);
                            });

                            $employeeSelect.trigger('change');
                        },
                        error: function() {
                            alert('Failed to fetch employees.');
                        }
                    });
                }
            }

            // Event listeners
            $codeInput.on('input', function() {
                fetchEmployees(false);
            });

            $departmentSelect.on('change', function() {
                fetchEmployees(false);
            });

            // Trigger on page load if in edit mode and values exist
            if (isEdit && $codeInput.val().trim() !== '' && $departmentSelect.val().length > 0) {
                fetchEmployees(true);
            }
        });
    </script>
@endSection
