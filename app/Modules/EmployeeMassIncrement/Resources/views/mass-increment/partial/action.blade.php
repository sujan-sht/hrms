<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                <div class="form-group row">
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Organization:<span class="text-danger">*</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- {!! Form::select('organization_id', $organizationList, null, ['class' => 'form-control select-search organization-filter','id' => 'organization_id','placeholder' => 'Select organization','required']) !!} --}}
                                    @if (count($organizationList) === 1)
                                        @php
                                            $orgGroup = $organizationList->first();
                                        @endphp
                                        @if ($orgGroup)
                                            {!! Form::select('organization_id', @$organizationList, $value = $orgGroup->id ?? null, [
                                                'id' => 'organizationField',
                                                'class' => 'form-control select-search ',
                                                'required',
                                                'placeholder' => 'Select organization',
                                            ]) !!}
                                        @endif
                                    @else
                                        {!! Form::select('organization_id', @$organizationList, $value = $orgGroup->id ?? null, [
                                            'id' => 'organization_id',
                                            'class' => 'form-control select-search ',
                                            'placeholder' => 'Select Organization',
                                            'required',
                                        ]) !!}
                                    @endif
                                </div>
                                @if ($errors->has('organization_id'))
                                    <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Employee:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('employee_id', [], null, [
                                        'class' => 'form-control select-search employee-filter',
                                        'placeholder' => 'Select Employee',
                                        'required',
                                    ]) !!}
                                </div>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold">Mass Increment Detail</legend>
                <div class="append-income" style="display:none">
                    <div class="form-group row income">
                        <div class="col-lg-3 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Incomes:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('income_setup_id[0]', [], null, [
                                            'class' => 'form-control select-search income-filter income-selection',
                                            'placeholder' => 'Select Incomes',
                                            'required',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('income_setup_id'))
                                        <div class="error text-danger">{{ $errors->first('income_setup_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Exiting Amount:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('exiting_amount[0]', null, [
                                            'class' => 'form-control numeric',
                                            'placeholder' => 'Exiting Amount',
                                            'required',
                                            'readonly',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('exiting_amount'))
                                        <div class="error text-danger">{{ $errors->first('exiting_amount') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-3">Increased Amount:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('increased_amount[0]', null, [
                                            'class' => 'form-control numeric',
                                            'placeholder' => 'Enter Increased Amount',
                                            'required',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('increased_amount'))
                                        <div class="error text-danger">{{ $errors->first('increased_amount') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Effective Date:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('effective_date[0]', $value = null, [
                                            'placeholder' => 'Select Effective Date',
                                            'readonly',
                                            'required',
                                            'class' => 'form-control nepali-calendar',
                                        ]) !!}

                                    </div>
                                    @if ($errors->has('effective_date'))
                                        <div class="error text-danger">{{ $errors->first('effective_date') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-1 mb-3">
                            <div class="row">
                                <button type="button" class="add_particular btn bg-success-400 btn-icon text-white"
                                    id="addMore">
                                    <i class="icon-plus3"></i><b></b>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center pt-1 pb-3 pl-3 pr-3">
    <button class="btn bg-teal text-white" type="submit">Save Changes</button>
</div>
<script>
    var selectedEmployee = null;
    $(document).ready(function() {
        let numberIncr = 1;
        const selectedIncomes = new Set(); // Track selected incomes

        function updateSelectedIncomes() {
            selectedIncomes.clear();
            $('.income-filter').each(function() {
                const value = $(this).val();
                if (value) selectedIncomes.add(value);
            });
        }
        $('#organizationField').on('change', function() {
            var organization_id = $(this).val()
            $.ajax({
                type: 'GET',
                url: "{{ route('deductionSetup.getIncomeTypesWithGross') }}",
                data: {
                    organization_id: organization_id,
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';

                    options += "<option value=''>Select Incomes</option>";
                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'  >" + value +
                            "</option>";
                    });
                    $('.income-filter').html(options);
                }
            });

        });

        const getIncome = () => {
            $('.income-selection').on('change', function() {
                var field = $(this);
                var selectedIncome = $(this).val();
                var employeeId = $('.employee-filter').val();
                var organizationId = $('#organizationField').val();
                $.ajax({
                    url: "{{ route('fetchincome.employee') }}",
                    type: "get",
                    data: {
                        selectedIncome: selectedIncome,
                        employeeId: employeeId,
                        organizationId: organizationId
                    },
                    success: function(response) {
                        if (response.error) {
                            field.closest('.form-group').find(
                                'input[name^="exiting_amount"]').val(
                                null);
                            return false;
                        }
                        field.closest('.form-group').find(
                            'input[name^="exiting_amount"]').val(
                            response.data);
                    }
                });

            });
        }
        getIncome();
        $('#addMore').on('click', function() {
            const organizationId = $('#organizationField').val();

            if (!organizationId) {
                alert('Please select an organization first.');
                return;
            }

            updateSelectedIncomes(); // Update selected incomes before the request

            $.ajax({
                type: 'GET',
                url: "{{ route('employeeMassIncrement.addIncome') }}",
                data: {
                    organization_id: organizationId,
                    numberIncr: numberIncr,
                    selectedIncomes: Array.from(
                        selectedIncomes) // Send selected incomes to the backend
                },
                success: function(data) {
                    numberIncr++;
                    $('.append-income').append(data.options);
                    getIncome();
                },
                error: function() {
                    alert('An error occurred while adding income.');
                }
            });
        });

        $(document).on('change', '.income-filter', function() {
            updateSelectedIncomes();
        });

        $('.employee-filter').on('change', function() {
            var employeeValue = $(this).val();
            $('.append-income').hide();
            if (employeeValue) {
                $('.append-income').show();
            }
        });
        var beforeDate="{{@$currentDateInNep}}";
        $('.nepali-calendar').nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpTriggerButton: false,
            disableBefore: beforeDate,
            onChange: function() {
            }
        });

        $('#organizationField').on('change', function() {
            var organizationId = $('#organizationField').val();
            var employeeId = $('.employee-filter').val();
            var leaveTypeId = $('.leave-type-filter').val();
            var selectedEmployee=@json($createdEmployee) ?? null;
            console.log('Ok',selectedEmployee);
            $.ajax({
                type: 'GET',
                url: '/admin/organization/get-employees',
                data: {
                    organization_id: organizationId,
                    selectedEmployee:selectedEmployee
                },
                success: function(data) {
                    var list = JSON.parse(data);
                    var options = '';

                    options += "<option value=''>Select Employee</option>";
                    $.each(list, function(id, value) {
                        options += "<option value='" + id + "'  >" + value +
                            "</option>";
                    });

                    $('.employee-filter').html(options);
                    $('.employee-filter').select2();

                    if (employeeId) {
                        $('.employee-filter').val(employeeId);
                    }

                    if (empArray) {
                        $('.employee-filter').val(empArray.id);
                    }
                    $('input[name="leave_kind"]').prop('checked', false);

                    $('.employee-filter').trigger('change');
                    $('#remainingLeaveDetail').empty();
                    $('#remainingLeaveDiv').hide();
                    $('#leaveType').html(
                            "<option selected=\"selected\">Choose Leave Type</option>")
                        .select2();
                }
            });
        })

    });
</script>
