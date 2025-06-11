<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row">
                    @if (isset($organizationId))
                        {!! Form::hidden('organization_id', $organizationId, []) !!}
                    @else
                        <div class="col-md-6 mb-3">
                            <div class="row items">
                                <label class="col-form-label col-lg-2">Select Organization:</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('organization_id', $organizationList, null, [
                                            'id' => 'organization',
                                            'class' => 'form-control select-search organization-filter organization-filter2',
                                            'placeholder' => 'Select Organization',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($employeeId))
                        {!! Form::hidden('employee_id', $employeeId, ['id' => 'employeeId', 'class' => 'employee-filter']) !!}
                    @else
                        <div class="col-lg-6 mb-3">
                            <div class="row items">
                                <label class="col-form-label col-lg-2">Employee Name :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('employee_id', $employeeList, null, [
                                            'placeholder' => 'Select Employee',
                                            'class' => 'form-control select-search employee-filter',
                                            'required',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('employee_id'))
                                        <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-6 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Amount :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('advance_amount', null, [
                                        'id' => 'advanceAmount',
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Issue Date :</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('from_date', null, [
                                        'placeholder' => 'YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if (isset($approvalStatus))
                        {!! Form::hidden('approval_status', 1, []) !!}
                    @else
                        <div class="col-lg-6 mb-3">
                            <div class="row items">
                                <label class="col-form-label col-lg-2">Approval Status:<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('approval_status', $approvalstatusList, null, [
                                            'class' => 'form-control select-search',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('approval_status'))
                                        <div class="error text-danger">{{ $errors->first('approval_status') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold">Settlement Details</legend>
                <div class="col-lg-6 form-group-feedback form-group-feedback-right">
                    <div class="input-group">
                        <div class="p-1 rounded">
                            {{-- <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('settlement_type', 1, true, ['class' => 'custom-control-input settlementType', 'id' => 'radio1']) }}
                                <label class="custom-control-label mr-3" for="radio1">One-Time Pay</label>
                            </div> --}}
                            <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('settlement_type', 2, true, ['class' => 'custom-control-input settlementType', 'id' => 'radio2']) }}
                                <label class="custom-control-label mr-3" for="radio2">Full/Partially Pay</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                {{ Form::radio('settlement_type', 3, false, ['class' => 'custom-control-input settlementType', 'id' => 'radio3']) }}
                                <label class="custom-control-label mr-3" for="radio3">Monthly EMI Pay</label>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row settlementType01 mt-3">
                    <div class="col-lg-6 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Due Date :</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('due_date', null, [
                                        'placeholder' => 'YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Total Amount :</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('total_amount', null, ['placeholder' => 'Enter Amount', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="settlementType02 row mt-3">
                    <div class="col-lg-5 mb-3 engDueDate">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Due Date :</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('partial_date[]', null, [
                                        'placeholder' => 'YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 mb-3 nepDueDate" style="display:none">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Due Daten :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('partial_date[]', null, [
                                        'rows' => 5,
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepali-datepicker-start-date',
                                        'readonly',
                                    ]) !!}
                                </div>
                                @if ($errors->has('start_date'))
                                    <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-2">Amount :</label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('partial_amount[]', null, ['placeholder' => 'Enter Amount', 'class' => 'form-control numeric']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <a id="addMore" class="btn btn-success rounded-pill">
                            <i class="icon-plus-circle2 mr-1"></i>Add More
                        </a>
                    </div>
                </div>
                <div class="form-repeater"></div>
                <!-- hidden repeater form start -->
                <div id="repeatForm" style="display:none;">
                    <div class="row parent">
                        {{-- <div class="col-lg-5 mb-3">
                            <div class="row items">
                                <label class="col-form-label col-lg-2">Due Date :</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('partial_date[]', null, [
                                            'placeholder' => 'YYYY-MM-DD',
                                            'class' => 'form-control daterange-single',
                                            'readonly',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-lg-5 mb-3 engDueDate">
                            <div class="row items">
                                <label class="col-form-label col-lg-2">Due Date :</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('partial_date[]', null, [
                                            'placeholder' => 'YYYY-MM-DD',
                                            'class' => 'form-control daterange-single',
                                            'readonly',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 mb-3 nepDueDate" style="display:none">
                            <div class="row items">
                                <label class="col-form-label col-lg-2">Due Daten :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('partial_date[]', null, [
                                            'rows' => 5,
                                            'placeholder' => 'e.g: YYYY-MM-DD',
                                            'class' => 'form-control nepali-datepicker-start-date',
                                            'readonly',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 mb-3">
                            <div class="row items">
                                <label class="col-form-label col-lg-2">Amount :</label>
                                <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::text('partial_amount[]', null, ['placeholder' => 'Enter Amount', 'class' => 'form-control numeric']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <a class="btn btn-danger rounded-pill remove">
                                <i class="icon-minus-circle2 mr-1"></i>Remove
                            </a>
                        </div>
                    </div>
                </div>
                <!-- hidden repeater form end -->
                <div class="row settlementType03 mt-3" style="display: none;">
                    <div class="col-md-4 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-4">Starting Month:</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('starting_month', $monthList, null, [
                                        'placeholder' => 'Select Month',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-4">Number of Months :</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('number_of_month', null, [
                                        'id' => 'numberOfMonth',
                                        'placeholder' => 'Enter Number of Month',
                                        'class' => 'form-control numeric',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3">
                        <div class="row items">
                            <label class="col-form-label col-lg-4">Amount (Monthly):</label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('monthly_amount', null, [
                                        'id' => 'monthlyAmount',
                                        'placeholder' => 'Enter Amount',
                                        'class' => 'form-control',
                                        'readonly',
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
    <a href="{{ url()->previous() }}" class="btns btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
    <button type="submit" class="btns btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@section('script')
    <!-- validation js -->
    <script src="{{ asset('admin/validation/clearance.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
    <script type="text/javascript">
        window.onload = function() {
            var startDateInput = document.getElementsByClassName("nepali-datepicker-start-date");
            startDateInput.nepaliDatePicker({
                ndpYear: true,
                ndpMonth: true,
                ndpYearCount: 10
            });
        };
    </script>
    {{-- <script src="{{ asset('admin/js/nrj_custom.js') }}"></script> --}}
    <script>
        $(document).ready(function() {

            $(".settlementType").on('change', function() {
                var type = $(this).val();
                if (type == '1') {
                    $('.settlementType01').show();
                    $('.settlementType02').hide();
                    $('.settlementType03').hide();
                } else if (type == '2') {
                    $('.settlementType01').hide();
                    $('.settlementType02').show();
                    $('.settlementType03').hide();
                } else if (type == '3') {
                    $('.settlementType01').hide();
                    $('.settlementType02').hide();
                    $('.settlementType03').show();
                } else {
                    $('.settlementType01').hide();
                    $('.settlementType02').hide();
                    $('.settlementType03').hide();
                }
            });

            $('#addMore').on('click', function() {
                var html = $('#repeatForm').html();
                $('.form-repeater').append(html);

                $('.daterange-single').daterangepicker({
                    parentEl: '.content-inner',
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                });
                $('.nepali-datepicker-start-date').nepaliDatePicker({
                    ndpYear: true,
                    ndpMonth: true,
                    ndpYearCount: 10
                });

            });

            $(document).on('click', '.remove', function() {
                $(this).closest('.parent').hide();
            });

            $('#advanceAmount, #numberOfMonth').on('keyup', function() {
                calculateMonthlyAmount();
            });

            $('#organization').on('change', function() {
                var organizationId = $('#organization').val();
                $.ajax({
                    type: 'GET',
                    url: '/admin/payroll-setting/get-calendar-type',
                    data: {
                        organization_id: organizationId
                    },
                    success: function(data) {
                        var list = JSON.parse(data);
                        if (list.calendar_type == 'nep') {
                            $('.engDueDate').hide();
                            $('.nepDueDate').show();
                        } else {
                            $('.nepDueDate').hide();
                            $('.engDueDate').show();
                        }
                    }
                });
            });

            function calculateMonthlyAmount() {
                var advanceAmount = $('#advanceAmount').val();
                var numberOfMonth = $('#numberOfMonth').val();

                var monthlyAmount = parseFloat(advanceAmount / numberOfMonth).toFixed(2);;

                $('#monthlyAmount').val(monthlyAmount);
            }
        });
    </script>
@endSection
