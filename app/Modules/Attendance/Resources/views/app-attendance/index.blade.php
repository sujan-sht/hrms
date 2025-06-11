@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">App Attendance</a>
@endsection

@section('content')

    @php
        $colors = ['A' => 'danger', 'L' => 'indigo', 'P' => 'success', 'H' => 'info', 'D' => 'slate', 'P*' => 'primary', 'HL' => 'violet'];
        $index = $statusList = ['A' => 'Absent', 'L' => 'Leave', 'P' => 'Present', 'H' => 'Holiday', 'D' => 'Day off', 'P*' => 'Partial', 'HL' => 'Half Leave'];

        $checkinColors = ['Late Arrival' => 'danger', 'On Time' => 'primary', 'Early Arrival' => 'success', '' => ''];
        $checkoutColors = ['Early Departure' => 'danger', 'On Time' => 'primary', 'Late Departure' => 'success', '' => ''];
    @endphp

    {{-- @include('attendance::regular-attendance.partial.filter', $statusList) --}}

    @include('attendance::app-attendance.partial.filter', $statusList)

    @if ($show)
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
                        <button type="button"
                            class="btn btn-sm alpha-primary text-primary-800 border-primary-600">P*</button>
                        <span class="text-primary-800 ml-1">Partial</span>
                    </div>
                    <div class="col-md-1">
                        <button type="button"
                            class="btn btn-sm alpha-success text-success-800 border-success-600">P</button>
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
                {{-- <div class="mt-1">
                    @if (checkExportState('enableExportButton'))
                        <a href="{{ route('exportRegularAttendance', request()->all()) }}" class="btn btn-success rounded-pill"><i
                            class="icon-file-excel"></i> Export</a>
                    @else
                        <a class="btn btn-success disableExport rounded-pill" data-popup="tooltip" data-placement="top"
                                data-original-title="Export">
                                Export
                        </a>
                    @endif
                </div> --}}
            </div>
        </div>
        <div class="card card-body">

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            {{-- <th>S.N</th> --}}
                            <th>Employee Name</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Working Hours</th>
                            <th>Location</th>
                            <th>App Logs</th>
                            <th>Actual Shift</th>
                            <th>Updated Shift</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($emps as $key => $emp)
                            @forelse ($emp->date as $date => $item)
                                <tr>
                                    {{-- <td>#{{ $emps->firstItem() + $key }} </td> --}}
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
                                    <td>

                                        @if (request('calendar_type') == 'nep')
                                            {{ date('Y-m-d', strtotime(date_converter()->eng_to_nep_convert($date))) }}
                                        @else
                                            {{ date('Y-m-d', strtotime($date)) }}
                                        @endif

                                    </td>
                                    <td>
                                        {{ date('l', strtotime($date)) }}
                                        <br>
                                        @if (isset($item['holidayName']) && $item['holidayName'] != '')
                                            <span class="badge badge-danger badge-pill ml-auto ml-lg-0">
                                                {{ $item['holidayName'] }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if (isset($item['checkin']))
                                            @if (isset($item['checkin_from']))
                                                <b>{{ ucfirst(mb_substr($item['checkin_from'], 0, 1)) . ':' }}</b>
                                            @endif
                                            {{ date('h:i A', strtotime($item['checkin'])) }}
                                        @endif
                                        <br>
                                        @if (isset($item['checkinStatus']))
                                            <button type="button"
                                                class="badge badge-flat alpha-{{ $checkinColors[$item['checkinStatus']] }} text-{{ $checkinColors[$item['checkinStatus']] }}-800 border-{{ $checkinColors[$item['checkinStatus']] }}-600">{{ $item['checkinStatus'] }}
                                            </button>
                                        @endif

                                    </td>
                                    <td>
                                        @if (isset($item['checkout']))
                                            @if (isset($item['checkout_from']))
                                                <b>{{ ucfirst(mb_substr($item['checkout_from'], 0, 1)) . ':' }}</b>
                                            @endif
                                            {{ date('h:i A', strtotime($item['checkout'])) }}
                                        @endif
                                        <br>
                                        @if (isset($item['checkoutStatus']))
                                            <button type="button"
                                                class="badge badge-flat alpha-{{ $checkoutColors[$item['checkoutStatus']] }} text-{{ $checkoutColors[$item['checkoutStatus']] }}-800 border-{{ $checkoutColors[$item['checkoutStatus']] }}-600">{{ $item['checkoutStatus'] }}
                                            </button>
                                        @endif

                                    </td>

                                    <td>
                                        <button type="button" data-popup="tooltip" data-placement="top"
                                            data-original-title="{{ $index[$item['status']] }}"
                                            class="btn btn-sm alpha-{{ $colors[$item['status']] }} text-{{ $colors[$item['status']] }}-800 ml-2 border-{{ $colors[$item['status']] }}-600">
                                            {{ $item['status'] }}
                                            @if (isset($item['leave_status']))
                                                <sub class="text-danger">{{ $item['leave_status'] }}</sub>
                                            @endif
                                        </button>
                                    </td>

                                    <td>{{ isset($item['total_working_hr']) ? $item['total_working_hr'] : '' }}</td>
                                    <td>
                                        @if (isset($item['coordinates']) && !empty(json_decode($item['coordinates'])))
                                            <i class="icon-location3 getLocation" style="color: blue"
                                                data-location="{{ $item['coordinates'] }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- <a data-toggle="modal" data-target="#appLogReport" class="btn btn-outline-secondary btn-icon viewLogs mx-1"
                                        data-biometricitemid="{{ $item['biometric_id'] }}" data-date="{{ $item['date'] }}" data-location="{{ $item['coordinates'] }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="View Logs">
                                        <i class="icon-eye"></i>
                                    </a> --}}

                                        <a data-toggle="modal" data-target="#appLogReport"
                                            class="btn btn-outline-secondary btn-icon viewLogs mx-1"
                                            data-biometricempid="{{ $emp['biometric_id'] }}"
                                            data-date="{{ $date }}"
                                            data-calendar-type="{{ request('calendar_type') }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="View Logs"><i class="icon-eye"></i>
                                        </a>
                                    </td>
                                    <td>{{$item->actual_shift}}</td>
                                    <td>{{$item->updated_shift}}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"> No Data Found</td>
                                </tr>
                            @endforelse
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <span class="float-right pagination align-self-end mt-3">
                    {{ $emps->appends(request()->all())->links() }}
                </span>
            </div>
        </div>
    @endif
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
    <div id="appLogReport" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">App Logs</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-light btn-slate">
                                <tr>
                                    <th width="5%">SN</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Punch From</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody class="attendanceLogs">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>


    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
    <script src="{{ asset('admin/validation/validation.js') }}"></script>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ Config::get('admin.google-map') }}">
    </script>
    <script>
        $(document).ready(function() {
            function initializeMap(locations) {
                const map = new google.maps.Map(document.getElementById("map"));
                var infowindow = new google.maps.InfoWindow();
                var bounds = new google.maps.LatLngBounds();

                for (var location of locations) {
                    // let url = "http://maps.google.com/mapfiles/ms/icons/";
                    // url += location.color + "-dot.png";

                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(location.lat, location.long),
                        map: map,
                        // icon: {
                        //     url: url
                        // }
                        animation: google.maps.Animation.DROP,
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

            $('body').on('click', '.getLocation', function(e) {
                e.preventDefault();
                locations = $(this).data('location');
                initializeMap(locations);
                $('#modal_map').modal('show');
                $('#appLogReport').modal('hide');
            })

            //View App Logs
            $('.viewLogs').on('click', function() {
                var biometric_emp_id = $(this).data('biometricempid')
                var date = $(this).data('date')
                // var location = $(this).data('location')
                var form_data = {
                    biometric_emp_id,
                    date,
                    // location,
                    punch_from: 'app'
                }
                $.ajax({
                    type: "GET",
                    url: "{{ route('appViewLogs') }}",
                    dataType: 'json',
                    data: form_data,
                    success: function(resp) {
                        $('.attendanceLogs').empty()
                        $('.attendanceLogs').append(resp.data)
                    }
                })
            })
            //
        });
    </script>
@endsection
