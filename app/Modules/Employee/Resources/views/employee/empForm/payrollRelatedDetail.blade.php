<style>
    #ndp-nepali-box.ndp-corner-all,
    .ndp-nepali-box {
        position: absolute !important;
        top: 30% !important;
        /* left: 25% !important; */
        width: auto !important;
        z-index: 9999 !important;
    }
</style>
<div class="row">
    {{-- <div class="col-md-4 mb-3 d-none">
        <div class="row">
            <label class="col-form-label col-lg-4">Join Date:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-calendar2"></i></span>
                    </span>
                    @if ($is_edit)
                        {!! Form::text('join_date', null, ['placeholder' => 'e.g: YYYY-MM-DD', 'readonly', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::text('join_date', null, [
                            'placeholder' => 'e.g: YYYY-MM-DD',
                            'readonly',
                            'class' => 'form-control daterange-single',
                        ]) !!}
                    @endif
                </div>
            </div>
        </div>
    </div> --}}
    <div class="col-md-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">Contract Type:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('contract_type', $contractTypeList, $value = null, [
                        'placeholder' => 'Select Contract Type',
                        'class' => 'form-control select-search',
                        'id' => 'contract_type',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3 contract-date d-none">
        <div class="row">
            <label class="col-form-label col-lg-4">Contract Start Date:</label>
            {{-- @dd($employees) --}}
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <x-utilities.date-picker :date="$employees->payrollRelatedDetailModel->contract_start_date ?? null" mode="toggle" default="eng"
                    nepDateAttribute="contract_nep_start_date" engDateAttribute="contract_start_date" />
                {{-- <div class="input-group">
                    {!! Form::text('contract_start_date', $value = null, [
                        'placeholder' => 'e.g: YYYY-MM-DD',
                        'class' => 'form-control daterange-single',
                    ]) !!}
                </div> --}}
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3 contract-date d-none">
        <div class="row">
            <label class="col-form-label col-lg-4">Contract End Date:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <x-utilities.date-picker :date="$employees->payrollRelatedDetailModel->contract_end_date ?? request('contract_end_date')" mode="toggle" default="eng"
                    nepDateAttribute="contract_nep_end_date" engDateAttribute="contract_end_date" />
                {{-- <div class="input-group">
                    {!! Form::text('contract_end_date', $value = null, [
                        'placeholder' => 'e.g: YYYY-MM-DD',
                        'class' => 'form-control daterange-single',
                    ]) !!}
                </div> --}}
            </div>
        </div>
    </div>

    <div
        class="col-md-4 mb-3 probstatus  {{ isset($employees) && $employees->probation_status == '11' ? '' : 'd-none' }}">
        <div class="row">
            <label class="col-form-label col-lg-4">Probation Status:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('probation_status', $statusList, $value = null, [
                        'id' => 'probationStatus',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">OT:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('ot', $statusList, $value = null, [
                        'id' => 'ot','placeholder' => 'Select Status',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
        </div>
    </div> --}}
    <div class="col-md-4 mb-3 probationDiv"
        style="display: {{ isset($employees) && $employees->probation_status == '11' ? 'block' : 'none' }}">
        <div class="row">
            <label class="col-form-label col-lg-4">Probation Start Date:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <x-utilities.date-picker :date="$employees->payrollRelatedDetailModel->probation_start_date ?? null" mode="toggle" default="eng"
                    nepDateAttribute="probation_nep_start_date" engDateAttribute="probation_start_date" />
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-3 probationDiv"
        style="display: {{ isset($employees) && $employees->probation_status == '11' ? 'block' : 'none' }}">
        <div class="row">
            <label class="col-form-label col-lg-4">Probation End Date:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <x-utilities.date-picker :date="$employees->payrollRelatedDetailModel->probation_end_date ?? request('probation_end_date')" mode="toggle" default="eng"
                    nepDateAttribute="probation_nep_end_date" engDateAttribute="probation_end_date" />
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="row">
            <label class="col-form-label col-lg-4">Account Number:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    @if ($is_edit)
                        {!! Form::text('account_no', null, ['placeholder' => 'Enter bank account No', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::text('account_no', null, [
                            'placeholder' => 'Enter Bank Account No',
                            'class' => 'form-control',
                        ]) !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <label class="col-form-label col-lg-4">PAN Number:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('pan_no', null, [
                        'placeholder' => 'Enter Pan Number',
                        'class' => 'form-control',
                        'id' => 'pan_no',
                    ]) !!}
                </div>
                <span class="text-danger">{{ $errors->first('pan_no') }}</span>
                <span class="error_pan_no"></span>

            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <label class="col-form-label col-lg-4">PF Number:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('pf_no', null, ['placeholder' => 'Enter PF Number', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-2">
        <div class="row">
            <label class="col-form-label col-lg-4">SSF Number:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('ssf_no', null, ['placeholder' => 'Enter SSF Number', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-2">
        <div class="row">
            <label class="col-form-label col-lg-4">CIT Number:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('cit_no', null, ['placeholder' => 'Enter CIT Number', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-2">
        <div class="row">
            <label class="col-form-label col-lg-5">Gratuity Fund Account Number:</label>
            <div class="col-lg-7 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('gratuity_fund_account_no', null, [
                        'placeholder' => 'Enter Gratuity Fund Account Number',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-2">
        <div class="row">
            <label class="col-form-label col-lg-4">Salary Tax Calculation:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('tax_calculation', ['regular' => 'Regular', 'actual' => 'Actual'], null, [
                        'placeholder' => 'Enter Salary Tax Calculation',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<legend class="text-uppercase font-size-sm font-weight-bold">Previous Payroll Detail</legend>
<div class="row">
    <div class="col-md-4">
        <div class="row">
            <label class="col-form-label col-lg-4">Effective Fiscal Year:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::select('effective_fiscal_year', $fiscalYearList, null, [
                        'placeholder' => 'Select Fiscal Year',
                        'class' => 'form-control select-search',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <label class="col-form-label col-lg-4">Total Previous Income:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('total_previous_income', null, [
                        'placeholder' => 'Enter Previous Total Income',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="row">
            <label class="col-form-label col-lg-4">Total Previous Deduction:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('total_previous_deduction', null, [
                        'placeholder' => 'Enter Previous Total Deduction',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-2">
        <div class="row">
            <label class="col-form-label col-lg-4">Total TDS Paid:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('total_tds_paid', null, [
                        'placeholder' => 'Enter Previous Total Tds Paid',
                        'class' => 'form-control',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-4 mt-2">
        <div class="row">
            <label class="col-form-label col-lg-4">Previous Paid Month:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    {!! Form::text('previous_paid_month', null, ['placeholder' => 'Enter Previous Paid Month', 'class' => 'form-control']) !!}
                </div>
            </div>
        </div>
    </div> --}}
    <div class="col-md-4 mt-2">
        <div class="row">
            <label class="col-form-label col-lg-4">Grade Applicable Date:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                    @if (setting('calendar_type') == 'BS')
                        {!! Form::text('grade_applicable_nep_date', $value = null, [
                            'placeholder' => 'Enter Grade Applicable Month',
                            'class' => 'form-control nepali-calendar',
                            'readOnly',
                        ]) !!}
                    @else
                        {!! Form::text('grade_applicable_date', $value = null, [
                            'placeholder' => 'Choose Grade Applicable Date',
                            'class' => 'form-control daterange-single',
                            'readOnly',
                        ]) !!}
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<legend class="text-uppercase font-size-sm font-weight-bold">Threshold Benefit Detail</legend>

<div class="row">
    @if ($is_edit == true)
        @if (count($employeeThresholdList) > 0)
            @foreach ($employeeThresholdList as $key => $value)
                {{-- {{dd($value)}} --}}
                <div class="col-md-4 mb-3">
                    <div class="row">
                        <label class="col-form-label col-lg-4">{{ $value->title }}</label>
                        <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                <span class="input-group-prepend">
                                    <span class="input-group-text">Rs.</span>
                                </span>
                                <input type="text" name="gross_salary[{{ $value->id }}]"
                                    value="{{ optional($value->employeeThresholdBenefit)->amount }}"
                                    class="form-control numeric">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    @else
        @foreach ($deductionList as $key => $value)
            <div class="col-md-4 mb-3">
                <div class="row">
                    <label class="col-form-label col-lg-4">{{ $value }}</label>
                    <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                        <div class="input-group">
                            <span class="input-group-prepend">
                                <span class="input-group-text">Rs.</span>
                            </span>
                            <input type="text" name="gross_salary[{{ $key }}]" value=""
                                class="form-control numeric">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- <div class="col-md-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">Gross Salary:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text">Rs.</span>
                </span>
                    {!! Form::text('basic_salary', null, ['placeholder'=>'e.g: 10000','class'=>'form-control numeric']) !!}
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <div class="col-md-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">Insurance Premium:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text">Rs.</span>
                </span>
                    {!! Form::text('insurance_premium', null, ['placeholder'=>'e.g: 10000','class'=>'form-control numeric']) !!}
                </div>
            </div>
        </div>
    </div> --}}
    {{-- <div class="col-md-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">Dearness Allowance:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text">Rs.</span>
                </span>
                    {!! Form::text('dearness_allowance', null, ['placeholder'=>'e.g: 5000','class'=>'form-control numeric']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="row">
            <label class="col-form-label col-lg-4">Lunch Allowance:</label>
            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text">Rs.</span>
                </span>
                    {!! Form::text('lunch_allowance', null, ['placeholder'=>'e.g: 2000','class'=>'form-control numeric']) !!}
                </div>
            </div>
        </div>
    </div> --}}
</div>

<script>
    function capitalizeInput(element) {
        let words = element.value.toLowerCase().split(' ');
        for (let i = 0; i < words.length; i++) {
            words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
        }
        element.value = words.join(' ');
    }
    $(document).ready(function() {

        $('#probationStatus').on('change', function() {
            var status = $(this).val();
            if (status == '11') {
                $('.probationDiv').show();
                $("#show_permanent").hide();
            } else {
                $("#show_permanent").show();
                $('.probationDiv').hide();
            }
        });

        function syncDateChange(contractTypeVal, joinDate) {
            @if (!empty($employees))
                const payrollDetails = @json($employees->payrollRelatedDetailModel);
                const jobstatus = payrollDetails?.contract_type;

                if (jobstatus === '10' && payrollDetails?.probation_status === '11') {
                    const probationStart = payrollDetails?.probation_start_date;
                    if (contractTypeVal === '10') {
                        $('#probation_start_date').val(probationStart ?? joinDate);
                    }
                }

                if (jobstatus === '11') {
                    const contractStart = payrollDetails?.contract_start_date;
                    if (contractTypeVal === '11') {
                        $('#contract_start_date').val(contractStart ?? joinDate);
                    }
                }
            @endif
        }

        $('#contract_type').on('change', function() {
            const val = $(this).val();
            const joiningDate = $('#nepali_join_date').val();
            let joinDate = $('#join_date').val();

            if (!joinDate && joiningDate) {
                const nepDateParts = joiningDate.split('-');
                if (nepDateParts.length === 3) {
                    const bsDate = NepaliFunctions.BS2AD({
                        year: parseInt(nepDateParts[0]),
                        month: parseInt(nepDateParts[1]),
                        day: parseInt(nepDateParts[2])
                    });
                    joinDate =
                        `${bsDate.year}-${('0' + bsDate.month).slice(-2)}-${('0' + bsDate.day).slice(-2)}`;
                }
            }
            if (val === '10') {
                $('.probstatus').removeClass('d-none');
                $('.contract-date').addClass('d-none');

                const status = $('#probationStatus').val();
                if (status === '11') {
                    $('.probationDiv').show();
                    $("#show_permanent").hide();
                    $('#probation_start_date').val(joinDate);
                    $('#contract_start_date').val('');
                } else {
                    $("#show_permanent").show();
                    $('.probationDiv').hide();
                    $('#probation_start_date').val('');
                    $('#contract_start_date').val('');
                    $('#probation_end_date').val('');
                    $('#contract_end_date').val('');
                }

            } else if (val === '11') {
                $("#show_permanent").hide();
                $('.contract-date').removeClass('d-none');
                $('.probstatus').addClass('d-none');
                $('.probationDiv').hide();

                $('#probation_start_date').val('');
                $('#contract_start_date').val(joinDate);
            } else if (val === '12') {
                $('.contract-date').addClass('d-none');
                $('.probstatus').addClass('d-none');
                $('.probationDiv').hide();

                $('#probation_start_date').val('');
                $('#contract_start_date').val('');
                $('#probation_end_date').val('');
                $('#contract_end_date').val('');
            }

            syncDateChange(val, joinDate);
        });



        var $isEdit = "{{ $is_edit }}";
        if ($isEdit) {
            $('#contract_type').trigger('change');
        }

    });
</script>
