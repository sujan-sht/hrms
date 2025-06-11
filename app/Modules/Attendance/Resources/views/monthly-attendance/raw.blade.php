@extends('admin::layout')

@section('breadcrum')
    <a href="{{ route('monthlyAttendance') }}" class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('atdReportRepo', '\App\Modules\Attendance\Repositories\AttendanceReportRepository')


@section('content')
    {{-- @include('attendance::monthly-attendance.partial.raw-filter') --}}


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Attendance</h6>
                All the Attendance Information will be listed below. You can view the data.
            </div>
            @if (Auth::user()->user_type != 'employee')
                <div class="mt-1">
                    <a href="{{ route('exportRawAttendance', request()->all()) }}" class="btn btn-success"><i
                            class="icon-file-excel"></i> Export</a>
                </div>
                {{-- <div class="mt-1 ml-1">
                        <a href="{{ route('downloadMonthlyAttendance', request()->all()) }}"
                            class="btn btn-warning rounded-pill"><i class="icon-file-download"></i> Download</a>
                    </div> --}}
            @endif
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        @if (Auth::user()->user_type != 'employee')
                            <th>Employee Name</th>
                            <th>Sub-Function</th>
                            <th>Designation</th>
                        @endif

                        <th>Check In</th>
                        <th>Check In Medium</th>
                        <th>Check Out</th>
                        <th>Check Out Medium</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            @if (Auth::user()->user_type != 'employee')
                                <td>{{ $attendance->employee->full_name }}</td>
                                <td>{{ optional($attendance->employee->department)->title }}</td>
                                <td>{{ optional($attendance->employee->designation)->title }}</td>
                            @endif
                            <td>{{ $attendance->checkin }}</td>
                            <td>{{ $attendance->checkin_from }}</td>
                            <td>{{ $attendance->checkout }}</td>
                            <td>{{ $attendance->checkout_from }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                @if (isset($attendances))
                    {{ $attendances->appends(request()->all())->links() }}
                @endif
            </span>
        </div>

    </div>
@endsection
