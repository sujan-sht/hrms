@extends('admin::layout')
@section('title') Calendar @endSection
@section('breadcrum')
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
<script src="{{ asset('admin/global/js/demo_pages/main.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/3.4.1/tippy.all.min.js"></script>
<script src="{{ asset('admin/assets/js/plugins/forms/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('admin/validation/event.js') }}"></script>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
@if ($menuRoles->assignedRoles('event.create'))
    <div class="row">
        <div class="col-lg-12">
            <div class="media align-items-center align-items-md-start flex-column flex-md-row float-right">
                <a href="javascript:void(0)" class="btn btn-success rounded-pill mb-1 create-event">Create Event</a>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-lg-3">
        <div class="card">

            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title">Event Detail</h6>
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

                            <tbody class="appendEvent">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-transparent header-elements-inline">
                <h6 class="card-title">Holiday Detail</h6>
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

                            <tbody class="appendHoliday">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>

    </div>
    <div class="col-lg-9">
        <div class="card card-body">


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

        </div>

    </div>
</div>

<!-- popup modal -->
<div id="eventModal" class="modal fade bd-example-modal-xl" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">Create Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open([
                    'method' => 'POST',
                    'id' => 'event_submit',
                    'class' => 'form-horizontal eventForm',
                    'role' => 'form',
                    'files' => true,
                ]) !!}
                {!! Form::hidden('type', 'create', []) !!}
                @include('event::event.partial.action', ['users' => $users, 'btnType' => 'Save'])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script>
    const FullCalendarStyling = function() {
        // External events
        const _componentFullCalendarStyling = function() {
            if (typeof FullCalendar == 'undefined') {
                toastr.warning('Warning - Fullcalendar files are not loaded.');
                return;
            }

            // Define element
            const calendarEventColorsElement = document.querySelector('.fullcalendar-event-colors');

            var SITEURL = "{{ url('admin/') }}";
            var userId = "{{ auth()->user()->id }}";

            defaultDate = new Date();
            checkRole = "{{ $menuRoles->assignedRoles('event.create') }}";
            // Initialize
            if (calendarEventColorsElement) {
                const calendarEventColorsInit = new FullCalendar.Calendar(calendarEventColorsElement, {
                    headerToolbar: {

                        right: '',
                        center: 'title',
                        left: 'dayGridMonth,dayGridWeek,listWeek'

                    },
                    initialDate: defaultDate,
                    navLinks: true, // can click day/week names to navigate views
                    businessHours: true, // display business hours
                    editable: true,
                    selectable: true,
                    events: SITEURL + "/calendar-ajax",
                    // eventSources: [{
                    //     url: SITEURL + "/leave/calendar-ajax",
                    //     method: 'GET',
                    //     extraParams: {
                    //         organization_id: organization_id,
                    //         branch_id: branch_id,
                    //         leave_type_id: leave_type_id,
                    //         leave_kind: leave_kind,
                    //         status: status
                    //     },
                    //     failure: function() {
                    //         toastr.error('there was an error while fetching events!');
                    //     },
                    //     color: 'yellow', // a non-ajax option
                    //     textColor: 'black' // a non-ajax option
                    // }],
                    eventSourceSuccess: function(content, response) {
                        $('.appendHoliday').empty();
                        $('.appendEvent').empty();

                        if (content.length > 0) {
                            content.map(function(event) {
                                if (event.type == 'event') {
                                    tds = [
                                        '<td>' + event.title +
                                        '<br><span class="text-muted font-size-sm">' +
                                        moment(event.start).format(
                                            'MMMM D, YYYY h:mmA') + '</span></td>',
                                        '<td><span class="badge badge-info">' + event
                                        .type + '</td>',
                                    ];

                                    $('.appendEvent').append('<tr>' + tds.join('') +
                                        '</tr>');
                                } else if (event.type == 'holiday') {

                                    tds = [
                                        '<td>' + event.title +
                                        '<br><span class="text-muted font-size-sm">' +
                                            moment(event.start).format('MMMM D, YYYY') + '</span></td>',
                                        '<td><span class="badge badge-danger">' + event
                                        .type + '</td>',
                                    ];
                                    event
                                    $('.appendHoliday').append('<tr>' + tds.join('') +
                                        '</tr>');
                                }
                            });
                        }
                    },
                    eventDidMount: function(info) {
                        const event = info.event;
                        const type = event.extendedProps.type;
                        const desc = event.extendedProps.description;
                        const tooltipContent =`<strong>${event.title}</strong><br>Type: ${type}<br>Description: ${desc}`;
                        tippy(info.el, {
                            content: tooltipContent,
                            arrow: true,
                        });

                    },
                    select: function(event_start, event_end, allDay) {
                        if (checkRole) {
                            var event_start = moment(event_start).format('Y-MM-DD');

                            form = $(".eventForm");
                            $(".eventForm")[0].reset();
                            callDatePicker('event_start_date', event_start);
                            callDatePicker('event_end_date', event_start, '', event_start);

                            $('#event_start_date').val(event_start);
                            $('#start-timepicker').clockTimePicker();
                            $('#tagged_users').multiselect({
                                includeSelectAllOption: true,
                                enableFiltering: true,
                                enableCaseInsensitiveFiltering: true
                            });
                            $('#eventModal').modal('show');
                        }

                    },
                    eventDrop: function(info) {
                        event = info.event;
                        if (event.extendedProps.type == 'holiday') {
                            toastr.error('Unable to move holiday', 'Holiday');
                            return false;
                        }

                        if (event.extendedProps.type == 'event' && event.extendedProps.created_by !=
                            userId) {
                            toastr.error('User can only drag ther own events only', 'Event');
                            return false;
                        }
                        var event_start = moment(event.start).format('Y-MM-DD');

                        var event_end = event.end ? moment(event.end).format('Y-MM-DD') : '';
                        $.ajax({
                            url: SITEURL + '/store-calendar-event-ajax',
                            data: {
                                title: event.title,
                                start: event_start,
                                end: event_end,
                                id: event.id,
                                type: 'edit',
                                _token: "{{ csrf_token() }}"

                            },
                            type: "POST",
                            success: function(response) {
                                displayMessage("Event updated");

                            }
                        });
                    },
                    // eventClick: function(event) {

                    //     if (event.type == 'holiday') {
                    //         $('#showEventModal').find('.modal-title').text('View Holiday');
                    //     } else if (event.type == 'event') {
                    //         $('#showEventModal').find('.modal-title').text('View Event');
                    //     }


                    //     $.ajax({
                    //         type: "POST",
                    //         url: SITEURL + '/store-calendar-event-ajax',
                    //         data: {
                    //             id: event.id,
                    //             category: event.type,
                    //             type: 'view',
                    //             _token: "{{ csrf_token() }}"

                    //         },
                    //         success: function(response) {
                    //             $('#showEventModal').find('.modal-body').html(
                    //                 response
                    //                 .event);

                    //             $('#showEventModal').modal('show');
                    //         }
                    //     });
                    // }
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

                setSelectedMonth();

                function setSelectedMonth() {
                    var cdate = calendarEventColorsInit.getDate();
                    var selectedMonth = cdate.getMonth();
                    monthSelect.value = selectedMonth;
                }

                function displayMessage(message) {
                    toastr.success(message, 'Event');
                }

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

                $('.create-event').on('click', function() {
                    $(".eventForm")[0].reset();
                    $('#start-timepicker').clockTimePicker();
                    $(".eventForm").find('button[type=submit]').attr('disabled', false).find('.spinner')
                        .remove();
                    $('#tagged_users').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true
                    });
                    // $('.tagged_users_select').multiSelect('select', String|Array);
                    $('#eventModal').modal('show');

                })

                $(".eventForm").submit(function(e) {
                    e.preventDefault();
                    var SITEURL = "{{ url('admin/') }}";
                    form = $(this).serialize();

                    if ($(".eventForm").valid()) {
                        $.ajax({
                            url: SITEURL + '/store-calendar-event-ajax',
                            data: form,
                            type: "POST",
                            dataType: "json",
                            success: function(res) {
                                calendarEventColorsInit.addEvent(res.event);
                                calendarEventColorsInit.render();

                                $('#eventModal').modal('hide');
                                displayMessage(res.msg);

                            }
                        });
                    }
                });
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
