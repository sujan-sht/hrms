@extends('admin::layout')
@section('title') Monthly Attendance @endSection
@section('breadcrum')
<a href="{{ route('viewAttendanceCalendar') }}" class="breadcrumb-item">Monthly Attendance</a>
<a class="breadcrumb-item active">Calendar</a>
@stop
@section('css')
<style>
    .fc-content {
        padding: 6px;
        text-align: center;
        /* font-size: 15px; */
        height: 50px;
        width: 50px;
        overflow: inherit !important;
    }

    .fc-day-header {
        color: white;
        padding: 10px !important;

    }

    .fc-day-number {
        font-size: 25px;
    }

    .modal-content {
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .fc-button-group,
    .fc-today-button {
        display: none !important;
    }
</style>
@endsection
@section('script')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
@endsection

@section('content')

<!-- Event Details Modal -->
<div id="eventModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h6 class="modal-title text-white"></h6>
                <button type="button" class="btn bg-outline-white text-white" data-dismiss="modal">Close</button>
            </div>
            <div class="modal-body">
                <p id="eventDate"></p>
                <p id="eventDetails"></p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold">Indexes</legend>
        <div class="row">
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-danger text-danger-800 border-danger-600">A</button>
                <span class="text-danger-800 ml-1">Absent <span class="total-absent"></span> </span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-slate text-slate-800 border-slate-600">D</button>
                <span class="text-slate-800 ml-1">Day Off <span class="total-dayoff"></span></span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-info text-info-800 border-info-600">H</button>
                <span class="text-info-800 ml-1">Holiday <span class="total-holiday"></span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-indigo text-indigo-800 border-indigo-600">L</button>
                <span class="text-indigo-800 ml-1">Leave <span class="total-leave"></span></span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-primary text-primary-800 border-primary-600">P*</button>
                <span class="text-primary-800 ml-1">Partial <span class="total-partial"></span> </span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-success text-success-800 border-success-600">P</button>
                <span class="text-success-800 ml-1">Present <span class="total-present"></span> </span>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm alpha-violet text-violet-800 border-violet-600">HL</button>
                <span class="text-violet-800 ml-1">Half Leave <span class="total-hl"></span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if (auth()->user()->user_type != 'employee')
        <div class="col-lg-3">
            <div class="card">
                <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
                    <h5 class="card-title text-uppercase font-weight-semibold">Search Filter</h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" data-action="collapse"></a>
                        </div>
                    </div>
                </div>
                <div class="collapse show">
                    <div class="card-body">
                        <form action="" id="calendarFilter">
                            <div class="row">
                                @if (auth()->user()->user_type == 'super_admin' || auth()->user()->user_type == 'hr')
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Organization</label>
                                        {!! Form::select(
                                            'organization_id',
                                            $organizationList,
                                            $value = request('organization_id') ?? request('org_id'),
                                            [
                                                'placeholder' => 'Select Organization',
                                                'id' => 'organization_id',
                                                'class' => 'form-control select-search organization-filter2',
                                            ],
                                        ) !!}
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Unit</label>
                                        {!! Form::select('branch_id', [], $value = request('branch_id'), [
                                            'placeholder' => 'Select Unit',
                                            'id' => 'branch_id',
                                            'class' => 'form-control branch-filter',
                                        ]) !!}
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Sub-Function</label>
                                        {!! Form::select('department_id', [], $value = request('department_id'), [
                                            'placeholder' => 'Select Sub-Function',
                                            'id' => 'department_id',
                                            'class' => 'form-control department-filter',
                                        ]) !!}
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Designation</label>
                                        {!! Form::select('designation_id', [], $value = request('designation_id'), [
                                            'placeholder' => 'Select Designation',
                                            'id' => 'designation_id',
                                            'class' => 'form-control designation-filter',
                                        ]) !!}
                                    </div>
                                @endif
                                @php
                                    $randomKey = array_rand($employeeList);
                                @endphp
                                <div class="col-md-12 mb-2">
                                    <label for="example-email" class="form-label">Employee</label>
                                    {!! Form::select(
                                        'emp_id',
                                        $employeeList,
                                        auth()->user()->user_type == 'super_admin' ? $randomKey : auth()->user()->emp_id,
                                        [
                                            'placeholder' => 'Select Employee',
                                            'class' => 'form-control select2 attachFilterEmployees select-search',
                                            'id' => 'emp_id',
                                        ],
                                    ) !!}
                                </div>

                            </div>

                            {{-- <div class="mt-2 mb-2 float-right">
                                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                                    <i class="icons icon-reset mr-1"></i>Reset
                                </a>

                                <button class="btn bg-yellow mr-2" type="submit">
                                    <i class="icon-filter3 mr-1"></i>Filter
                                </button>
                            </div> --}}

                        </form>

                    </div>
                </div>
            </div>
        </div>

    @endif
    @if (auth()->user()->user_type == 'employee')
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
        @else
            <div class="col-lg-9">
    @endif
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-end">
                <div class="rounded mr-auto d-flex" style="width: 30%">
                    {!! Form::select('year', [], $value = request('year') ?: null, [
                        'placeholder' => 'Select Year',
                        'id' => 'yearSelect',
                        'class' => 'form-control select-search1 selectpicker mr-1',
                    ]) !!}

                    {!! Form::select('month', [], $value = request('month') ?: null, [
                        'placeholder' => 'Select Month',
                        'id' => 'monthSelect',
                        'class' => 'form-control select-search1 selectpicker mr-1',
                    ]) !!}
                </div>
                <div class="py-2 rounded-left">
                    <button type="button" class="btn btn-indigo  fc-prev"><i class="icon-arrow-left7"></i></button>
                </div>
                <div class="border-left border-white py-2">
                    <button type="button" class="btn btn-danger mx-2 fc-today">Today</button>
                </div>
                <div class="border-left border-white py-2 rounded-right">
                    <button type="button" class="btn btn-teal fc-next"><i class="icon-arrow-right7"></i></button>
                </div>
                <br>
                <div class="py-2 rounded-left">
                </div>
            </div>
            <div id='full_calendar_attendances'></div>

        </div>
    </div>
</div>

@if (auth()->user()->user_type == 'employee')
    <div class="col-lg-1"></div>
@endif
</div>



<script>
    $(document).ready(function() {
        var SITEURL = "{{ url('admin/') }}";
        var employeeId = $("#emp_id").val();

        var calendar = $('#full_calendar_attendances').fullCalendar({
            editable: true,
            eventSources: [{
                url: SITEURL + "/monthly-attendance-report/calendar-ajax",
                type: 'GET',
                data: function() { // Use a function to dynamically get emp_id
                    return {
                        _token: '{{ csrf_token() }}',
                        calendar_type: '{{ request()->calendar_type }}',
                        emp_id: $("#emp_id").val(),
                        eng_year: '{{ request()->eng_year }}',
                        eng_month: '{{ request()->eng_month }}',
                    };
                },
                success: function(response) {
                    console.log('response >>>> ', response);

                    var countPresent = 0;
                    var partial = 0;
                    var totalHoliday = 0;
                    const counts = {
                        A: 0,
                        D: 0,
                        L: 0,
                        HL: 0,
                    }
                    response.forEach(function(item) {
                        if (counts.hasOwnProperty(item.title)) {
                            counts[item.title]++;
                        } else if (item.color == '#00ACC1') { // holiday
                            totalHoliday++;
                        } else if (item.color == '#43A047') { // present
                            countPresent++;
                        } else if (item.color == '#1E88E5') { // partial
                            partial++;
                        }
                    });

                    // Log the counts
                    console.log('Absent:', counts.A, 'Day Off:',
                        counts.D, 'Leave:', counts.L, 'Present: ', countPresent);

                    // Update the text for each count
                    $(".total-absent").text(`(${counts.A})`);
                    $(".total-dayoff").text(`(${counts.D})`);
                    $(".total-leave").text(`(${counts.L})`);
                    $(".total-hl").text(`(${counts.HL})`);
                    $(".total-holiday").text(`(${totalHoliday})`);
                    $(".total-present").text(`(${countPresent})`);
                    $(".total-partial").text(`(${partial})`);
                },
                error: function(response) {
                    console.log('error ...', response);
                }
            }],
            displayEventTime: true,
            header: {
                center: 'prev,next today',
                left: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventRender: function(event, element, view) {
                event.allDay = event.allDay === 'true' ? true : false;
            },
            selectable: true,
            selectHelper: true,
            select: function(event_start, event_end, allDay) {
                var event_start = $.fullCalendar.formatDate(event_start, "Y-MM-DD");
            },
            eventDrop: function(event, delta) {},
            eventClick: function(event) {
                console.log('here')
                let eventDate = moment(event.start).format("YYYY-MM-DD");
                let today = moment().format("YYYY-MM-DD");
                let empId = $("#emp_id").val();


                let attendanceBtn = "";
                if (eventDate <= today) {
                    attendanceBtn = '<a href="/admin/attendance-request/create?employee_id=' +
                        empId +
                        '&date=' + eventDate +
                        '" class="btn btn-primary btn-sm">Attendance Request</a> ';
                }

                let leaveBtn = '<a href="/admin/leave/create?employee_id=' + empId +
                    '&date=' + eventDate +
                    '" class="btn btn-danger btn-sm">Leave Request</a>';

                let travelBtn = '<a href="/admin/travel-request/create?' + empId +
                    '&date=' + eventDate +
                    '" class="btn btn-info btn-sm ml-1">Travel Request</a>';

                $("#eventModal .modal-title").text(event.title);
                $("#eventModal #eventDate").html(
                    "Date: " + eventDate + "<br><br>" + attendanceBtn + leaveBtn + travelBtn
                );

                $("#eventModal").modal("show"); // Show Bootstrap modal
            }
        });


        $('.fc-prev').on('click', function() {
            calendar.fullCalendar('prev');
            setSelectedMonthAndYear()

        });

        $('.fc-next').on('click', function() {
            calendar.fullCalendar('next');
            setSelectedMonthAndYear()

        });

        $('.fc-today').on('click', function() {
            calendar.fullCalendar('today');
            setSelectedMonthAndYear()
        });

        // Month Dropdown
        const monthSelect = document.getElementById('monthSelect');
        const months = moment.months(); // Get month names from Moment.js
        months.forEach((month, index) => {
            const option = new Option(month, index);
            monthSelect.appendChild(option);
        });

        // Year Dropdown
        const yearSelect = document.getElementById('yearSelect');
        const currentYear = moment().year(); // Get the current year
        for (let i = currentYear - 10; i <= currentYear +
            10; i++) { // Add 10 years before and after the current year
            const option = new Option(i, i);
            yearSelect.appendChild(option);
        }
        yearSelect.value = currentYear; // Set the current year as the default

        // Listen to changes in the month dropdown
        $(monthSelect).on('change', function() {
            const selectedMonth = parseInt($(this).val(), 10);
            const selectedYear = parseInt(yearSelect.value, 10);
            calendar.fullCalendar('gotoDate', moment().year(selectedYear).month(selectedMonth)
                .startOf(
                    'month').format('YYYY-MM-DD'));
        });

        // Listen to changes in the year dropdown
        $(yearSelect).on('change', function() {
            const selectedYear = parseInt($(this).val(), 10);
            const selectedMonth = parseInt(monthSelect.value, 10);
            calendar.fullCalendar('gotoDate', moment().year(selectedYear).month(selectedMonth)
                .startOf(
                    'month').format('YYYY-MM-DD'));
        });

        // Set the selected month and year based on the calendar's current view
        function setSelectedMonthAndYear() {
            var currentDate = calendar.fullCalendar('getDate');
            var selectedMonth = currentDate.month(); // Get the month (0-11)
            var selectedYear = currentDate.year(); // Get the year
            monthSelect.value = selectedMonth;
            yearSelect.value = selectedYear;
        }

        // Initialize the selected month and year
        setSelectedMonthAndYear();

        // Update calendar events when emp_id changes
        $("#emp_id").change(function() {
            calendar.fullCalendar('refetchEvents'); // Refresh events with new emp_id
        });
    });

    function GetTodayDate() {
        var tdate = new Date();
        var dd = tdate.getDate(); //yields day
        var MM = tdate.getMonth(); //yields month
        var yyyy = tdate.getFullYear(); //yields year
        var currentDate = yyyy + "-" + (MM + 1) + "-" + dd;

        return currentDate;
    }
</script>

@endSection
