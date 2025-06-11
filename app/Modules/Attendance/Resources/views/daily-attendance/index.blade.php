@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">Attendance Report</a>
@endsection

@section('css')
    <style>
        .error {
            color: red;
        }
    </style>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

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

    @include('attendance::daily-attendance.partial.filter')

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
                <h6 class="media-title font-weight-semibold">Attendance Report</h6>
                All the Attendance Information will be listed below. You can view the data.
            </div>

            @if ($show)
                <div id="showHide" class="mt-1">
                    <a href="javascript:;" class="btn btn-primary btn-right  viewDetail" style="margin-right: 5px;"><i
                            class="icon-history"></i> View Log Detail</a>
                </div>
                <div class="mt-1">
                    <a href="{{ route('exportDailyAttendanceReport', request()->all()) }}"
                        class="btn btn-success export-btn"><i class="icon-file-excel"></i> Export</a>
                </div>
                <div class="mt-1 ml-1">
                    <a href="{{ route('downloadDailyAttendanceReport', request()->all()) }}"
                        class="btn btn-warning  export-btn"><i class="icon-file-download"></i> Download</a>
                </div>
            @endif
        </div>
    </div>

    @if ($show)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate text-center">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        @for ($i = 1; $i <= $days; $i++)
                            @php
                                $date = $year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i);
                            @endphp
                            <th class="text-nowrap">{{ $date }}
                                <p>
                                    @php
                                        if (request()->get('calendar_type') == 'nep') {
                                            $date = date_converter()->nep_to_eng_convert($date);
                                        }
                                    @endphp
                                    {{ date('D', strtotime($date)) }}
                                </p>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emps as $key => $emp)
                        <tr>
                            <td>#{{ $emps->firstItem() + $key }} </td>
                            <td class="d-flex text-nowrap">
                                <div class="media">
                                    <div class="mr-3">
                                        <a href="#">
                                            <img src="{{ $emp->getImage() }}" class="rounded-circle" width="40"
                                                height="40" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">{{ $emp->full_name }}</div>
                                        <span class="text-muted">ID: {{ $emp->employee_code }}</span>
                                    </div>
                                </div>
                            </td>
                            {{-- @for ($i = 1; $i <= $days; $i++)
                                @php
                                    $date = $emp->date[$year . '-' . sprintf('%02d', $month) . '-' . sprintf('%02d', $i)];
                                @endphp
                                <th>
                                    <div class="text-center">
                                        <button type="button" data-popup="tooltip" data-placement="top"
                                            data-original-title="{{ $index[$date['status']] }}"
                                            class="btn btn-sm alpha-{{ $colors[$date['status']] }} text-{{ $colors[$date['status']] }}-800 ml-2 border-{{ $colors[$date['status']] }}-600 status">{{ $date['status'] }}</button>

                                        <div class="text-center text-{{ $colors[$date['status']] }}-800 status1"
                                            style="display:none;">
                                            <span>{{ $date['checkin'] ? date('h:i A', strtotime($date['checkin'])) : 'N/A' }}</span>
                                            <br>
                                            <span>{{ $date['checkout'] ? date('h:i A', strtotime($date['checkout'])) : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </th>
                            @endfor --}}
                            {{-- @dd($emp['date']) --}}
                            @foreach ($emp['date'] as $date)
                                <th>
                                    <div class="text-center">
                                        <button type="button" data-popup="tooltip" data-placement="top"
                                            data-original-title="{{ $index[$date['status']] }}"
                                            class="btn btn-sm alpha-{{ $colors[$date['status']] }} text-{{ $colors[$date['status']] }}-800 ml-2 border-{{ $colors[$date['status']] }}-600 status">
                                            {{ $date['status'] }}
                                            @if ($date['leave_status'])
                                                <sub class="text-danger">HL</sub>
                                            @endif


                                        </button>


                                        <div class="text-center text-{{ $colors[$date['status']] }}-800 status1"
                                            style="display:none;">
                                            <span>{{ $date['checkin'] ? date('h:i A', strtotime($date['checkin'])) : $date['status'] }}</span>
                                            @php
                                                $checkNextDay = false;
                                                if (
                                                    !is_null($date['checkout']) &&
                                                    $date['checkout'] < $date['checkin']
                                                ) {
                                                    $checkNextDay = true;
                                                }
                                            @endphp
                                            <span>
                                                {{ $date['checkout'] ? date('h:i A', strtotime($date['checkout'])) : '' }}

                                                @if ($checkNextDay)
                                                    <span class="text-primary">(+1 day)</span>
                                                @endif
                                        </div>
                                        <button type="button"
                                            class="badge badge-flat mt-1 alpha-{{ $checkinColors[$date['checkinStatus']] }} text-{{ $checkinColors[$date['checkinStatus']] }}-800 border-{{ $checkinColors[$date['checkinStatus']] }}-600">{{ $date['checkinStatus'] }}
                                        </button>
                                        <button type="button"
                                            class="badge badge-flat mt-1 alpha-{{ $checkoutColors[$date['checkoutStatus']] }} text-{{ $checkoutColors[$date['checkoutStatus']] }}-800 border-{{ $checkoutColors[$date['checkoutStatus']] }}-600">{{ $date['checkoutStatus'] }}
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $emps->appends(request()->all())->links() }}
            </span>
        </div>
    @endif
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#showHide').on('click', '.viewDetail', function() {
                $('.status1').css('display', '');
                $('.status').css('display', 'none');
                $('#showHide').html(
                    '<a href="javascript:;" class="btn btn-primary btn-right rounded-pill view" style="margin-right: 5px;"><i class="icon-history"></i> View Log</a>'
                );
                $('#log_type').val(2);

                let url = new URL(window.location.href)
                url.searchParams.set('log_type', 2)

                url = url.toString()
                window.history.replaceState({
                    url: url
                }, null, url)

                var base_url = window.location.origin;
                var str = window.location.search.replace("?", "");
                var export_url = base_url + "/admin/monthly-attendance-report/export-report";
                // console.log(base_url);
                $('.export-btn').attr('href', export_url + '?' + str);
                // $('.export-btn').attr('href', window.location.href);
            })

            $('#showHide').on('click', '.view', function() {
                $('.status').css('display', '');
                $('.status1').css('display', 'none');
                $('#showHide').html(
                    '<a href="javascript:;" class="btn btn-primary btn-right rounded-pill viewDetail" style="margin-right: 5px;"><i class="icon-history"></i> View Log Detail</a>'
                );

                $('#log_type').val(1);

                let url = new URL(window.location.href)
                url.searchParams.set('log_type', 1)

                url = url.toString()
                window.history.replaceState({
                    url: url
                }, null, url)

                var base_url = window.location.origin;
                var str = window.location.search.replace("?", "");
                var export_url = base_url + "/admin/monthly-attendance-report/export-report";
                // console.log(base_url);
                $('.export-btn').attr('href', export_url + '?' + str);

            })

            function findGetParameter(parameterName) {
                var result = null,
                    tmp = [];
                var items = location.search.substr(1).split("&");
                for (var index = 0; index < items.length; index++) {
                    tmp = items[index].split("=");
                    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
                }
                return result;
            }
        })
    </script>
@endsection
