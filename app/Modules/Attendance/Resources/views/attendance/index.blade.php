@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
    <a href="" class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    @php
        $checkin_colors = ['Late' => 'danger', 'On Time' => 'primary', 'Early' => 'success', '-' => ''];
        $checkout_colors = ['Early' => 'danger', 'On Time' => 'primary', 'Late' => 'success', '-' => 'Not Decided'];
    @endphp

    @include('attendance::attendance.partial.advance_search')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Attendance</h6>
                All the Attendance Information will be listed below. You can Create and Modify the data.

            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    @if (Auth::user()->user_type != 'employee')
                        <th>Employee Name</th>
                    @endif
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                    <th>Working Hours</th>
                </tr>
            </thead>
            <tbody>
                @if ($attendanceModels->total() > 0)
                    @foreach ($attendanceModels as $key => $attendanceModel)
                        <tr>
                            <td>
                                {{ '#' . ++$key }}
                            </td>
                            @if (Auth::user()->user_type != 'employee')
                                <td class="d-flex text-nowrap">
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ optional($attendanceModel->employee)->getImage() }}"
                                                    class="rounded-circle" width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ optional($attendanceModel->employee)->full_name }}</div>
                                            <span class="text-muted">ID:
                                                {{ optional($attendanceModel->employee)->employee_code }}</span>
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td>
                                {{ date('M d, Y', strtotime($attendanceModel->date)) }}
                            </td>
                            <td>
                                {{ $attendanceModel->checkin ? date('h:i A', strtotime($attendanceModel->checkin)) : '-' }}
                                <br>

                                <button type="button"
                                    class="badge badge-flat alpha-{{ $checkin_colors[$attendanceModel->checkin_status] }} text-{{ $checkin_colors[$attendanceModel->checkin_status] }}-800 border-{{ $checkin_colors[$attendanceModel->checkin_status] }}-600">{{ $attendanceModel->checkin_status }}</button>
                            </td>
                            <td>
                                {{ $attendanceModel->checkout ? date('h:i A', strtotime($attendanceModel->checkout)) : '-' }}
                                <br>
                                <button type="button"
                                    class="badge badge-flat alpha-{{ $checkout_colors[$attendanceModel->checkout_status] }} text-{{ $checkout_colors[$attendanceModel->checkout_status] }}-800 border-{{ $checkout_colors[$attendanceModel->checkout_status] }}-600">{{ $attendanceModel->checkout_status }}</button>
                            </td>
                            <td>

                            </td>
                            <td>
                                {{ $attendanceModel->total_working_hr }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">No record found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-12">
            <ul class="pagination pagination-rounded justify-content-end mb-3">
                @if ($attendanceModels->total() != 0)
                    {{ $attendanceModels->links() }}
                @endif
            </ul>
        </div>
    </div>

@endsection
