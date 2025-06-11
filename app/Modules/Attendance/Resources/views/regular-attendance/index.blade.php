@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">Daily Attendance</a>
@endsection

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
        $index = $statusList = [
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
    @endphp

    @include('attendance::regular-attendance.partial.filter', $statusList)

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
                <h6 class="media-title font-weight-semibold">List of Employee Attendance</h6>
                All the Attendance Information will be listed below. You can view the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('exportRegularAttendance', request()->all()) }}" class="btn btn-success"><i
                        class="icon-file-excel"></i> Export</a>
            </div>
            <div class="mt-1 ml-1">
                <a href="{{ route('downloadRegularAttendance', request()->all()) }}" class="btn btn-warning"><i
                        class="icon-file-download"></i> Download</a>
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
                    @if (isset($filter['date_range']))
                        {{-- @foreach ($emps as $key => $emp)
                            @php
                                if (isset($filter['date_range'])) {
                                    $filterDates = explode(' - ', $filter['date_range']);
                                    $startDate = $filterDates[0];
                                    $endDate = $filterDates[1];
                                }
                            @endphp
                            @while ($startDate <= $endDate)
                                @php
                                    $fullDate = $startDate;
                                @endphp

                                <tr>
                                    <td>#{{ $key+1 }} </td>
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
                                    @php
                                        $date = $emp->date[$fullDate];
                                    @endphp
                                    <td>
                                        {{ date('Y-m-d', strtotime($fullDate)) }}
                                    </td>
                                    <td>
                                        {{ date('l', strtotime($fullDate)) }}
                                        <br>
                                        @if (isset($date['holidayName']) && $date['holidayName'] != '')
                                            <span class="badge badge-info badge-pill ml-auto ml-lg-0">
                                                {{ $date['holidayName'] }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $date['checkin'] ? date('h:i A', strtotime($date['checkin'])) : '' }}
                                        <br>

                                        <button type="button"
                                            class="badge badge-flat alpha-{{ $checkinColors[$date['checkinStatus']] }} text-{{ $checkinColors[$date['checkinStatus']] }}-800 border-{{ $checkinColors[$date['checkinStatus']] }}-600">{{ $date['checkinStatus'] }}
                                        </button>
                                    </td>
                                    <td>
                                        {{ $date['checkout'] ? date('h:i A', strtotime($date['checkout'])) : '' }}
                                        <br>

                                        <button type="button"
                                            class="badge badge-flat alpha-{{ $checkoutColors[$date['checkoutStatus']] }} text-{{ $checkoutColors[$date['checkoutStatus']] }}-800 border-{{ $checkoutColors[$date['checkoutStatus']] }}-600">{{ $date['checkoutStatus'] }}
                                        </button>

                                    </td>

                                    <td>
                                        <button type="button" data-popup="tooltip" data-placement="top"
                                            data-original-title="{{ $index[$date['status']] }}"
                                            class="btn btn-sm alpha-{{ $colors[$date['status']] }} text-{{ $colors[$date['status']] }}-800 ml-2 border-{{ $colors[$date['status']] }}-600">{{ $date['status'] }}
                                        </button>
                                    </td>

                                    <td>{{ $date['total_working_hr'] }}</td>

                                </tr>

                                @php
                                    $startDate = date('Y-m-d', strtotime('+1 day', strtotime($startDate)));
                                @endphp

                            @endwhile
                        @endforeach --}}

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
                                <td>{{ @$emp->designation->title }}</td>
                                <td>
                                    {{ date('Y-m-d', strtotime($emp['date'])) }}
                                </td>
                                <td>{{ @$emp->early_time }}</td>
                                <td>{{ @$emp->lateIn }}</td>
                                <td>
                                    {{-- {{ date('l', strtotime($emp['date'])) }} --}}
                                    {{ $emp['day'] }}
                                    <br>
                                    @if (isset($emp['holidayName']) && $emp['holidayName'] != '')
                                        <span class="badge badge-info badge-pill ml-auto ml-lg-0">
                                            {{ $emp['holidayName'] }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $emp['checkin'] ? date('h:i A', strtotime($emp['checkin'])) : '' }}
                                    <br>

                                    <button type="button"
                                        class="badge badge-flat alpha-{{ $checkinColors[$emp['checkinStatus']] }} text-{{ $checkinColors[$emp['checkinStatus']] }}-800 border-{{ $checkinColors[$emp['checkinStatus']] }}-600">{{ $emp['checkinStatus'] }}
                                    </button>
                                </td>
                                <td>
                                    @php
                                        $checkNextDay = false;
                                        if (!is_null($emp['checkout']) && $emp['checkout'] < $emp['checkin']) {
                                            $checkNextDay = true;
                                        }
                                    @endphp
                                    @if ($checkNextDay)
                                        <span class="text-success">(+1 day)</span>
                                        <br>
                                    @endif
                                    {{ $emp['checkout'] ? date('h:i A', strtotime($emp['checkout'])) : '' }}
                                    <br>

                                    <button type="button"
                                        class="badge badge-flat alpha-{{ $checkoutColors[$emp['checkoutStatus']] }} text-{{ $checkoutColors[$emp['checkoutStatus']] }}-800 border-{{ $checkoutColors[$emp['checkoutStatus']] }}-600">{{ $emp['checkoutStatus'] }}
                                    </button>

                                </td>

                                <td>
                                    <button type="button" data-popup="tooltip" data-placement="top"
                                        data-original-title="{{ $index[$emp['status']] }}"
                                        class="btn btn-sm alpha-{{ $colors[$emp['status']] }} text-{{ $colors[$emp['status']] }}-800 ml-2 border-{{ $colors[$emp['status']] }}-600">{{ $emp['status'] }}
                                    </button>
                                </td>

                                <td>{{ $emp['total_working_hr'] }}</td>
                                <td>{{ @$emp->overStay }}</td>
                                <td>{{ @$emp->otValue }}</td>
                                <td>
                                    @if (!is_null($emp->checkin_from))
                                        {{ $emp->checkin_from }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>
                                    @if (!is_null($emp->checkout_from))
                                        {{ $emp->checkout_from }}
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>
                                    @if (!empty(json_decode($emp->coordinates)))
                                        <i class="icon-location3 getLocation" style="color: blue"
                                            data-location="{{ $emp->coordinates }}"></i>
                                    @endif
                                </td>
                                <td>{{ $emp->actual_shift_name }}</td>
                                <td>
                                    @if ($emp->actual_shift_name != $emp->updated_shift_name)
                                        {{ $emp->updated_shift_name }}
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $emps->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
    <div id="modal_map" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-indigo text-white border-0">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div id="map"
                        style="width:870px;height:400px; margin:auto; border: 1px solid #0c0c0c; padding: 10px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- @dd(Config::get('admin.google-map')) --}}
@section('script')
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ Config::get('admin.google-map') }}">
    </script>
    <script>
        $(function() {

            function initializeMap(locations) {
                const map = new google.maps.Map(document.getElementById("map"));
                var infowindow = new google.maps.InfoWindow();
                var bounds = new google.maps.LatLngBounds();

                for (var location of locations) {
                    let url = "http://maps.google.com/mapfiles/ms/icons/";
                    url += location.color + "-dot.png";

                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(location.lat, location.long),
                        map: map,
                        icon: {
                            url: url
                        }
                    });
                    bounds.extend(marker.position);

                    google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
                        this.setZoom(map.getZoom() - 1);

                        if (this.getZoom() > 15) {
                            this.setZoom(15);
                        }
                    });

                    google.maps.event.addListener(marker, 'click', (function(marker, location) {
                        return function() {
                            var iwContent = '<div id="iw_container">' +
                                '<div class="iw_title"><b>Type</b> : <span class="lastLoc">' +
                                location.type + '<span></div>';
                            iwContent +=
                                '<div class="iw_title"><b>Latitude</b> : <span class="lastLoc">' +
                                location.lat + '<span></div>';
                            iwContent +=
                                '<div class="iw_title"><b>Longitude</b> : <span class="lastLoc">' +
                                location.long + '<span></div>';
                            iwContent += '</div';
                            infowindow.setContent(iwContent);
                            infowindow.open(map, marker);
                        }
                    })(marker, location));

                }
                map.fitBounds(bounds);
            }

            $('.getLocation').on('click', function(e) {
                e.preventDefault();
                locations = $(this).data('location');
                initializeMap(locations);
                $('#modal_map').modal('show');
            })

        })
    </script>
@endsection
