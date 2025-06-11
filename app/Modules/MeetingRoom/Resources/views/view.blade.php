@extends('admin::layout')
@section('title') Meeting Room @stop

@section('breadcrum')
    <a href="{{ route('meetingRoom.index') }}" class="breadcrumb-item">Meeting Room</a>
    <a class="breadcrumb-item active">Bookings</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Meeting Schedule</h6>
                All the Meeting Information will be listed below.
            </div>

        </div>
    </div>

    <div class="card">
        <div class="bg-secondary card-header header-elements-inline border-bottom-0 text-white">
            <h5 class="card-title text-uppercase font-weight-semibold">Filter</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('meetingRoom.view', $room->id) }}">
                <div class="row">
                    @if (setting('calendar_type') == 'BS')
                        <div class="col-md-3">
                            <label class="d-block font-weight-semibold">Date:</label>
                            <div class="input-group">
                                <input type="text" name="date" id="date"
                                    class="form-control nepali-calendar date"
                                    value="{{ request()->date ?? date_converter()->eng_to_nep_convert_two_digits(Carbon\Carbon::now()->format('Y-m-d')) }}">
                            </div>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="example-email" class="form-label">Date:</label>
                                <div class="input-group">
                                    <input type="text" name="date" id="date"
                                        class="form-control daterange-single date"
                                        value="{{ request()->date ?? Carbon\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button class="btn bg-yellow mr-1" type="submit">
                        <i class="icons icon-filter3 mr-1"></i>Filter
                    </button>

                    <a href="{{ request()->url() }}" class="btn bg-secondary text-white"><i
                            class="icons icon-reset mr-1"></i>Reset</a>
                </div>
            </form>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>Date</th>
                    <th>Time Range</th>
                    <th>Purpose</th>
                    <th>Booked By</th>

                </tr>
            </thead>
            <tbody>
                @if ($detailInfo->count() > 0)
                    @foreach ($detailInfo as $key => $info)
                        <tr>
                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert_two_digits($info->date) }}
                                @else
                                    {{ $info->date }}
                                @endif
                            </td>
                            <td>{{ Carbon\Carbon::parse($info->start_time)->format('h:i A') }} ->
                                {{ Carbon\Carbon::parse($info->end_time)->format('h:i A') }} </td>
                            <td>{{ $info->purpose }}</td>
                            <td>{{ optional(optional($info->user)->userEmployer)->full_name ?? 'Administrator' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>!!!! No Schedule Found !!!!</td>
                    </tr>
                @endif

            </tbody>

        </table>

    </div>


@endsection
