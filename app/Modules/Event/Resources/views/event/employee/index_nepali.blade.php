@extends('admin::employee.layout')
@section('title') Holidays & Events @stop
@section('breadcrum') Holidays & Events @stop

@section('scripts')
    <script src="{{asset('admin/nepalidatepicker/customized/calander.js')}}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="box">
        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-header table-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Holiday and Events nepali calander</h6>
                        <div class="d-flex align-items-center">
                            @if($menuRoles->assignedRoles('employee-event.create'))
                                <a href="{{route('employee-event.create')}}" class="btn btn-primary text-white"
                                   type="button"><i class="fa fa-plus"></i>
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

                    <div class="card-body table-content list-view" style="display:none">
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

                                                    <a class="view view_event_detail" data-toggle="modal"
                                                       data-target="#modal_theme_view"
                                                       data-placement="bottom" data-popup="tooltip"
                                                       data-original-title="View"
                                                       eid="{{$value->id}}" type="{{$value->type}}">
                                                        <i class="fa fa-eye"></i></a>

                                                    @if($value->type == 'event' && $value->created_by == auth()->user()->id)
                                                        @if($menuRoles->assignedRoles('employee-event.edit'))
                                                            <a class="edit"
                                                               href="{{ route('employee-event.edit',$value->id) }}"
                                                               data-popup="tooltip" data-original-title="Edit Event">
                                                                <i class="fa fa-edit"></i></a>
                                                        @endif

                                                        @if($menuRoles->assignedRoles('employee-event.delete'))
                                                            <a class="delete delete_event" data-toggle="modal"
                                                               data-target="#modal_theme_warning"
                                                               link="{{ route('employee-event.delete', $value->id) }}"
                                                               data-placement="bottom" data-popup="tooltip"
                                                               data-original-title="Delete"><i class="fa fa-trash"></i></a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <a class="view view_event_detail" data-toggle="modal"
                                                       data-target="#modal_theme_view"
                                                       data-placement="bottom" data-popup="tooltip"
                                                       data-original-title="View"
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
                                        {{--<div class="fullcalendar-event-colors"></div>--}}
                                        <div class="fullcalendar-basic nepali_cal">

                                            <nepali-calendar>

                                                <div slot="title" class="calander_nep">
                                                    <strong>२०००-२०९९</strong>
                                                    <small>नेपाली क्यालेण्डर</small>
                                                </div>
                                            </nepali-calendar>
                                        </div>
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
                        <h2>Event Detail</h2>
                        <hr>
                        <div id="view_event_detail"></div>
                        <hr>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </center>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-events fade" id="modal_theme_view" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content result_view_detail">
            </div>
        </div>
    </div>

    <script>
      var calendar = document.querySelector("nepali-calendar");

      calendar.addEventListener("click", () => {
        calendar.shadowRoot.querySelectorAll("div.day").forEach(item => {
          var task = item.getAttribute('class')
          if (task === 'day eventDay' || task === 'day holidayClass' || task === 'day holidayClass inactive' || task === 'day eventDay inactive') {
            item.removeAttribute('class')
            item.setAttribute('class', 'day')
          }
        })

        makeCalendarReady()
      });

      $(document).ready(function () {
        makeCalendarReady()
      });

      function makeCalendarReady () {
        var events = [];
          @if($all_dates->total() != 0)
          @foreach($all_dates as $key => $value)
        // Default events
        events.push({
          title: '{{ $value->title }}',
          start: '{{ $value->type == "event" ? $value->event_date."T".$value->event_time.":00" : $value->event_date }}',
          color: '{{ $value->type == "event" ? "#039be5" : "red"}}',
          class: '{{ $value->type == "event" ? 'eventDay' : 'holidayClass' }}',
          type: '{{$value->type}}',
          eid: '{{$value->id}}',
          allDay: '{{ $value->type == "event" ?  false : true}}',
          description: '{{ $value->type == "event" ? $value->description : ""}}',
          note: '{{ $value->type == "event" ? $value->note : ""}}',
          datetime: '{{ $value->type == "event" ? date("Y-m-d g:i A", strtotime($value->event_date.' '.$value->event_time)) : $value->event_date }}',
          created_by: '{{ $value->type == "event" && $value->created_by == auth()->user()->id ? "self" : "other" }}'
        });
        ;
          @endforeach
          @endif
        var calendar = document.querySelector("nepali-calendar");

        calendar.shadowRoot.querySelectorAll("div.day").forEach((item) => {
          var nepaliDate = item.querySelector(".np-date");
          var engDate = item.querySelector(".eng-full_date").innerHTML;
          var task = item.querySelector(".task");//
          var date = new Date(engDate);

          item.removeAttribute('class')
          item.setAttribute('class', 'day')

          var calanderDate = date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate()
          var today = new Date().getFullYear()+'-'+new Date().getMonth()+'-'+new Date().getDate()

          if (calanderDate === today) {
            task.parentElement.classList.add('today');
          }

          events.forEach((event) => {
            var eventDate = new Date(event.start);
            var eDate = eventDate.getFullYear() + '-' + eventDate.getMonth() + '-' + eventDate.getDate();

            if (eDate == calanderDate) {

              task.parentElement.classList.add(event.class);
              task.innerHTML = '<a  class ="event_detail" data-toggle="modal" data-target="#eventDetails"  event_id="' + event.eid + '" data-id="' + event.start + '">' + event.title + '</a>';

            }
          })
        });


        calendar.addEventListener("select", ({detail}) => {
          var selected_date = detail.int;
          $.ajax({
            type: 'GET',
            url: '{{route("upcomingReminder.display")}}',
            data: {'selected_date': selected_date},
            success: function (resp) {
              $('#view_event_detail').html(resp);
              $('#modal_view').modal('show');

            }
          });
        });

      }

    </script>
    <style>
        .event {
            background: purple !important;
        }
    </style>


@stop
