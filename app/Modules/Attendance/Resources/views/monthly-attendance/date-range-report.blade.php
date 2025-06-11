@extends('admin::layout')

@section('breadcrum')
    <a href="{{ route('monthlyAttendanceRange') }}" class="breadcrumb-item">Attendance</a>
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

        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        <th>Designation</th>
                        <th>Date</th>
                        <th>Early In</th>
                        <th>Late In</th>
                        <th>Day</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Working Hours</th>
                        <th>System OverTime(hr)</th>
                        <th>Actual OverTime(hr)</th>
                        <th>Check In Medium</th>
                        <th>Check Out Medium</th>
                        <th>Location</th>
                        <th>Actual Shift</th>
                        <th>Updated Shift</th>
                    </tr>
                </thead>
                <tbody>
                    @if (@$show)
                        @foreach ($emps as $empKey => $empData)
                            @php
                                $employee = $empData['employee'];
                                $attendances = $empData['attendance'];
                                $rowCount = count($attendances) ?: 1;
                            @endphp

                            @foreach ($attendances as $index => $attendance)

                                <tr>
                                    @if ($index === 0)
                                        <td rowspan="{{ $rowCount }}">{{ $empKey + 1 }}</td>
                                        <td rowspan="{{ $rowCount }}">{{ $employee->full_name }}</td>
                                        <td rowspan="{{ $rowCount }}">{{ $employee->designation->title ?? 'N/A' }}</td>
                                    @endif

                                    {{-- Attendance Data --}}
                                    <td>{{ $attendance['date'] }}</td>
                                    <td>{{ $attendance['early_time'] }}</td>
                                    <td>{{ $attendance['lateIn'] }}</td>
                                    <td>
                                        {{ $attendance['day'] }}
                                        @if (!empty($attendance['holidayName']))
                                            <br>
                                            <span
                                                class="badge badge-info badge-pill">{{ $attendance['holidayName'] }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $attendance['checkin'] ? date('h:i A', strtotime($attendance['checkin'])) : '' }}<br>
                                        <button type="button"
                                            class="badge badge-flat alpha-{{ $checkinColors[$attendance['checkinStatus']] ?? 'secondary' }} text-{{ $checkinColors[$attendance['checkinStatus']] ?? 'secondary' }}-800 border-{{ $checkinColors[$attendance['checkinStatus']] ?? 'secondary' }}-600">
                                            {{ $attendance['checkinStatus'] }}
                                        </button>
                                    </td>
                                    <td>
                                        @php
                                            $checkNextDay =
                                                !is_null($attendance['checkout']) &&
                                                $attendance['checkout'] < $attendance['checkin'];
                                        @endphp
                                        @if ($checkNextDay)
                                            <span class="text-success">(+1 day)</span><br>
                                        @endif
                                        {{ $attendance['checkout'] ? date('h:i A', strtotime($attendance['checkout'])) : '' }}<br>
                                        <button type="button"
                                            class="badge badge-flat alpha-{{ $checkoutColors[$attendance['checkoutStatus']] ?? 'secondary' }} text-{{ $checkoutColors[$attendance['checkoutStatus']] ?? 'secondary' }}-800 border-{{ $checkoutColors[$attendance['checkoutStatus']] ?? 'secondary' }}-600">
                                            {{ $attendance['checkoutStatus'] }}
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm alpha-{{ $colors[$attendance['status']] ?? 'secondary' }} text-{{ $colors[$attendance['status']] ?? 'secondary' }}-800 border-{{ $colors[$attendance['status']] ?? 'secondary' }}-600">
                                            {{ $attendance['status'] }}
                                        </button>
                                    </td>
                                    <td>{{ $attendance['total_working_hr'] }}</td>
                                    <td>{{ $attendance['overStay'] }}</td>
                                    <td>{{ $attendance['otValue'] }}</td>
                                    <td>{{ $attendance['checkin_from'] ?? '---' }}</td>
                                    <td>{{ $attendance['checkout_from'] ?? '---' }}</td>
                                    <td>
                                        @if (!empty($attendance['coordinates']))
                                            <i class="icon-location3 getLocation" style="color: blue"
                                                data-location="{{ $attendance['coordinates'] }}"></i>
                                        @endif
                                    </td>
                                    <td>{{ $attendance['actual_shift_name'] }}</td>
                                    <td>
                                        @if ($attendance['actual_shift_name'] != $attendance['updated_shift_name'])
                                            {{ $attendance['updated_shift_name'] }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>

        </div>

        @if (@$show)
            <div class="col-12">
                <span class="float-right pagination align-self-end mt-3">
                    {{ $emps->appends(request()->all())->links() }}
                </span>
            </div>
        @endif
    </div>
@endsection
