@extends('admin::layout')
@section('title') Calendar @endSection
@section('breadcrum')
<a href="{{ route('leave.index') }}" class="breadcrumb-item">Leave</a>
<a class="breadcrumb-item active">Calendar</a>
@stop
@section('css')
<style>
    .fc-col-header-cell-cushion {
        color: #fff !important;
    }

    .fc-event-title {
        color: #fff !important;
    }

    .fc-button-group .fc-button {
        text-transform: capitalize;
        color: #fff !important;
        border-radius: 50rem !important;
    }

    .fc-dayGridMonth-button {
        background-color: #2196f3 !important;
        border-color: #2196f3 !important;
    }

    .fc-dayGridWeek-button {
        margin-right: 0.625rem !important;
        margin-left: 0.625rem !important;
        background-color: #4a5ab9 !important;
        border-color: #4a5ab9 !important;
    }

    .fc-listWeek-button {
        border-radius: 50rem !important;
        background-color: #27a7b7 !important;
        border-color: #27a7b7 !important;
    }

    /* Custom styles for the tooltip container */
    .fc-event.custom-tooltip {
        /* Your custom styles for the event tooltip */
        /* For example: */
        position: relative;
    }

    .fc-event.custom-tooltip::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 6px 10px;
        background-color: #333;
        color: #fff;
        font-size: 14px;
        border-radius: 4px;
        white-space: nowrap;
        visibility: hidden;
        opacity: 0;
        z-index: 1;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .fc-event.custom-tooltip:hover::after {
        visibility: visible;
        opacity: 1;
    }
</style>
@endsection

