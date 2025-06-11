    <div class="stats">

        {{-- <div class="row mb-1">
        <div class="col-xl-8"></div>
        <div class="col-xl-4">
            <div class="d-flex justify-content-end">
                <a href="" class="bg-success text-white py-2 px-3 rounded-start" data-target="#checkInModal" data-toggle="modal"> Check In</a>
                <a href="" class="bg-danger text-white py-2 px-3 rounded-start"> Check Out</a>
            </div>
        </div>
    </div> --}}

        <div class="row">

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('leave.create') }}" class="text-dark">
                                            <h6 class="font-weight-semibold mb-0">Apply Leave</h6>
                                        </a>
                                        <h1>{{ sprintf('%02d', $leaveRequestCount) }}</h1>
                                        <p style="font-size:12px">leave taken in total</p>
                                    </div>
                                    <div class="col-md-6">


                                        <h1 style="padding-top:1.5rem;">
                                            {{ sprintf('%02d', $leaveRequestCountThisMonth) }}
                                        </h1>
                                        <p style="font-size:12px">leave taken this month</p>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3 text-right">
                                <i class="icon-users2 icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <a href="{{ route('attendanceRequest.index') }}" class="text-dark">
                                    <h6 class="font-weight-semibold mb-0">Attendance Request</h6>
                                </a>
                                <h1>{{ sprintf('%02d', $attendanceRequestCount) }}</h1>
                                <p style="font-size:12px">request made this month </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="icon-cabinet icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <a href="{{ route('tada.create') }}" class="text-dark">
                                    <h6 class="font-weight-semibold mb-0">Apply for Claim </h6>
                                </a>
                                <h1>{{ sprintf('%02d', $claimRequest) }} </h1>
                                <p style="font-size:12px">claim applied this month</p>

                            </div>
                            <div class="col-md-4 text-right">
                                <i class="icon-users4 icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8" style="margin-bottom: 6px;">
                                <a href="{{ route('viewMonthlyAttendanceCalendar', [
                                    'org_id' => 1,
                                    'calendar_type' => 'eng',
                                    'eng_year' => date('Y'),
                                    'eng_month' => (int) date('m'),
                                ]) }}"
                                    class="text-dark">
                                    <h6 class="font-weight-semibold">Attendance Calendar</h6>
                                </a>
                                <h1>0</h1>
                            </div>
                            <div class="col-md-4 text-right">
                                <i class="icon-calendar icon-3x text-secondary mt-1 mb-3"></i>
                            </div>
                        </div>
                    </div>
                    <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                </div>
            </div>

            @if (setting('web_attendance') == 11 && $allowWebAttendance == true)
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8" style="margin-bottom: 6px;">
                                    <a href="" class="text-dark">
                                        <h6 class="font-weight-semibold">Web Attendance</h6>
                                    </a>

                                    {!! Form::open([
                                        'route' => 'store.attendance',
                                        'method' => 'POST',
                                        'class' => 'form-horizontal checkForm',
                                        'role' => 'form',
                                    ]) !!}

                                    {{-- @if (isset($getTodayAtd) && !empty($getTodayAtd)) --}}
                                    <p>
                                        @if ($getTodayAtd['checkin'])
                                            In: {{ date('h.i A', strtotime($getTodayAtd['checkin'])) }}
                                        @endif
                                        @if ($getTodayAtd['checkout'])
                                            | Out: {{ date('h.i A', strtotime($getTodayAtd['checkout'])) }}
                                        @endif
                                    </p>
                                    {{-- <button class="btn btn-xl bg-success text-white mt-0 btn-check"
                                        type="submit">Punch</button> --}}
                                    {{-- @dd($getTodayAtd) --}}

                                    {{-- @if ($getTodayAtd['inout_mode'] != null)
                                {{ $getTodayAtd['inout_mode']  }} --}}
                                @if ($getTodayAtd['inout_mode'] == 0)
                                {!! Form::hidden('type', 'checkout') !!}
                                {!! Form::hidden('inout_mode', '1') !!}

                                {{-- {!! Form::hidden('date', $getTodayAtd['attendanceDate']) !!} --}}

                                <button class="btn btn-xl bg-danger text-white mt-0 btn-check" type="submit">Check
                                    Out</button>
                            @elseif($getTodayAtd['inout_mode'] == 1)
                                {!! Form::hidden('type', 'checkin') !!}
                                {!! Form::hidden('inout_mode', '0') !!}
                                {{-- {!! Form::hidden('date', $getTodayAtd['attendanceDate']) !!} --}}

                                <button class="btn btn-xl bg-success text-white mt-0 btn-check" type="submit">Check
                                    In</button>
                            @endif
                                    {{-- @else
                                wer
                                {{ $getTodayAtd['inout_mode']  }}

                                    {!! Form::hidden('type', 'checkin') !!}
                                    <button class="btn btn-xl bg-success text-white mt-0 btn-check" type="submit">Check
                                        In</button>
                                @endif --}}

                                    {{-- @else
                                    {!! Form::hidden('type', 'checkin') !!}
                                    <button class="btn btn-xl bg-success text-white mt-0 btn-check" type="submit">Check
                                        In</button>
                                @endif --}}

                                    {!! Form::close() !!}

                                </div>
                                <div class="col-md-4 text-right">
                                    <i class="icon-alarm icon-3x text-secondary mt-1 mb-2"></i>
                                </div>
                            </div>
                        </div>
                        <img src="{{ asset('admin/widget-bg-secondary.png') }}">
                    </div>
                </div>
            @endif

            {{-- <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="font-weight-semibold mb-0"><span id="displayTime">{{ date('h:i:s A', strtotime($currentDatetime)) }}</span></h1>
                            <h6>{{ date('M d, Y (l)', strtotime($currentDatetime)) }}</h6>
                        </div>
                        <div class="col-md-4 text-right">
                            <i class="icon-alarm icon-3x text-secondary mt-1 mb-3"></i>
                        </div>
                    </div>
                </div>
                <img src="{{ asset('admin/widget-bg-secondary.png') }}">
            </div>
        </div> --}}

        </div>
    </div>

    {{-- @push('custom_js') --}}
    <script>
        $(document).ready(function() {
            $('.checkForm').submit(function(e) {
                e.preventDefault();
                var form = $(this)[0];
                showTime = ($('#showTime').text());
                $('.time').val(showTime);
                // $(this).submit();
                // $(".checkForm:first").submit();
                form.submit();
            })
        })
    </script>
    {{-- @endpush --}}
