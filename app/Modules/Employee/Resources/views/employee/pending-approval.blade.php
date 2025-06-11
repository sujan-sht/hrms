@extends('admin::layout')

@section('title')
    {{ $title }}s
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">{{ $title }}s</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('employee::employee.partial.pending-approval-filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of {{ $title }}s</h6>
                All the {{ $title }}s Information will be listed below.
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped mb-0">
                            <thead>
                                <tr class="text-white">
                                    <th>S.N</th>
                                    <th>Employee Name</th>
                                    <th>Type of Request</th>
                                    <th>Date</th>
                                    <th>Remarks/ Reason</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @inject('employee', 'App\Modules\Employee\Entities\Employee')
                                @if (count($pendingApprovals) > 0)
                                    @foreach ($pendingApprovals as $key => $pendingApproval)
                                        <tr>
                                            <td> #{{ $key + 1 }}</td>
                                            <td>
                                                @php
                                                    $emp = $employee->find($pendingApproval['employee_id']);
                                                @endphp
                                                <div class="media">
                                                    <div class="mr-3">
                                                        <a href="#">
                                                            <img src="{{ optional($emp)->getImage() }}"
                                                                class="rounded-circle" width="40" height="40"
                                                                alt="">
                                                        </a>
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="media-title font-weight-semibold">
                                                            {{ optional($emp)->getFullName() }}</div>
                                                        <span
                                                            class="text-muted">{{ optional($emp)->official_email ?? optional($emp)->personal_email }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $pendingApproval['type'] }}</td>
                                            <td>
                                                @if (setting('calendar_type') == 'BS')
                                                    {{ date_converter()->eng_to_nep_convert($pendingApproval['date']) }}
                                                @else
                                                    {{ getStandardDateFormat($pendingApproval['date']) }}
                                                @endif
                                            </td>
                                            <td>{{ $pendingApproval['title'] }}</td>
                                            <td>{{ $pendingApproval['status'] == 1 ? 'Pending' : '' }}</td>
                                            <td>
                                                @php
                                                    $route = '';
                                                    switch ($pendingApproval['type']) {
                                                        case 'claim':
                                                            $route = route('tada.index');
                                                            if (
                                                                auth()->user()->user_type == 'supervisor' &&
                                                                auth()->user()->emp_id !=
                                                                    $pendingApproval['employee_id']
                                                            ) {
                                                                $route = route('tada.showTeamClaim');
                                                            }
                                                            break;
                                                        case 'request':
                                                            $route = route('tadaRequest.index');
                                                            if (
                                                                auth()->user()->user_type == 'supervisor' &&
                                                                auth()->user()->emp_id !=
                                                                    $pendingApproval['employee_id']
                                                            ) {
                                                                $route = route('tadaRequest.showTeamRequest');
                                                            }
                                                            break;
                                                        case 'leave':
                                                            $route = route('leave.index');
                                                            if (
                                                                auth()->user()->user_type == 'supervisor' &&
                                                                auth()->user()->emp_id !=
                                                                    $pendingApproval['employee_id']
                                                            ) {
                                                                $route = route('leave.showTeamleaves');
                                                            }
                                                            break;
                                                        case 'attendance':
                                                            $route = route('attendanceRequest.index');
                                                            if (
                                                                auth()->user()->user_type == 'supervisor' &&
                                                                auth()->user()->emp_id !=
                                                                    $pendingApproval['employee_id']
                                                            ) {
                                                                $route = route('attendanceRequest.showTeamAttendance');
                                                            }
                                                            break;
                                                        case 'businessTrip':
                                                            $route = route('businessTrip.index');
                                                            if (
                                                                auth()->user()->user_type == 'supervisor' &&
                                                                auth()->user()->emp_id !=
                                                                    $pendingApproval['employee_id']
                                                            ) {
                                                                $route = route('businessTrip.teamRequests');
                                                            }
                                                            break;
                                                        default:
                                                            $route = '';
                                                            break;
                                                    }

                                                @endphp

                                                <a class="btn btn-outline-secondary btn-icon mx-1"
                                                    href="{{ $route }}" data-popup="tooltip"
                                                    data-original-title="View" data-placement="bottom">
                                                    <i class="icon-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">No record found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <ul class="pagination pagination-rounded justify-content-end mb-3">
                @if ($pendingApprovals->total() != 0)
                    {{ $pendingApprovals->links() }}
                @endif
            </ul>
        </div>
    </div>


@endsection

@section('script')
    <script type="text/javascript">
        $('document').ready(function() {


        });
    </script>
@endsection
