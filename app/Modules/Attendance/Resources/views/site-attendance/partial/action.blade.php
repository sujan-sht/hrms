<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>

                <div class="row mb-3">
                    {!! Form::hidden('calendar_type', $value='eng', []) !!}

                    @if (auth()->user()->user_type != 'employee')
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Employee<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('employee_id', $employees, request()->get('employee_id') ? request()->get('employee_id') : null, [
                                        'id' => 'employee_id',
                                        'class' => 'form-control select-search',
                                        'data-toggle' => 'select2',
                                        'placeholder' => 'Select Employee'
                                    ]) !!}
                                    @if ($errors->first('employee_id') != null)
                                        <ul class="parsley-errors-list filled" aria-hidden="false">
                                            <li class="parsley-required">{{ $errors->first('employee_id') }}</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="employee_id" value="{{ auth()->user()->emp_id }}" id = 'employee_id'>
                    @endif

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Type<span class="text-danger"> *</span></label>
                            </div>
                            <div class="col-md-12">
                                {!! Form::select('type', $type, $value = null, [
                                    'placeholder' => 'Choose Type',
                                    'class' => 'form-control select-search',
                                    'id' => 'requestType'
                                ]) !!}
                            </div>
                            {{-- <span class="errorType"></span> --}}
                        </div>
                    </div>

                    @if (setting('calendar_type') == 'BS')

                        {!! Form::hidden('calendar_type', $value='nep', ['id' => 'calendarType']) !!}

                        {{-- <div class="col-md-6 mt-3">
                            <div id="customTitle">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">Date<span class="text-danger"> *</span></label>
                                    </div>
                                    <div class="col-md-12">
                                        {!! Form::text('nepali_date', request()->get('nepali_date') ? request()->get('nepali_date') : null, [
                                            'placeholder' => 'Choose Date',
                                            'class' => 'form-control nepali-calendar',
                                            // 'id' => 'maxDateId',
                                        ]) !!}
                                    </div>
                                    <span class="errorDate"></span>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-md-6 mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Start Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('start_date', request()->get('start_date') ? request()->get('start_date') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepali-calendar',
                                        'id' => 'nepaliStartMaxDate',
                                        'readonly',
                                    ]) !!}
                                </div>
                                <span class="errorStartDate"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3 endDate" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">End Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('end_date', request()->get('end_date') ? request()->get('end_date') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control nepali-calendar',
                                        'id' => 'nepaliEndMaxDate',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    @else
                        {!! Form::hidden('calendar_type', $value='eng', ['id' => 'calendarType']) !!}

                        <div class="col-md-6 mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Start Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('start_date', request()->get('start_date') ? request()->get('start_date') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'id' => 'startMaxDate',
                                        'readonly',
                                    ]) !!}
                                </div>
                                <span class="errorStartDate"></span>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3 endDate" style="display: none;">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">End Date<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::text('end_date', request()->get('end_date') ? request()->get('end_date') : null, [
                                        'placeholder' => 'e.g: YYYY-MM-DD',
                                        'class' => 'form-control daterange-single',
                                        'id' => 'endMaxDate',
                                        'readonly',
                                    ]) !!}
                                </div>
                                {{-- <span class="errorDate"></span> --}}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 time">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Time<span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-12">
                                @php
                                    if (isset($request)) {
                                        $time = date('H:i', strtotime($request->time));
                                    } else {
                                        $time = ' ';
                                    }
                                @endphp
                                <div class="input-group">
                                    {!! Form::time('time', $value = $time, ['class' => 'form-control timeVal']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 kind" style="display:none">
                        <div id="customTitle">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Kind<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::select('kind', $kind, $value = null, [
                                        'placeholder' => 'Choose Kind',
                                        'class' => 'form-control select-search',
                                        'id' => 'kind'
                                    ]) !!}
                                </div>
                                {{-- <span class="errorType"></span> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div id="noticeList" class=""></div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <div id="customTitle">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Detail/Reason<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-md-12">
                                    {!! Form::textarea('detail', $value = null, [
                                        'placeholder' => 'Enter Reason',
                                        'class' => 'form-control',
                                        'rows' => 4,
                                    ]) !!}
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
            @if (setting('calendar_type') == 'BS')
                <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
            @else
                <button type="submit" class="btn btn-success btn-labeled btn-labeled-left" id="submitBtn" style="display: none"><b><i
                class="icon-database-insert"></i></b>{{ $btnType }}</button>
            @endif
        </div>
    </div>

</div>

<script src="{{ asset('admin/global/js/plugins/pickers/daterangepicker.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/picker_date.js') }}"></script>
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/validation/attendanceRequest.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#employee_id').on('change', function() {
            $('#submitBtn').hide()
            $('#startMaxDate').val('')
            $('#endMaxDate').val('')
            $('.timeVal').val('')
            $('#kind').val('')
        });

        var type = $('#requestType').val();
        if(type == '5' || type == '6' || type == '7') {
            $('.time').hide()
            $('.kind').show()
            $('.endDate').show()
        } else {
            $('.time').show()
            $('.kind').hide()
            $('.endDate').hide()
        }

        $('#requestType').on('change', function() {
            var requestType = $(this).val();
            if(requestType == '5' || requestType == '6' || requestType == '7') {
                $('.time').hide()
                $('.kind').show()
                $('.endDate').show()
            } else {
                $('.time').show()
                $('.kind').hide()
                $('.endDate').hide()
            }

            $('#submitBtn').hide()
            $('#startMaxDate').val('')
            $('#endMaxDate').val('')
            $('.timeVal').val('')
            $('#kind').val('')

            var today = new Date();
            var yyyy = today.getFullYear();
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var dd = String(today.getDate()).padStart(2, '0');
            today = yyyy + '-' + mm + '-' + dd;

            startDate('startMaxDate', today, '', '', today);
        });

        function startDate(id, startDate = '', endDate = '', minDate = '', maxDate = '' ) {
            $('#'+id).daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                showDropdowns: true,
                startDate: startDate,
                endDate: endDate,
                minDate: minDate,
                // maxDate: maxDate,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'))
                $('#endMaxDate').val('')
                var employeeId = $('#employee_id').val()
                var type = $('#requestType').val()
                var startDate = $('#startMaxDate').val()
                var params = {
                    'employeeId': employeeId,
                    'type': type,
                    'startDate': startDate,
                };
                preProcessData(params, maxDate);
            });
        }

        function preProcessData(params, maxDate) {
            $.ajax({
                type: "GET",
                url: "{{ route('attendanceRequest.checkRequestExist') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    empId: params['employeeId'],
                    requestType: params['type'],
                    date: params['startDate'],
                },
                dataType: 'json',
                success: function(response) {
                    if(response.exist == true){
                        $('#startMaxDate').css('border-color', 'red')
                        $('.errorStartDate').html(
                            '<i class="icon-thumbs-down3 mr-1"></i> Attendance Request Already Exists.'
                        );
                        $('.errorStartDate').removeClass('text-success')
                        $('.errorStartDate').addClass('text-danger')
                        $('#startMaxDate').focus()
                        $("#startMaxDate").val(null)
                    }else{
                        $('#startMaxDate').css('border-color', 'green')
                        $('.errorStartDate').html('')
                        $('.errorStartDate').removeClass('text-danger')
                        $('.errorStartDate').addClass('text-success')
                        $('#submitBtn').show()

                        var reqType = $('#requestType').val()
                        if(reqType == 5 || reqType == 6 || reqType == 7){
                            endDate('endMaxDate', '', '', params['startDate'], maxDate);
                        }
                    }
                }
            });
        }

        function endDate(id, startDate = '', endDate = '', minDate = '', maxDate = '') {
            $('#' + id).daterangepicker({
                parentEl: '.content-inner',
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                startDate: minDate,
                endDate: maxDate,
                minDate: minDate,
                // maxDate: maxDate,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'))
                var employeeId = $('#employee_id').val()
                var startDate = $('#startMaxDate').val()
                var endDate = $('#endMaxDate').val()
                var type = $('#requestType').val()
                var params = {
                    'employeeId': employeeId,
                    'type': type,
                    'startDate': startDate,
                    'endDate': endDate
                }
                postProcessData(params)
            });
        }

        function postProcessData(params) {
            $.ajax({
                type: "POST",
                url: "{{ route('attendanceRequest.postProcessData') }}",
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
    });
</script>
