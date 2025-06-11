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
                                    {!! Form::select('organization_id', @$organizationList, $employeeMassIncrement->organization_id ?? null, [
                                        'id' => 'organization_id',
                                        'class' => 'form-control select-search organization-filter',
                                        'required',
                                        'disabled',
                                        'placeholder' => 'Select organization',
                                    ]) !!}
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
                                    {!! Form::select('employee_id', $employees, $employeeMassIncrement->employee_id ?? null, [
                                        'class' => 'form-control select-search employee-filter',
                                        'placeholder' => 'Select Employee',
                                        'required',
                                        'disabled',
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
                    @if (isset($employeeMassIncrement) && count($employeeMassIncrement->details) > 0)
                        @foreach ($employeeMassIncrement->details as $key=>$detail)
                            <div class="form-group row income">
                                <div class="col-lg-3 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Incomes:<span class="text-danger">
                                                *</span></label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('income_setup_id['.$key.']', @$detail->detailIncome, $detail->income_setup_id, [
                                                    'class' => 'form-control select-search edit-income',
                                                    'placeholder' => 'Select Incomes',
                                                    'required',
                                                    $detail->status ? 'disabled':''
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('income_setup_id'))
                                                <div class="error text-danger">{{ $errors->first('income_setup_id') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Existing Amount:<span class="text-danger">
                                                *</span></label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('exiting_amount['.$key.']', $detail->getLatestAmount(), [
                                                    'class' => 'form-control numeric',
                                                    'placeholder' => 'Exiting Amount',
                                                    'required',
                                                    'readonly',
                                                     $detail->status ? 'disabled':''
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('exiting_amount'))
                                                <div class="error text-danger">{{ $errors->first('exiting_amount') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-lg-3">Increased Amount:<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::text('increased_amount['.$key.']', @$detail->increased_amount, [
                                                    'class' => 'form-control numeric',
                                                    'placeholder' => 'Enter Increased Amount',
                                                    'required',
                                                    $detail->status ? 'disabled':''
                                                ]) !!}
                                            </div>
                                            @if ($errors->has('increased_amount'))
                                                <div class="error text-danger">{{ $errors->first('increased_amount') }}
                                                </div>
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
                                                {!! Form::text('effective_date['.$key.']',$detail->effective_date ?? null, [
                                                    'placeholder' => 'Select Effective Date',
                                                    'readonly',
                                                    'required',
                                                    'class' => 'form-control nepali-calendar',
                                                    $detail->status ? 'disabled':''
                                                ]) !!}

                                            </div>
                                            @if ($errors->has('effective_date'))
                                                <div class="error text-danger">{{ $errors->first('effective_date') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($key==0)
                                    <div class="col-lg-1 mb-3">
                                        <div class="row">
                                            <button type="button"
                                                class="add_particular btn bg-success-400 btn-icon text-white"
                                                id="addMore">
                                                <i class="icon-plus3"></i><b></b>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    @if(!$detail->status)
                                        <div class="col-lg-1 mb-3">
                                            <div class="row">
                                                <button type="button" class="removeIncome btn bg-danger-400 btn-icon text-white">
                                                    <i class="icon-minus3"></i><b></b>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                            </div>
                        @endforeach
                    @else
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
                                            <div class="error text-danger">{{ $errors->first('increased_amount') }}
                                            </div>
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
                    @endif


                </div>

            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center pt-1 pb-3 pl-3 pr-3" style="">
    <button class="btn bg-teal text-white updateBtn" style="" type="submit">Update Changes</button>
</div>
<script>
    var selectedEmployee = null;
    $(document).ready(function() {
        const checkUpdateBtn=()=>{
            var removeAttribute=$('.removeIncome');
            if(removeAttribute.length > 0){
                $('.updateBtn').show();
            }else{
                $('.updateBtn').hide();
            }
        }
        checkUpdateBtn();

        $('.removeIncome').on('click', function() {
            $(this).closest('.income').remove();
            checkUpdateBtn();
        });
        let numberIncr = "{{@$employeeMassIncrement->details->count()}}";
        const selectedIncomes = new Set(); // Track selected incomes

        function updateSelectedIncomes() {
            selectedIncomes.clear();
            $('.income-filter').each(function() {
                const value = $(this).val();
                if (value) selectedIncomes.add(value);
            });
            $('.edit-income').each(function() {
                const value = $(this).val();
                if (value) selectedIncomes.add(value);
            });
        }
        $('#organization_id').on('change', function() {
            var organization_id = "{{ @$employeeMassIncrement->organization_id ?? null }}" ?? $(this)
                .val();
            $.ajax({
                type: 'GET',
                url: "{{ route('deductionSetup.getIncomeTypes') }}",
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
                var organizationId = $('.organization-filter').val();
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
            const organizationId = $('#organization_id').val();

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
                    checkUpdateBtn();
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
            var employeeValue = "{{ @$employeeMassIncrement->employee_id ?? null }}" ?? $(this).val();
            $('.append-income').hide();
            if (employeeValue) {
                $('.append-income').show();
            }
        });
        @isset($employeeMassIncrement)
            // $('#organization_id').trigger('change');
            $('.employee-filter').trigger('change');
        @endisset
        var beforeDate="{{@$currentDateInNep}}";
        $('.nepali-calendar').nepaliDatePicker({
            ndpYear: true,
            ndpMonth: true,
            ndpTriggerButton: false,
            disableBefore: beforeDate,
            onChange: function() {
            }
        });

    });
</script>
