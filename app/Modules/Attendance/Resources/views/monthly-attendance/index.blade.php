@extends('admin::layout')

@section('breadcrum')
    <a href="{{ route('monthlyAttendance') }}" class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('atdReportRepo', '\App\Modules\Attendance\Repositories\AttendanceReportRepository')


@section('content')

    @php
        $colors = [
            'A' => 'danger',
            'L' => 'indigo',
            'P' => 'success',
            'H' => 'info',
            'D' => 'slate',
            'P*' => 'primary',
            'HL' => 'violet',
        ];
        $index = [
            'A' => 'Absent',
            'L' => 'Leave',
            'P' => 'Present',
            'H' => 'Holiday',
            'D' => 'Day off',
            'P*' => 'Partial',
            'HL' => 'Half Leave',
        ];

        $checkinColors = ['Late Arrival' => 'danger', 'On Time' => 'primary', 'Early Arrival' => 'success', '' => ''];
        $checkoutColors = [
            'Early Departure' => 'danger',
            'On Time' => 'primary',
            'Late Departure' => 'success',
            '' => '',
        ];

        $attRequestedcolors = [
            '1' => 'secondary',
            '2' => 'primary',
            '3' => 'success',
            '4' => 'danger',
            '5' => 'warning',
            '-' => 'info',
        ];

    @endphp

    @include('attendance::monthly-attendance.partial.filter')

    <div class="card">
        <div class="card-body">
            <legend class="text-uppercase font-size-sm font-weight-bold">Indexes</legend>
            <div class="row">
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm alpha-danger text-danger-800 border-danger-600">A</button>
                    <span class="text-danger-800 ml-1">Absent</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm alpha-slate text-slate-800 border-slate-600">D</button>
                    <span class="text-slate-800 ml-1">Day Off</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm alpha-info text-info-800 border-info-600">H</button>
                    <span class="text-info-800 ml-1">Holiday</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm alpha-indigo text-indigo-800 border-indigo-600">L</button>
                    <span class="text-indigo-800 ml-1">Leave</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm alpha-primary text-primary-800 border-primary-600">P*</button>
                    <span class="text-primary-800 ml-1">Partial</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm alpha-success text-success-800 border-success-600">P</button>
                    <span class="text-success-800 ml-1">Present</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm alpha-violet text-violet-800 border-violet-600">HL</button>
                    <span class="text-violet-800 ml-1">Half Leave</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Attendance</h6>
                All the Attendance Information will be listed below. You can view the data.
            </div>
            @if ($show)
                @if (Auth::user()->user_type != 'employee')
                    <div class="mt-1">
                        <a href="{{ route('exportMonthlyAttendance', request()->all()) }}" class="btn btn-success"><i
                                class="icon-file-excel"></i> Export</a>
                    </div>
                    {{-- <div class="mt-1 ml-1">
                        <a href="{{ route('downloadMonthlyAttendance', request()->all()) }}"
                            class="btn btn-warning rounded-pill"><i class="icon-file-download"></i> Download</a>
                    </div> --}}
                @endif
            @endif
        </div>
    </div>
    <div class="card card-body">
        @if ($show)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>S.N</th>
                            @if (Auth::user()->user_type != 'employee')
                                <th>Employee Name</th>
                                <th>Sub-Function</th>
                                <th>Designation</th>
                                {{-- <th>Date of Join</th> --}}
                            @endif
                            <th>Date(AD)</th>
                            <th>Date(BS)</th>
                            <th>Day</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Late Arrival</th>
                            <th>Early Departure</th>
                            <th>Status</th>
                            <th>Working Hours</th>
                            <th>System OverTime(hr)</th>
                            <th>Actual OverTime(hr)</th>
                            <th>Actual Shift</th>
                            <th>Updated Shift</th>

                            {{-- @if (Auth::user()->user_type == 'employee' && $menuRoles->assignedRoles('attendanceRequest.create'))
                                <th>Request</th>
                            @endif --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $checkIn = 0;
                            $checkOut = 0;
                            $total_work_hr = 0;
                        @endphp
                        @foreach ($emps as $key => $emp)
                            @php
                                $empShift = optional(
                                    optional(
                                        App\Modules\Shift\Entities\ShiftGroupMember::where('group_member', $emp->id)
                                            ->orderBy('id', 'DESC')
                                            ->first(),
                                    )->group,
                                )->shift;
                                $perDayShift = isset($empShift)
                                    ? App\Helpers\DateTimeHelper::getTimeDiff(
                                        $empShift->start_time,
                                        $empShift->end_time,
                                    )
                                    : 8;

                            @endphp

                            @for ($i = 1; $i <= $days; $i++)
                                <tr>
                                    <td>#{{ $i }} </td>
                                    @if (Auth::user()->user_type != 'employee')
                                        <td class="d-flex text-nowrap">
                                            <div class="media">
                                                <div class="mr-3">
                                                    <a href="#">
                                                        <img src="{{ $emp->getImage() }}" class="rounded-circle"
                                                            width="40" height="40" alt="">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-title font-weight-semibold">{{ $emp->full_name }}
                                                    </div>
                                                    <span class="text-muted">ID: {{ $emp->employee_code }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ optional($emp->department)->title }}</td>
                                        <td>{{ optional($emp->designation)->title }}</td>
                                        {{-- <td>{{ $emp->nepali_join_date }}</td> --}}
                                    @endif
                                    @php
                                        $dateVal = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                                        $date =
                                            $emp->date[
                                                $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i)
                                            ];
                                    @endphp
                                    <td>
                                        @if ($calendarType == 'eng')
                                            {{ $dateVal }}
                                        @else
                                            {{ date_converter()->nep_to_eng_convert($dateVal) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{-- {{ date('Y-m-d', strtotime($dateVal)) }} --}}
                                        @if ($calendarType == 'eng')
                                            {{ date_converter()->eng_to_nep_convert($dateVal) }}
                                        @else
                                            {{ $dateVal }}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            if ($emp->calendarType == 'nep') {
                                                $dateVal = date_converter()->nep_to_eng_convert($dateVal);
                                            }
                                        @endphp
                                        {{ date('l', strtotime($dateVal)) }}

                                        <br>
                                        @if (isset($date['holidayName']) && $date['holidayName'] != '')
                                            <span class="badge badge-info badge-pill ml-auto ml-lg-0">
                                                {{ $date['holidayName'] }}
                                            </span>
                                        @endif
                                    </td>

                                    @php
                                        $reqTypeTitle = 'R: ';
                                        if ($atdReportRepo->checkODDRequestExist($dateVal, $emp->id)) {
                                            $reqTypeTitle = 'ODD: ';
                                        } elseif ($atdReportRepo->checkWFHRequestExist($dateVal, $emp->id)) {
                                            $reqTypeTitle = 'WFH: ';
                                        } elseif ($atdReportRepo->checkForceAtdRequestExist($dateVal, $emp->id)) {
                                            $reqTypeTitle = 'FAR: ';
                                        }
                                    @endphp
                                    <td>
                                        {{-- @if (date('h:i', strtotime($date['checkin'])) != date('h:i', strtotime($date['checkin_original']))) --}}
                                        @if ($date['checkin'] != $date['checkin_original'])
                                            {{ $date['checkin_from'] && $date['checkin_from'] == 'form' ? 'F: ' : $reqTypeTitle }}
                                            {{ date('h:i A', strtotime($date['checkin'])) }}
                                        @endif
                                        <br>

                                        <button type="button"
                                            class="badge badge-flat alpha-{{ $checkinColors[$date['checkinStatus']] }} text-{{ $checkinColors[$date['checkinStatus']] }}-800 border-{{ $checkinColors[$date['checkinStatus']] }}-600">{{ $date['checkinStatus'] }}
                                        </button>
                                        <br>

                                        {{ $date['checkin_original'] ? date('h:i A', strtotime($date['checkin_original'])) : '' }}
                                        @if ($date['late_arrival'])
                                            @php

                                                $checkIn++;
                                            @endphp
                                        @endif
                                    </td>

                                    <td>
                                        @php
                                            $checkNextDay = false;
                                            if ($date['checkout'] < $date['checkin']) {
                                                $checkNextDay = true;
                                            }
                                        @endphp
                                        @if ($date['checkout'])
                                            @if ($checkNextDay)
                                                <span class="text-success">(+1 day)</span>
                                                <br>
                                            @endif
                                            @if ($date['checkout'] != $date['checkout_original'])
                                                {{ $date['checkout_from'] && $date['checkout_from'] == 'form' ? 'F: ' : $reqTypeTitle }}
                                                {{ date('h:i A', strtotime($date['checkout'])) }}
                                            @endif
                                        @endif


                                        <br>

                                        <button type="button"
                                            class="badge badge-flat alpha-{{ $checkoutColors[$date['checkoutStatus']] }} text-{{ $checkoutColors[$date['checkoutStatus']] }}-800 border-{{ $checkoutColors[$date['checkoutStatus']] }}-600">{{ $date['checkoutStatus'] }}
                                        </button>

                                        <br>
                                        {{ $date['checkout_original'] ? date('h:i A', strtotime($date['checkout_original'])) : '' }}
                                        @if ($date['early_departure'])
                                            @php

                                                $checkOut++;
                                            @endphp
                                        @endif
                                    </td>

                                    <td>{{ $date['late_arrival'] }}</td>
                                    <td>{{ $date['early_departure'] }}</td>
                                    <td>
                                        <a href="{{ route('attendanceRequest.create', ['employee_id' => $emp->employee_id, 'date' => $dateVal]) }}"
                                            target="_blank">
                                            <button type="button" data-popup="tooltip" data-placement="top"
                                                data-original-title="{{ $index[$date['status']] }}"
                                                class="btn btn-sm alpha-{{ $colors[$date['status']] }} text-{{ $colors[$date['status']] }}-800 ml-2 border-{{ $colors[$date['status']] }}-600">
                                                {{ $date['status'] }}
                                                @if ($date['leave_status'])
                                                    <sub class="text-danger">HL</sub>
                                                @endif
                                            </button>
                                        </a>
                                    </td>

                                    <td>{{ $date['total_working_hr'] }}</td>
                                    <td>{{ $date['over_stay'] }} </td>
                                    <td>{{ $date['ot_value'] }} </td>
                                    <td>{{ $date['actual_shift'] }}</td>
                                    <td>
                                        @if ($date['actual_shift'] != $date['updated_shift'])
                                            {{ $date['updated_shift'] }}
                                        @endif
                                    </td>

                                    @php
                                        $total_work_hr = (float) $date['total_working_hr'] + $total_work_hr;
                                    @endphp

                                    {{-- @if (Auth::user()->user_type == 'employee' && $menuRoles->assignedRoles('attendanceRequest.create'))
                                        <td class="d-flex">
                                            @php
                                                $checkInHtml = '';
                                                $checkOutHtml = '';

                                                foreach ($date['atdRequest'] as $atdRequestKey => $atdRequest) {
                                                    if ($atdRequest->type == 1 || $atdRequest->type == 4) {
                                                        $checkInHtml .= $atdRequest->getType() . ': ' . $atdRequest->getStatus();
                                                    }

                                                    if ($atdRequest->type == 2 || $atdRequest->type == 3) {
                                                        $checkOutHtml .= $atdRequest->getType() . ': ' . $atdRequest->getStatus();
                                                    }
                                                }
                                            @endphp

                                            @forelse ($date['atdRequest'] as $atdRequestKey => $atdRequest)
                                                @if ($atdRequest->type == 1 || $atdRequest->type == 4)
                                                    @if ($atdRequest->status == 1)
                                                        {!! Form::open([
                                                            'route' => 'attendanceRequest.cancelAttendanceRequest',
                                                            'method' => 'PUT',
                                                            'class' => 'form-horizontal',
                                                            'role' => 'form',
                                                        ]) !!}
                                                            {!! Form::hidden('id', $atdRequest->id, ['id' => 'attendanceId']) !!}
                                                            {!! Form::hidden('employee_id', $emp->emp_id, ['class' => 'employee_id']) !!}
                                                            {!! Form::hidden('status', $value = 5) !!}
                                                            {!! Form::hidden('url', request()->url()) !!}

                                                            <button class="btn btn-outline-warning btn-icon mr-1 confirmCancel"
                                                                data-placement="bottom" data-popup="tooltip"
                                                                data-original-title="{{ $checkInHtml }}">
                                                                <i class="icon-cancel-square"></i>
                                                            </button>

                                                        {!! Form::close() !!}


                                                        <button class="btn btn-outline-primary btn-icon mr-1 requestAttendanceClick"
                                                        data-toggle="modal" data-target="#requestAttendanceCheckoutType"
                                                        link="{{ route('attendanceRequest.store') }}"
                                                        getDate="{{ $dateVal }}" calendarType={{ $emp->calendarType }}
                                                        empId={{ $emp->id }} data-placement="bottom" data-popup="tooltip"
                                                        data-original-title="{{ $checkOutHtml }}">
                                                        <span>Out</span></button>
                                                    @else
                                                        <button
                                                            class="btn btn-outline-primary btn-icon mr-1 requestAttendanceClick"
                                                            data-toggle="modal" data-target="#requestAttendanceCheckinType"
                                                            link="{{ route('attendanceRequest.store') }}"
                                                            getDate="{{ $dateVal }}"
                                                            calendarType={{ $emp->calendarType }} empId={{ $emp->id }}
                                                            data-placement="bottom" data-popup="tooltip"
                                                            data-original-title="{{ $checkInHtml }}">
                                                            <span>In</span></button>


                                                    @endif
                                                @endif
                                            @empty
                                                <button class="btn btn-outline-primary btn-icon mr-1 requestAttendanceClick"
                                                    data-toggle="modal" data-target="#requestAttendanceCheckinType"
                                                    link="{{ route('attendanceRequest.store') }}"
                                                    getDate="{{ $dateVal }}" calendarType={{ $emp->calendarType }}
                                                    empId={{ $emp->id }} data-placement="bottom" data-popup="tooltip"
                                                    data-original-title="{{ $checkInHtml }}">
                                                    <span>In</span></button>
                                            @endforelse
                                        </td>
                                    @endif --}}

                                </tr>
                            @endfor

                            @if (Auth::user()->user_type != 'employee')
                                <td colspan="7" class="text-center">Total </td>
                            @else
                                <td colspan="6" class="text-center">Total </td>
                            @endif
                            <td>{{ $checkIn . '/' . $days }} days</td>
                            <td>{{ $checkOut . '/' . $days }} days</td>

                            <td>{{ $emp->total_late_arrival }}</td>
                            <td>{{ $emp->total_early_departure }}</td>
                            <td></td>
                            <td>{{ $total_work_hr . '/' . $days * $perDayShift }}</td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                @if (isset($emps))
                    {{ $emps->appends(request()->all())->links() }}
                @endif
            </span>
        </div>

    </div>

    <!-- Attendance Request Check In Modal Start-->
    {{-- <div id="requestAttendanceCheckinType" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Request Attendance</h5>0
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'attendanceRequest.store',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}

                    <input type="hidden" name="employee_id" class="employeeId">
                    <input type="hidden" name="fromAttOverview" value="1">
                    <input type="hidden" name="calendar_type" class="calendarType">

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Date</label>
                        <div class="col-lg-9">
                            {!! Form::text('date', $value = null, [
                                'class' => 'form-control dateClass',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Type<span class="text-danger">
                                *</span></label>
                        <div class="col-lg-9">
                            @php
                                $checkInType = [
                                    '1' => 'Missed Check In',
                                    // '2' => 'Missed Check Out',
                                    // '3' => 'Early Departure Request',
                                    '4' => 'Late Arrival Request',
                                ];
                            @endphp
                            {!! Form::select('type', $checkInType, 0$value = null, [
                                'placeholder' => 'Choose Type',
                                'class' => 'form-control select2',
                                'required',
                                'id' => 'requestType',
                            ]) !!}
                            <span class="errorType"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Time<span class="text-danger">
                                *</span></label>
                        <div class="col-lg-9">
                            @php
                                $time = '10:00';
                            @endphp
                            <div class="input-group">
                                {!! Form::text('time', $value = $time, ['class' => 'form-control', 'id' => 'start-timepicker', 'required']) !!}
                                <span class="input-group-text"><i class="icon icon-watch2"></i></span>
                                <span class="erroTime"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Detail/Reason<span class="text-danger">
                                *</span></label>
                        <div class="col-lg-9">
                            {!! Form::textarea('detail', $value = null, [
                                'placeholder' => 'Enter Reason',
                                'class' => 'form-control',
                                'rows' => 4,
                                'required',
                            ]) !!}
                            <span class="erroReason"></span>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="ml-2 btn btn-success">Request</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Attendance Request Check In Modal End-->

    <!-- Attendance Request Check Out Modal Start-->
    {{-- <div id="requestAttendanceCheckoutType" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Request Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open([
                        'route' => 'attendanceRequest.store',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}

                    <input type="hidden" name="employee_id" class="employeeId">
                    <input type="hidden" name="fromAttOverview" value="1">
                    <input type="hidden" name="calendar_type" class="calendarType">

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Date</label>
                        <div class="col-lg-9">
                            {!! Form::text('date', $value = null, [
                                'class' => 'form-control dateClass',
                                'readonly',0
                            ]) !!}
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Type<span class="text-danger">
                                *</span></label>
                        <div class="col-lg-9">
                            @php
                                $checkOutType = [
                                    '2' => 'Missed Check Out',
                                    '3' => 'Early Departure Request',
                                ];
                            @endphp
                            {!! Form::select('type', $checkOutType, $value = null, [
                                'placeholder' => 'Choose Type',
                                'class' => 'form-control select2',
                                'required',
                                'id' => 'requestType',
                            ]) !!}
                            <span class="errorType"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Time<span class="text-danger">
                                *</span></label>
                        <div class="col-lg-9">
                            @php
                                // if (isset($request)) {
                                //     $time = date('H:i', strtotime($request->time));
                                // } else {
                                $time = '10:00';
                                // }
                            @endphp
                            <div class="input-group">
                                {!! Form::text('time', $value = $time, ['class' => 'form-control', 'id' => 'start-timepicker', 'required']) !!}
                                <span class="input-group-text"><i class="icon icon-watch2"></i></span>
                                <span class="erroTime"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3" for="">Detail/Reason<span class="text-danger">
                                *</span></label>
                        <div class="col-lg-9">
                            {!! Form::textarea('detail', $value = null, [
                                'placeholder' => 'Enter Reason',
                                'class' => 'form-control',
                                'rows' => 4,
                                'required',
                            ]) !!}
                            <span class="erroReason"></span>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="ml-2 btn btn-success">Request</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Attendance Request Check Out Modal End-->
@endsection

{{-- @section('script')
    <script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>

    <script>0
        $(document).on('click', '.requestAttendanceClick', function() {
            $('.dateClass').val($(this).attr('getDate'));
            $('.calendarType').val($(this).attr('calendarType'));
            $('.employeeId').val($(this).attr('empId'));
            $('#start-timepicker').clockTimePicker();
            $('.select2').select2();
            $("#requestType").val(null).trigger("change");

        });

        $(document).ready(function() {
            //check request type
            $('#requestType').on('change', function() {
                $.ajax({
                    type: 'GET',
                    url: '/attendance-request/checkRequestExist',
                    data: {
                        calendarType: $('.calendarType').val(),
                        date: $('.dateClass').val(),
                        empId: $('.employeeId').val(),
                        requestType: $('#requestType').val()
                    },
                    success: function(resp) {
                        if (resp != null && resp == 1) {
                            $('#requestType').css('border-color', 'red');
                            $('.errorType').html(
                                '<i class="icon-thumbs-down3 mr-1"></i> Attendance Request Already Exists.'
                            );
                            $('.errorType').removeClass('text-success');
                            $('.errorType').addClass('text-danger');
                            $('#requestType').focus();
                            $("#requestType").val(null).trigger("change");

                            event.preventDefault();
                        } else {
                            $('#requestType').css('border-color', 'green');
                            $('.errorType').html('');
                            $('.errorType').removeClass('text-danger');
                            $('.errorType').addClass('text-success');
                        }
                    }
                });
            });
            //

            //confirm cancel
            $(document).ready(function() {
                $('.confirmCancel').on('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, cancel it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'cancelled!',
                                text: 'Your file has been cancelled.',
                                icon: 'success',
                                showCancelButton: false,
                                showConfirmButton: false,
                            });
                            $(this).closest('form').submit();
                        }
                    });
                });
            });
            //
        });
    </script>
@endsection --}}
