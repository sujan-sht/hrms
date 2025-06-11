@extends('admin::layout')

@section('breadcrum')
<a href="{{ route('monthlyAttendanceRange') }}" class="breadcrumb-item"> Date Range Attendance</a>
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

@include('attendance::monthly-attendance.partial.date-range-filter')

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
                @if (@$emps)
                {{-- @dd($emps); --}}
                @foreach ($emps as $key => $emp)
                {{-- @dd($emp); --}}
                @php
                $shiftGroupMember = App\Modules\Shift\Entities\ShiftGroupMember::where(
                'group_member',
                $emp->id,
                )
                ->orderBy('id', 'DESC')
                ->first();

                $empShift = optional(optional($shiftGroupMember)->group)->shift;

                if ($empShift && !empty($empShift->start_time) && !empty($empShift->end_time)) {
                $perDayShiftRaw = App\Helpers\DateTimeHelper::getTimeDiff(
                $empShift->start_time,
                $empShift->end_time,
                );
                } else {
                $perDayShiftRaw = 8;
                }

                $perDayShift = is_numeric($perDayShiftRaw) ? (float) $perDayShiftRaw : 8;
                @endphp



                @foreach ($date_range as $each_date)
                @php
                $datakey = $each_date->format('Y-m-d');
                @endphp


                <tr>
                    <td>#{{ $loop->iteration }} </td>
                    @if (Auth::user()->user_type != 'employee')
                    <td class="d-flex text-nowrap">
                        <div class="media">
                            <div class="mr-3">
                                <a href="#">
                                    <img src="{{ $emp->getImage() }}" class="rounded-circle" width="40" height="40"
                                        alt="">
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
                    @endif

                    @php
                    $date = $emp[$datakey];
                    @endphp
                    <td>
                        {{ $datakey }}
                    </td>
                    <td>
                        {{ date_converter()->eng_to_nep_convert($datakey) }}
                    </td>

                    <td>

                        {{ date('l', strtotime($datakey)) }}

                        <br>
                        @if (isset($date['holidayName']) && $date['holidayName'] != '')
                        <span class="badge badge-info badge-pill ml-auto ml-lg-0">
                            {{ $date['holidayName'] }}
                        </span>
                        @endif
                    </td>

                    @php
                    $reqTypeTitle = 'R: ';
                    if ($atdReportRepo->checkODDRequestExist($datakey, $emp->id)) {
                    $reqTypeTitle = 'ODD: ';
                    } elseif ($atdReportRepo->checkWFHRequestExist($datakey, $emp->id)) {
                    $reqTypeTitle = 'WFH: ';
                    } elseif ($atdReportRepo->checkForceAtdRequestExist($datakey, $emp->id)) {
                    $reqTypeTitle = 'FAR: ';
                    }
                    @endphp
                    <td>
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
                        if ($date['checkout'] < $date['checkin']) { $checkNextDay=true; } @endphp @if
                            ($date['checkout']) @if ($checkNextDay) <span class="text-success">(+1 day)</span>
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


                </tr>
                @endforeach


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
                @endif
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

@endsection
