@extends('admin::employee.layout')
@section('title') Holidays & Events @stop
@section('breadcrum') Holidays & Events @stop

@section('scripts')
    <script src="{{asset('employee/js/eventFullCalendar_styling.js')}}"></script>
    {{--<script src="{{asset('employee/js/calendar_basic.js')}}"></script>--}}
    <script>
        $(document).ready(function() {
            $("#list").on("click", function () {
                $(".list-view").css('display', '');
                $(".grid-view").css('display', 'none');
                $("#list").addClass("active");
                $("#grid").removeClass("active");
            });

            $("#grid").on("click", function () {
                $(".grid-view").css('display', '');
                $(".list-view").css('display', 'none');
                $("#grid").addClass("active");
                $("#list").removeClass("active");
            });

            $('.view_event_detail').on('click', function () {
                var eid = $(this).attr('eid');
                var type = $(this).attr('type');
                $.ajax({
                    type: 'GET',
                    url: "{{url('employee/event/view')}}/"+eid,
                    data: {
                        type: type
                    },
                    success: function (data) {
                        $('.result_view_detail').html(data);
                    }
                });

            });


            var events = [];
            @if($all_dates->total() != 0)
            @foreach($all_dates as $key => $value)
            // Default events
            events.push({
                title: '{{ $value->title }}',
                start: '{{ $value->type == "event" ? $value->event_date."T".$value->event_time.":00" : $value->event_date }}',
                color: '{{ $value->type == "event" ? "#039be5" : "red"}}',
                type: '{{$value->type}}',
                eid: '{{$value->id}}',
                allDay: '{{ $value->type == "event" ?  false : true}}',
                description: '{{ $value->type == "event" ? $value->description : ""}}',
                note: '{{ $value->type == "event" ? $value->note : ""}}',
                datetime: '{{ $value->type == "event" ? date("Y-m-d g:i A", strtotime($value->event_date.' '.$value->event_time)) : $value->event_date }}',
                created_by : '{{ $value->type == "event" && $value->created_by == auth()->user()->id ? "self" : "other" }}'
            });
            @endforeach
            @endif
            _componentFullCalendarStyling(events);

            $('.delete_event').on('click', function() {
                var link = $(this).attr('link');
                $('.get_link').attr('href', link);
            });
        })
    </script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div>
    <div class="box">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-header table-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Holiday and Events</h6>
                        <div class="d-flex align-items-center">
                            @if($menuRoles->assignedRoles('employee-event.create'))
                                <a href="{{route('employee-event.create')}}" class="btn btn-primary text-white" type="button"><i class="fa fa-plus"></i>
                                    &nbsp;Add Event</a>
                            @endif
                            <div class="btn-group btn-group-toggle ml-3">
                                <a href="{{ route('employee-event.index') }}" class="text-white">
                                    <label class="btn btn-secondary active" id="grid">
                                        N
                                    </label>
                                </a>
                                <a href="{{ route('employee-event-english.index') }}">
                                    <label class="btn btn-secondary btn-warning" id="list">
                                        E
                                    </label>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body table-content list-view"  style="display:none">
                        <table class="table">
                            <tr>
                                <th scope="col">S. No.</th>
                                <th scope="col">Date</th>
                                <th scope="col">Title</th>
                                <th scope="col">Time</th>
                                <th scope="col">Created By</th>
                                <th scope="col">Action</th>
                            </tr>
                            @if($holiday_events->total() != 0)
                                @foreach($holiday_events as $key => $value)
                                    <tr>
                                        <td>{{++$key}}</td>
                                        <td>{{ $value->event_date }}</td>
                                        <td>{{ $value->title }}</td>
                                        <td>{{ $value->type == 'event' ? date('g:i A', strtotime($value->event_time)) : '-' }}</td>
                                        <td>{{ \App\Modules\User\Repositories\UserRepository::getName($value->created_by) }}</td>
                                        <td>
                                            <div class="action-icons">
                                                @if(($value->type == 'event' && $value->created_by == auth()->user()->id && $menuRoles->assignedRoles('employee-event.edit')) ||
                                                ($value->type == 'event' && $value->created_by == auth()->user()->id && $menuRoles->assignedRoles('employee-event.delete')))

                                                    <a class="view view_event_detail" data-toggle="modal" data-target="#modal_theme_view"
                                                       data-placement="bottom" data-popup="tooltip" data-original-title="View"
                                                       eid="{{$value->id}}" type="{{$value->type}}">
                                                        <i class="fa fa-eye"></i></a>

                                                    @if($value->type == 'event' && $value->created_by == auth()->user()->id)
                                                        @if($menuRoles->assignedRoles('employee-event.edit'))
                                                            <a class="edit"  href="{{ route('employee-event.edit',$value->id) }}"
                                                               data-popup="tooltip" data-original-title="Edit Event">
                                                                <i class="fa fa-edit"></i></a>
                                                        @endif

                                                        @if($menuRoles->assignedRoles('employee-event.delete'))
                                                            <a class="delete delete_event" data-toggle="modal" data-target="#modal_theme_warning"
                                                               link="{{ route('employee-event.delete', $value->id) }}" data-placement="bottom" data-popup="tooltip"
                                                               data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <a class="view view_event_detail" data-toggle="modal" data-target="#modal_theme_view"
                                                       data-placement="bottom" data-popup="tooltip" data-original-title="View"
                                                       eid="{{$value->id}}" type="{{$value->type}}">
                                                        <i class="fa fa-eye"></i></a>

                                                @endif
                                            </div>
                                        </td>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">No Holiday Found !!!</td>
                                </tr>
                            @endif
                        </table>
                        <span style="margin: 5px;float: right;">
                        @if($holiday_events->total() != 0)
                                {{ $holiday_events->links() }}
                            @endif
                    </span>
                    </div>

                    <div class="bd-events grid-view">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="fullcalendar-event-colors"></div>
{{--                                        <div class="fullcalendar-basic"></div>--}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Warning modal -->
    <div id="modal_theme_warning" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <center>
                        <i class="icon-alert text-danger icon-3x"></i>
                    </center>
                    <br>
                    <center>
                        <h2>Are You Sure Want To Delete ?</h2>
                        <a class="btn btn-success get_link" href="">Yes, Delete It!</a>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->

    <!-- Warning modal -->
    <div id="modal_view" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <center>
                        <h2>Event/Holiday Detail</h2>
                        <hr>
                        <div id="view_event_detail"></div>
                        <hr>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <!-- /warning modal -->

    <div class="modal modal-events fade" id="modal_theme_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content result_view_detail">
            </div>
        </div>
    </div>
    </div>

@stop
