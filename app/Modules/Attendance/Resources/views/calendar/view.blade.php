@extends('admin::layout')
@section('title') Attendance @endSection
@section('breadcrum')
<a href="{{ route('viewAttendanceCalendar') }}" class="breadcrumb-item">Attendance</a>
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
</style>
@endsection
@section('script')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
@endsection

@section('content')
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
                                        <label class="form-label">Organization <span
                                                class="text-danger">*</span></label>
                                        {!! Form::select(
                                            'organization_id',
                                            $organizationList,
                                            $value = request('organization_id') ?? optional(auth()->user()->userEmployer)->organization_id,
                                            [
                                                'placeholder' => 'Select Organization',
                                                'id' => 'organization_id',
                                                'class' => 'form-control select-search organization-filter2',
                                                'required',
                                            ],
                                        ) !!}
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Unit</label>
                                        {!! Form::select(
                                            'branch_id',
                                            $branchList,
                                            $value = request('branch_id') ?? optional(auth()->user()->userEmployer)->branch_id,
                                            [
                                                'placeholder' => 'Select Unit',
                                                'id' => 'branch_id',
                                                'class' => 'form-control select2 branch-filter',
                                            ],
                                        ) !!}
                                    </div>
                                @endif

                                <div class="col-md-12 mb-2">
                                    <label for="example-email" class="form-label">Employee</label>
                                    {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?? auth()->user()->emp_id, [
                                        'placeholder' => 'Select Employee',
                                        'class' => 'form-control select2 employee-filter',
                                        'id' => 'employee_id',
                                    ]) !!}
                                </div>

                            </div>

                            <div class="mt-2 mb-2 float-right">
                                <a href="{{ request()->url() }}" class="btn bg-secondary text-white">
                                    <i class="icons icon-reset mr-1"></i>Reset
                                </a>

                                <button class="btn bg-yellow mr-2" type="submit">
                                    <i class="icon-filter3 mr-1"></i>Filter
                                </button>
                            </div>

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
        var SITEURL = "{{ url('admin/attendance/') }}";

        var calendar = $('#full_calendar_attendances').fullCalendar({
            editable: true,
            events: SITEURL + "/calendar-ajax",
            displayEventTime: true,
            header: {
                center: 'prev,next today',
                left: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventRender: function(event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function(event_start, event_end, allDay) {
                var event_start = $.fullCalendar.formatDate(event_start, "Y-MM-DD");
            },
            eventDrop: function(event, delta) {},
            eventClick: function(event) {}
        });
    });

    function callDatePicker(id, startDate = '', endDate = '', minDate = '', maxDate = '') {
        $('#' + id).daterangepicker({
            parentEl: '.content-inner',
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            startDate: startDate,
            endDate: endDate,
            minDate: minDate,
            maxDate: maxDate,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        }).on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });;
    }
</script>
@endSection
