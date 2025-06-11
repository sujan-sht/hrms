@extends('admin::layout')

@section('breadcrum')
    <a class="breadcrumb-item active">Activity Logs</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('user::activityLog.partial.filter')

        <div class="card card-body">
            <div class="media align-items-center align-items-md-start flex-column flex-md-row">
                <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                    <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
                </a>
                <div class="media-body text-center text-md-left">
                    <h6 class="media-title font-weight-semibold">Activity Logs</h6>
                    All the Activity Logs Information will be listed below.
                </div>

                {{-- <div class="mt-1">
                    <a href="{{ route('exportDailyAttendanceReport', request()->all()) }}"
                        class="btn btn-success rounded-pill export-btn"><i class="icon-file-excel"></i> Export</a>
                </div> --}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                       <th>Type</th>
                       <th>Date</th>
                       <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activityLogs as $key => $activityLog)
                        <tr>
                            <td>#{{ $activityLogs->firstItem() + $key }} </td>
                            <td class="d-flex text-nowrap">
                                <div class="media">
                                    <div class="mr-3">
                                        <a href="#">
                                            <img src="{{ optional($activityLog->employee)->getImage() }}" class="rounded-circle" width="40"
                                                height="40" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">{{ optional($activityLog->employee)->full_name }}</div>
                                        <span class="text-muted">ID: {{ optional($activityLog->employee)->employee_code }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ ucfirst($activityLog->type) }}</td>
                            @if (setting('calendar_type') == 'BS')
                                <td>{{ $activityLog->nepali_date }}</td>
                            @else
                                <td>{{ $activityLog->date }}</td>
                            @endif
                            <td>{{ date('h:i A', strtotime($activityLog->created_at)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $activityLogs->appends(request()->all())->links() }}
            </span>
        </div>
@endsection