@section('script')
{{-- <script src="https://demo.interface.club/limitless/demo/template/assets/js/vendor/ui/fullcalendar/main.min.js"></script> --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.12/index.global.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/3.4.1/tippy.all.min.js"></script>
@endsection


@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <legend class="text-uppercase font-size-sm font-weight-bold">Indexes</legend>
        <div class="row">
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-slate text-slate-800 border-slate-600">P</button>
                <span class="text-slate-800 ml-1">Pending</span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-primary text-primary-800 border-primary-600">F</button>
                <span class="text-primary-800 ml-1">Forwarded</span>
            </div>

            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-success text-success-800 border-success-600">A</button>
                <span class="text-success-800 ml-1">Accepted</span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm alpha-danger text-danger-800 border-danger-600">R</button>
                <span class="text-danger-800 ml-1">Rejected</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
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

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Organization <span class="text-danger">*</span></label>
                                {!! Form::select(
                                    'organization_id',
                                    $organizationList,
                                    $value = request('organization_id') ?? optional(auth()->user()->userEmployer)->organization_id,
                                    [
                                        // 'placeholder' => 'Select Organization',
                                        'id' => 'organization_id',
                                        'class' => 'form-control select-search organization-filter organization-filter2',
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
                            <div class="col-md-12 mb-2">
                                <label for="example-email" class="form-label">Employee</label>
                                {!! Form::select('employee_id', $employeeList, $value = request('employee_id') ?: null, [
                                    'placeholder' => 'Select Employee',
                                    'class' => 'form-control select2 employee-filter',
                                    'id' => 'employee_id',
                                ]) !!}
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Leave Type</label>
                                {!! Form::select('leave_type_id', $leaveTypeList, $value = request('leave_type_id') ?: null, [
                                    'placeholder' => 'Select Leave Type',
                                    'id' => 'leave_type_id',
                                    'class' => 'form-control select2 leave-type-filter',
                                ]) !!}
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Leave Category</label>
                                {!! Form::select('leave_kind', $leaveKindList, $value = request('leave_kind') ?: null, [
                                    'placeholder' => 'Select Leave Category',
                                    'id' => 'leave_kind',
                                    'class' => 'form-control select-search',
                                ]) !!}
                            </div>
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Status</label>
                                {!! Form::select('status', $statusList, $value = request('status') ?: null, [
                                    'placeholder' => 'Select Status',
                                    'id' => 'status',
                                    'class' => 'form-control select-search',
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

        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title">Leave Details</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="collapse show">
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table table-bordered1 text-nowrap">

                            <tbody class="appendLeave">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>

    </div>
    <div class="col-lg-9">
        <div class="card card-body">
            @if (request('organization_id') || ($organizationList->count() > 0 ? $organizationList->keys()->first() : null))
                {{-- <div class="col-md-2" style="position: relative; top: 95px; left: 215px;">
                    {!! Form::select('month', [], $value = request('month') ?: null, [
                        'placeholder' => 'Select Month',
                        'id' => 'monthSelect',
                        'class' => 'form-control select-search selectpicker',
                    ]) !!}
                </div> --}}

                {{-- <div class="text-right"> --}}

                <div class="d-flex justify-content-end">
                    <div class="rounded mr-auto" style="width: 20%">
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
                <div class='fullcalendar-event-colors'></div>
            @else
                <h2 class="text-danger text-center">No Organizaton Found</h2>
            @endif
        </div>

    </div>
</div>

<script>
    const FullCalendarStyling = function() {
        // External events
        const _componentFullCalendarStyling = function() {
            if (typeof FullCalendar == 'undefined') {
                console.warn('Warning - Fullcalendar files are not loaded.');
                return;
            }


            // Define element
            const calendarEventColorsElement = document.querySelector('.fullcalendar-event-colors');

            var SITEURL = "{{ url('admin/') }}";
            defaultDate = new Date();

            organization_id = $('#organization_id').val();
            branch_id = $('#branch_id').val();
            employee_id = $('#employee_id').val();
            leave_type_id = $('#leave_type_id').val();
            leave_kind = $('#leave_kind').val();
            status = $('#status').val();
            // Initialize
            if (calendarEventColorsElement) {
                const calendarEventColorsInit = new FullCalendar.Calendar(calendarEventColorsElement, {
                    headerToolbar: {
                        // right: 'prev,next today',
                        // right: 'customprev,customnext,today',
                        right: '',
                        center: 'title',
                        left: 'dayGridMonth,dayGridWeek,listWeek'
                        // left: ''

                    },
                    initialDate: defaultDate,
                    navLinks: true, // can click day/week names to navigate views
                    businessHours: true, // display business hours
                    editable: false,
                    selectable: true,
                    editable: true,
                    eventSources: [{
                        url: SITEURL + "/leave/calendar-ajax",
                        method: 'GET',
                        extraParams: {
                            organization_id: organization_id,
                            branch_id: branch_id,
                            employee_id: employee_id,
                            leave_type_id: leave_type_id,
                            leave_kind: leave_kind,
                            status: status
                        },
                        failure: function() {
                            toastr.error('there was an error while fetching events!');
                        },
                        color: 'yellow', // a non-ajax option
                        textColor: 'black' // a non-ajax option
                    }],
                    eventSourceSuccess: function(content, response) {
                        $('.appendLeave').empty();
                        if (content.length > 0) {
                            content.map(function(event) {
                                // if(event.title != ''){
                                tds = [
                                    '<td>' + event.title +
                                    '<br><span class="text-muted font-size-sm">' + event
                                    .start +
                                    '</span></td>',
                                    '<td><span class="badge badge-' + event
                                    .props.color + '">' + event
                                    .props.status + '</td>',
                                ];
                                $('.appendLeave').append('<tr>' + tds.join('') +
                                    '</tr>');
                                // }

                            });
                        } else {
                            tds = [
                                '<td colspan="3">No Leave Details Found</td>',
                            ];
                            $('.appendLeave').append('<tr>' + tds.join('') +
                                '</tr>');
                        }

                    },
                    eventDidMount: function(info) {
                        const event = info.event;
                        const leave_kind = event.extendedProps.leave_kind;
                        const leave_category = event.extendedProps.leave_category;
                        const status = event.extendedProps.props.status;
                        const tooltipContent =
                            `<strong>${event.title}</strong><br>Leave Categroy: ${leave_kind}<br>Leave Type: ${leave_category}<br>Status: ${status}`;
                        // info.el.setAttribute('title', tooltipContent);
                        // info.el.classList.add('custom-tooltip');

                        tippy(info.el, {
                            content: tooltipContent,
                            arrow: true,
                        });

                    },
                    // dayRenderInfo: function(info) {
                    //     alert('asd');
                    //     info.el.innerHTML += "<button class='dayButton' data-date='" + info.date +
                    //         "'>Click me</button>";
                    //     info.el.style.padding = "20px 0 0 10px";
                    // },
                    select: function(event_start, event_end, allDay) {
                        // if (checkRole) {
                        //     console.log(event_start);
                        //     var event_start = $.fullCalendar.formatDate(event_start, "Y-MM-DD");

                        //     form = $(".eventForm");
                        //     $(".eventForm")[0].reset();

                        //     callDatePicker('event_start_date', event_start);
                        //     callDatePicker('event_end_date', event_start, '', event_start);

                        //     $('#event_start_date').val(event_start);
                        //     $('#start-timepicker').clockTimePicker();
                        //     $('#eventModal').modal('show');
                        // }

                    },
                    eventDrop: function(info) {
                        console.log(info.event.title, info.event.start);
                    },
                    eventClick: function(event) {

                        if (event.type == 'holiday') {
                            $('#showEventModal').find('.modal-title').text('View Holiday');
                        } else if (event.type == 'event') {
                            $('#showEventModal').find('.modal-title').text('View Event');
                        }


                        $.ajax({
                            type: "POST",
                            url: SITEURL + '/store-calendar-event-ajax',
                            data: {
                                id: event.id,
                                category: event.type,
                                type: 'view',
                                _token: "{{ csrf_token() }}"

                            },
                            success: function(response) {
                                $('#showEventModal').find('.modal-body').html(
                                    response
                                    .event);

                                $('#showEventModal').modal('show');
                            }
                        });


                    }
                });

                // Init
                calendarEventColorsInit.render();

                // Resize calendar when sidebar toggler is clicked
                $('.sidebar-control').on('click', function() {
                    calendarEventColorsInit.updateSize();
                });

                $('.fc-prev').on('click', function() {
                    calendarEventColorsInit.prev();
                    setSelectedMonth()

                });

                $('.fc-next').on('click', function() {
                    calendarEventColorsInit.next();
                    setSelectedMonth()

                });

                $('.fc-today').on('click', function() {
                    calendarEventColorsInit.today();
                    setSelectedMonth()
                });

                // Create custom month dropdown
                const monthSelect = document.getElementById('monthSelect');
                const months = moment.months(); // Get month names from Moment.js
                months.forEach((month, index) => {
                    const option = new Option(month, index);
                    monthSelect.appendChild(option);
                });

                // Listen to changes in the month dropdown
                $(monthSelect).on('change', function() {
                    const selectedMonth = parseInt($(this).val(), 10);
                    calendarEventColorsInit.gotoDate(moment().month(selectedMonth).startOf('month')
                        .format(
                            'YYYY-MM-DD'));
                });

                // $('#organization_id').change(function() {
                //     calendarEventColorsInit.refetchEvents();
                // });

                // $('#calendarFilter').on('submit', function(e) {
                //     e.preventDefault();
                //     calendarEventColorsInit.destroy();
                //     $(this).find('button[type=submit]').attr('disabled', false).find('.spinner')
                //         .remove();
                //     // calendarEventColorsInit.refetchEvents();
                //     calendarEventColorsInit.render();

                // })
                setSelectedMonth();

                function setSelectedMonth() {
                    var cdate = calendarEventColorsInit.getDate();
                    var selectedMonth = cdate.getMonth();
                    monthSelect.value = selectedMonth;
                }
            }
        };

        return {
            init: function() {
                _componentFullCalendarStyling();
            }
        }
    }();


    // Initialize module
    // ------------------------------

    document.addEventListener('DOMContentLoaded', function() {
        FullCalendarStyling.init();
    });
</script>
@endSection
