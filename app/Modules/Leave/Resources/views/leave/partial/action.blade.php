<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Leave Application Form</legend>
                {!! Form::hidden('authUserType', auth()->user()->user_type, ['id' => 'authUserType']) !!}
                {!! Form::hidden('leaveYearStartDate', $currentLeaveyear->start_date_english, ['id' => 'leaveYearStartDate']) !!}
                {!! Form::hidden('generated_by', 10, []) !!}
                <div class="form-group row">
                    @if (isset($organizationId))
                        {!! Form::hidden('organization_id', $organizationId, []) !!}
                    @else
                        <div class="col-lg-6 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Organization :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('organization_id', $organizationList, null, [
                                            'class' => 'form-control select-search organizationFilter',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('organization_id'))
                                        <div class="error text-danger">{{ $errors->first('organization_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (isset($employeeId))
                        {!! Form::hidden('employee_id', $employeeId, ['id' => 'employeeId', 'class' => 'employee-filter']) !!}
                    @else
                        <div class="col-lg-6 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Employee :<span class="text-danger">
                                        *</span></label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('employee_id', [], null, [
                                            'id' => 'employeeId',
                                            'placeholder' => 'Select Employee',
                                            'class' => 'form-control select-search1 employee-filter',
                                        ]) !!}
                                    </div>
                                    @if ($errors->has('employee_id'))
                                        <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    {!! Form::hidden('logged_in_emp_id', optional(auth()->user()->userEmployer)->id, ['id' => 'logged_in_emp_id']) !!}

                    {!! Form::hidden('leave_year_id', $currentLeaveyear->id, ['id' => 'leave_year_id']) !!}
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Leave Category :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    <div class="p-1 rounded">
                                        @foreach ($leaveKindList as $key => $leaveKind)
                                            @php
                                                if ($key == '4') {
                                                    break;
                                                }
                                            @endphp
                                            <div class="custom-control custom-radio custom-control-inline">
                                                {{ Form::radio('leave_kind', $key, $key == 2, ['class' => 'custom-control-input leaveKind', 'id' => 'radio' . $key]) }}
                                                <label class="custom-control-label"
                                                    for="radio{{ $key }}">{{ $leaveKind }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if ($errors->has('leave_kind'))
                                    <div class="error text-danger">{{ $errors->first('leave_kind') }}</div>
                                @endif
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-6 mb-3 leaveTypeDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Type of Leave :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('leave_type_id', $leaveTypeList, null, [
                                        'id' => 'leaveType',
                                        'placeholder' => 'Select Leave Type',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                                <div id="warningMessage" class="alert alert-warning mt-2" style="display: none;"></div>
                            </div>
                            {{-- <p class="text-info">
                                <span id="leave_category_loader" style="display: none;"><i
                                        class="ph-spinner spinner"></i> </span>
                            </p> --}}
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3 leaveTypeDiv" style="display: none;">
                        <div class="row">
                            <!-- <label class="col-form-label col-lg-4">Number of Days :</label> -->
                            <input type="hidden" class="form-control" id="numberOfDays" value="0">
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3 substituteDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Substitute Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('substitute_date', [], null, ['class' => 'form-control select-search substitute_date']) !!}

                                    {{-- {!! Form::text('substitute_date', null, [
                                        'placeholder' => 'YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                    ]) !!} --}}


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3 startDateDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Start Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- {!! Form::text('start_date', null, [
                                        'id' => 'startDate',
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                        'readonly',
                                    ]) !!} --}}

                                    <x-utilities.date-picker default="nep" nep-date-attribute="nep_start_date"
                                        eng-date-attribute="start_date" mode="both" :date="request('start_date')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3 endDateDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-2">End Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {{-- {!! Form::text('end_date', null, [
                                        'id' => 'endDate',
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                        'readonly',
                                    ]) !!} --}}

                                    <x-utilities.date-picker default="nep" nep-date-attribute="nep_end_date"
                                        eng-date-attribute="end_date" mode="both" :date="request('end_date')" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3 halfTypeDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Type of Half Leave:<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('half_type', $halfTypeList, null, [
                                        'placeholder' => 'Select Type',
                                        'class' => 'form-control select-search',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-lg-12 mb-3 leaveDetailDiv" style="display: none;">
                        <div class="dayOff"></div>
                        <div id="leaveDetailForm">

                        </div>
                    </div> -->
                    <div class="col-lg-12 mb-3 datesDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Dates :<span class="text-danger"> *</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('dates', null, ['placeholder' => 'Enter mutiple dates', 'class' => 'form-control multiDate']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6 mb-3 substituteDiv" style="display: none;">
                        <div class="row">
                            <label class="col-form-label col-lg-4">Leave Date :<span class="text-danger">
                                    *</span></label>
                            <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::text('leave_date', null, [
                                        'placeholder' => 'YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'id' => 'leave_date',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div> --}}


                    <!-- Display notice -->
                    <div id="noticeList"></div>

                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Reason : <span class="text-danger">*</span></label>
                            <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('reason', null, [
                                        'rows' => 5,
                                        'placeholder' => 'Write reason here..',
                                        'class' => 'form-control',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-2">Attachment : </label>
                            <div class="col-lg-10">
                                <input type="file" name="attachments[]" class="form-control h-auto"
                                    accept=".jpg, .png, .doc, .pdf" multiple>
                            </div>
                        </div>
                    </div>
                    @if (isset($status))
                        {!! Form::hidden('status', 1, []) !!}
                    @else
                        <div class="col-lg-6 mb-3">
                            <div class="row">
                                <label class="col-form-label col-lg-4">Status :</label>
                                <div class="col-lg-8 form-group-feedback form-group-feedback-right">
                                    <div class="input-group">
                                        {!! Form::select('status', $statusList, null, ['class' => 'form-control select-search', 'id' => 'statusList']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="remainingLeaveDiv" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Remaining Leave Detail</legend>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div id="remainingLeaveDetail"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="approvalFlowWrapper">
            <div class="card-body">
                {{-- <legend class="text-uppercase font-size-sm font-weight-bold">Alternative Employee Detail
                    <p>
                        <small>Note: <span class="font-italic">Please select the employee on your absence.</span>
                        </small>
                    </p>
                </legend> --}}

                <div class="row">
                    {{-- <div class="col-lg-12 mb-3">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Employee :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::select('alt_employee_id', $employeeAlternativeList, null, [
                                        'placeholder' => 'Select Employee',
                                        'class' => 'form-control select-search alt-employee-filter',
                                        'id' => 'alt_employee_id',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-lg-12 mb-3 d-none">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Message :</label>
                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                <div class="input-group">
                                    {!! Form::textarea('alt_employee_message', null, [
                                        'rows' => 6,
                                        'placeholder' => 'Write message here..',
                                        'class' => 'form-control',
                                        'id' => 'alt_employee_message',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        {{-- <div class="col-lg-12 mb-3" id="approvalFlowWrapper"> --}}
                        <div class="row">
                            <label class="col-form-label col-lg-3">Approval Flow Details :</label>
                            <div class="col-lg-9">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <strong>First Approver:</strong> <span id="first_approver"
                                            class="ml-3"></span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Second Approver:</strong> <span id="second_approver"
                                            class="ml-3"></span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Third Approver:</strong> <span id="third_approver"
                                            class="ml-3"></span>
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Last Approver:</strong> <span id="last_approver"
                                            class="ml-3"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-center">
    <button id="submitBtn" type="submit" class="ml-2 btn btn-success btn-labeled btn-labeled-left"
        style="display: none;"><b><i class="icon-database-insert"></i></b>{{ $btnType }}</button>
</div>

@include('leave::leave.partial.script')


{{-- @section('script')
    <script src="{{ asset('admin/validation/leave.js') }}"></script>

    <script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            let minDate = new Date();

            var authUserType = $('#authUserType').val();
            if (authUserType == 'super_admin' || authUserType == 'hr') {
                minDate = $('#leaveYearStartDate').val();
            }

            $(".multiDate").flatpickr({
                mode: "multiple",
                dateFormat: "Y-m-d"
            });

            $('.employee-filter').on('change', function() {
                $('input[name=leave_kind]:radio:checked').trigger('change');
            });

            $('.leaveKind').on('change', function() {
                var leaveKind = $(this).val();
                $('#submitBtn').hide();
                // $('#leave_category_loader').show();

                switch (leaveKind) {
                    case '1':
                        $('.startDateDiv').show();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').show();
                        $('.leaveTypeDiv').show();
                        $('.leaveDetailDiv').hide();
                        $('.datesDiv').hide();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        $('#submitBtn').show();
                        // $('#leave_category_loader').hide();
                        break;
                    case '2':
                        $('.startDateDiv').show();
                        $('.endDateDiv').show();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').show();
                        $('.leaveTypeDiv').show();
                        $('.datesDiv').hide();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        // getEmployeeLeaveTypeList(leaveKind);
                        break;
                    case '3':
                        $('.startDateDiv').hide();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').hide();
                        $('.leaveTypeDiv').show();
                        $('.datesDiv').show();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        $('#submitBtn').show();
                        break;
                    case '4':
                        $('.startDateDiv').hide();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').hide();
                        $('.leaveTypeDiv').show();
                        $('.datesDiv').hide();
                        $('.substituteDiv').show();
                        $('#remainingLeaveDiv').show();
                        getRemainingLeaveDetail(leaveKind);
                        break;
                    default:
                        $('.startDateDiv').hide();
                        $('.endDateDiv').hide();
                        $('.halfTypeDiv').hide();
                        $('.leaveDetailDiv').hide();
                        $('.leaveTypeDiv').hide();
                        $('.datesDiv').hide();
                        $('.substituteDiv').hide();
                        $('#remainingLeaveDiv').hide();
                        break;
                }
            });

            var calendar_type = localStorage.getItem('calendar_type');
            $('#leaveType').on('change', function() {
                $('#submitBtn').hide();
                $('#startDate').val('');
                $('#endDate').val('');
                $('#noticeList').html('');

                var leaveType = $('#leaveType').val();
                var leaveTypeDetail = $('#leaveType-' + leaveType).attr('data-leave-type');
                leaveTypeDetail = jQuery.parseJSON(leaveTypeDetail);
                if (leaveTypeDetail['code'] == 'SIKLV') {
                    minDate = $('#leaveYearStartDate').val();
                    $('.substituteDiv').hide();

                } else if (leaveTypeDetail['code'] == 'SUBLV') {
                    $('.substituteDiv').show();
                    // $('.endDateDiv').hide();
                    $.ajax({
                        url: "{{ route('leave.getSubstituteDateList') }}",
                        method: 'GET',
                        data: {
                            employee_id: $('#employeeId').val(),
                        },
                        success: function(resp) {
                            let values = JSON.parse(resp)
                            if (values.length > 0) {
                                $('.substitute_date').empty();


                                $('.substitute_date').select2({
                                    data: values,
                                    placeholder: 'Choose Date', // Placeholder text
                                    templateResult: function(item) {
                                        return $('<span>' + item.date +
                                            '<em> <span class="text-danger">  [Expires: ' +
                                            item.expiry_date +
                                            ']</span> <span class="badge badge-success badge-pill">' +
                                            item.nod + '</span></em></span>');
                                    },
                                    templateSelection: function(item) {
                                        return $('<span>' + item.date +
                                            '<em> <span class="text-danger">  [Expires: ' +
                                            item.expiry_date +
                                            ']</span> <span class="badge badge-success badge-pill">' +
                                            item.nod + '</span></em></span>');
                                    },

                                });
                                $('.substitute_date').trigger('change');
                            }
                        }
                    });
                } else {
                    $('.substituteDiv').hide();
                    minDate = $('#leaveYearStartDate').val();
                }
                if (calendar_type == 'BS') {
                    nepStartDate(minDate)
                } else {
                    startDate(minDate);
                }

            });

            $('.substitute_date').on('change', function() {
                var leaveType = $('#leaveType').val();
                var leaveTypeDetail = $('#leaveType-' + leaveType).attr('data-leave-type');
                leaveTypeDetail = jQuery.parseJSON(leaveTypeDetail);

                var minDate = $(this).val();
                if (calendar_type == 'BS') {
                    engMinDate = (NepaliFunctions.BS2AD(minDate));
                    var maxDate = moment(engMinDate).add(leaveTypeDetail.max_substitute_days, 'days').format('YYYY-MM-DD')
                    nepMaxDate = (NepaliFunctions.AD2BS(maxDate));
                    nepStartDate(minDate,nepMaxDate)
                } else {
                    var maxDate = moment(minDate).add(leaveTypeDetail.max_substitute_days, 'days').format('YYYY-MM-DD')
                    startDate(minDate, maxDate);
                }

            })

            function nepStartDate(minDate,maxDate='') {
                $("#startDate").nepaliDatePicker({
                    onChange: function() {
                        $('#endDate').val('');
                        $('#noticeList').html('');

                        var leaveType = $('#leaveType').val();
                        var maxDays = $('#leaveType-' + leaveType).attr('data');
                        var startDate = $('#startDate').val();
                        var employeeId = $('#employeeId').val();
                        var params = {
                            'maxDays': maxDays,
                            'startDate': startDate,
                            'leaveType': leaveType,
                            'employeeId': employeeId,

                        };
                        preProcessData(params);
                    },
                    disableBefore: minDate,
                    disableAfter: maxDate,
                });

            }

            function nepEndDate(minDate, maxDate) {
                $("#endDate").nepaliDatePicker({
                    onChange: function() {
                        var employeeId = $('#employeeId').val();
                        var leaveType = $('#leaveType').val();
                        var maxDays = $('#leaveType-' + leaveType).attr('data');
                        var startDate = $('#startDate').val();
                        var endDate = $('#endDate').val();
                        var params = {
                            'employeeId': employeeId,
                            'leaveType': leaveType,
                            'maxDays': maxDays,
                            'startDate': startDate,
                            'endDate': endDate
                        };
                        postProcessData(params);
                    },
                    disableBefore: minDate,
                    disableAfter: maxDate,
                });

            }

            function startDate(minDate, maxDate = '') {
                $('#startDate').daterangepicker({
                    parentEl: '.content-inner',
                    singleDatePicker: true,
                    showDropdowns: true,
                    minDate: minDate,
                    maxDate: maxDate,

                    autoUpdateInput: false,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                    $('#endDate').val('');
                    $('#noticeList').html('');

                    var leaveType = $('#leaveType').val();
                    var maxDays = $('#leaveType-' + leaveType).attr('data');
                    var startDate = $('#startDate').val();
                    var employeeId = $('#employeeId').val();
                    var params = {
                        'maxDays': maxDays,
                        'startDate': startDate,
                        'leaveType': leaveType,
                        'employeeId': employeeId,

                    };
                    preProcessData(params);
                });
            }

            function endDate(id, minDate, maxDate) {
                $('#' + id).daterangepicker({
                    parentEl: '.content-inner',
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false,
                    minDate: minDate,
                    maxDate: maxDate,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                    var minDate = $(this).val();
                    var employeeId = $('#employeeId').val();
                    var leaveType = $('#leaveType').val();
                    var maxDays = $('#leaveType-' + leaveType).attr('data');
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var params = {
                        'employeeId': employeeId,
                        'leaveType': leaveType,
                        'maxDays': maxDays,
                        'startDate': startDate,
                        'endDate': endDate
                    };
                    postProcessData(params);
                });
            }

            function preProcessData(params) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('leave.preProcessData') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        params: params
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.endDate == null || response.numberOfDays < 0) {
                            $('.endDateDiv').hide();
                        } else {
                            $('.endDateDiv').show();
                            if (calendar_type == 'BS') {
                                nepEndDate($('#startDate').val(), response.endDate)
                            } else {
                                endDate('endDate', $('#startDate').val(), response.endDate);
                            }

                        }
                    }
                });
            }

            function postProcessData(params) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('leave.postProcessData') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        params: params
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.noticeList) {
                            $('#noticeList').addClass('col-lg-12');
                            $('#noticeList').html(response.noticeList);
                            if (response.restrictSave == 'true') {
                                $('#submitBtn').hide();
                            } else {
                                $('#submitBtn').show();
                            }
                        } else {
                            $('#noticeList').hide();
                        }
                    }
                });
            }

            function checkDayOff() {
                var total_days = $('[name^="number_of_days[]"]').serializeArray();
                sum_day = 0;
                $.each(total_days, function(i, v) {
                    if (v.value != "") {
                        sum_day += parseInt(v.value);
                    }
                });

                $('.dayOff').empty();
                $.ajax({
                    type: 'GET',
                    url: "{{ route('leave.check.dayoff') }}",
                    data: {
                        sum_day: 0,
                        start_date: $('[name^="start_date"]').val()
                    },
                    success: function(data) {
                        if (data !== 'undefined' && data !== '') {
                            $('<div/>', {
                                'class': 'card card-body',
                                html: '<h3>Day Off:</h3><p class="text-danger">' +
                                    data +
                                    '</p>',
                            }).appendTo($('.dayOff'));
                        }

                    }
                });
            }

            function getRemainingLeaveDetail(leaveKind) {
                employee_id = $('#employeeId').val();

                if (employee_id) {
                    $.ajax({
                        url: "{{ route('leave.getRemainingList') }}",
                        method: 'GET',
                        data: {
                            leave_year_id: $('#leave_year_id').val(),
                            employee_id: $('#employeeId').val(),
                            leave_kind: leaveKind,
                        },
                        success: function(resp) {
                            $('#remainingLeaveDetail').html(resp.view);

                            $('#leaveType').empty();
                            let option =
                                "<option selected disabled>Select Leave Type</option>";
                            resp.leaveTypeList.map(item => {
                                option +=
                                    `<option value=${item.key}>${item.value}</option>`
                            });
                            $('#leaveType').append(option);
                        }
                    });
                } else {
                    $('#remainingLeaveDetail').empty();
                }


            }

            // function getEmployeeLeaveTypeList(leaveKind) {
            //     $.ajax({
            //         url: "{{ route('leave.getList') }}",
            //         method: 'GET',
            //         data: {
            //             leave_year_id: $('#leave_year_id').val(),
            //             employee_id: $('#employeeId').val(),
            //             leave_kind: leaveKind,
            //         },
            //         success: function(resp) {
            //             $('#leaveDetailForm').html(resp);
            //         }
            //     });
            // }

            $('#alt_employee_id').on('change', function() {
                $('#alt_employee_message').closest('.col-lg-12').removeClass('d-none');
            });

        });
    </script>
@endSection --}}
