@extends('admin::layout')
@section('title') Meeting Room @stop

@section('breadcrum')
    <a href="{{ route('meetingRoom.index') }}" class="breadcrumb-item">Meeting Room</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Rooms</h6>
                All the Room Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('meetingRoom.create'))
                <div class="mt-1">
                    <a href="{{ route('meetingRoom.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
                </div>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>#</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Floor</th>
                    <th>Wifi Password</th>
                    <th>Schedule For Today</th>
                    @if (
                        $menuRoles->assignedRoles('meetingRoom.edit') ||
                            $menuRoles->assignedRoles('meetingRoom.delete') ||
                            $menuRoles->assignedRoles('meetingRoom.view'))
                        <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($rooms->total() != 0)


                    @foreach ($rooms as $key => $room)
                        <tr>
                            <td>{{ $rooms->firstItem() + $key }}</td>
                            <td>{{ $room->room_name }}</td>
                            <td>{{ $room->room_code }}</td>
                            <td>
                                {{ $room->floor }}
                            </td>
                            <td>{{ $room->wifi_password }}</td>
                            <td>
                                @foreach ($room->getTodayBooking as $info)
                                    - {{ Carbon\Carbon::parse($info->start_time)->format('h:i A') }} ->
                                    {{ Carbon\Carbon::parse($info->end_time)->format('h:i A') }} <br>
                                @endforeach
                            </td>


                            <td>

                                @if ($menuRoles->assignedRoles('meetingRoom.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('meetingRoom.edit', $room->id) }}" data-popup="tooltip"
                                        data-original-title="Edit" data-placement="bottom">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('meetingRoom.delete'))
                                    <a data-toggle="modal" data-target="#modal_theme_warning"
                                        class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                        link="{{ route('meetingRoom.delete', $room->id) }}" data-popup="tooltip"
                                        data-original-title="Delete" data-placement="bottom">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('meetingRoom.view'))
                                    <a class="btn btn-outline-secondary btn-icon mx-1"
                                        href="{{ route('meetingRoom.view', $room->id) }}" data-popup="tooltip"
                                        data-original-title="View Bookings" data-placement="bottom">
                                        <i class="icon-eye"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('meetingRoom.booking'))
                                    <a data-toggle="modal" data-target="#bookingRoom"
                                        class="btn btn-outline-warning btn-icon bookingRoom mx-1"
                                        data-id="{{ $room->id }}" data-popup="tooltip" data-placement="top"
                                        data-original-title="Schedule Your Meeting">
                                        <i class="icon-flag3"></i>
                                    </a>
                                @endif



    </div>
    </div>

    </td>
    </tr>
    @endforeach
@else
    <tr>
        <td class="">No Rooms Found !!!</td>
    </tr>
    @endif
    </tbody>

    </table>
    <span style="margin: 5px;float: right;">
        @if ($rooms->total() != 0)
            {{ $rooms->links() }}
        @endif
    </span>
    </div>
    </div>

    <div id="bookingRoom" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Book A Meeting Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('meetingRoom.booking') }}" method="post" id="submit_booking"
                        class="roomBookingForm">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" value="" name="room_id" class="room_id" id="room_id">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="purpose">Purpose</label>
                                    <input type="text" name="purpose" id="purpose" class="form-control purpose">

                                </div>
                                <div class="col-lg-6">
                                    <label for="date">Meeting Date</label>
                                    @if (setting('calendar_type') == 'BS')
                                        <input type="text" name="date" id="date"
                                            class="form-control nepali-calendar date">
                                    @else
                                        <input type="text" name="date" id="date"
                                            class="form-control daterange-single date">
                                    @endif
                                </div>
                                <div class="col-lg-6">
                                    <label for="start_time">Time From</label>
                                    <input type="time" name="start_time" id="start_time" class="form-control start_time">


                                </div>
                                <div class="col-lg-6">
                                    <label for="end_time">Time To</label>
                                    <input type="time" name="end_time" id="end_time" class="form-control end_time">


                                </div>
                            </div>
                            <div id="message" class="mt-3">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success d-none" id="submit_btn">Book A Meeting</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#submit_booking').validate({
                rules: {
                    date: "required",
                    start_time: "required",
                    end_time: "required"
                },
                messages: {
                    date: "Please enter date",
                    start_time: "Please enter start time",
                    end_time: "Please enter end time"

                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
            $('.bookingRoom').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#room_id').val(id);
                $('.purpose').val('');
                $('.date').val('');
                $('.start_time').val('');
                $('.end_time').val('');
                $('#message').html('');
                $('#submit_btn').addClass('d-none');
                $('.roomBookingForm').find('#room_id').val(id);
            });
            $('.date').on('change', function() {
                $('.start_time').val('');
                $('.end_time').val('');
            });
            $('.start_time').on('change', function() {
                $('.end_time').val('');
            });
            $('.end_time').on('change', function() {
                var date = $('.date').val();
                var start_time = $('.start_time').val();
                var end_time = $(this).val();
                var room_id = $('.room_id').val();

                $.ajax({
                    url: "{{ url('admin/meetingRoom/checkBookingExists') }}", // Add this route in your web.php
                    method: 'GET',
                    data: {
                        room_id: room_id,
                        date: date,
                        start_time: start_time,
                        end_time: end_time
                    },
                    success: function(data) {
                        if (data.status === 'error') {
                            $('#message').html('<span class="text-danger">' + data.message +
                                '</span>');
                            $('#submit_btn').addClass('d-none');

                        } else {
                            $('#message').html('<span class="text-success">' + data.message +
                                '</span>');
                            $('#submit_btn').removeClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error.message);
                    }
                });

            });



        });
    </script>
@endsection
