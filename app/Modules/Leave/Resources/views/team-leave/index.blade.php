@extends('admin::layout')
@section('title') Team Leave @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Team Leaves</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>

{{-- @include('leave::leave.partial.advance-filter', ['route' => route('leave.showTeamleaves')]) --}}

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Team Leaves</h6>
            All the Team Leaves Information will be listed below. You can Modify the data.
        </div>

        @if ($currentUserModel->user_type == 'supervisor')
            <div class="mt-1">
                <a href="{{ route('leave.teamRequestCreate') }}" class="btn btn-success rounded-pill">Create New</a>
            </div>
        @endif
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Employee</th>
                    <th>Leave Date</th>
                    <th>Number of Days</th>
                    <th>Leave Type</th>
                    <th>Leave Category</th>
                    <th>Reason</th>
                    <th>Status</th>
                    @if ($menuRoles->assignedRoles('leave.updateStatus'))
                        <th width="12%">Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($teamLeaveModels->count() != 0)
                    @foreach ($teamLeaveModels as $key => $teamLeaveModel)
                        @inject('leave_flow', '\App\Modules\Leave\Repositories\LeaveRepository')
                        @php
                            $emp_leave_flow = $leave_flow->getEmployeeApprovalFlow($teamLeaveModel->employee_id);
                            $emp_status = 8;
                            if ($emp_id == $teamLeaveModel->employee_id) {
                                $emp_status = 1; //pending
                            } else {
                                if (!empty($emp_leave_flow)) {
                                    if (
                                        !empty($emp_leave_flow->first_approval_user_id) &&
                                        $emp_leave_flow->first_approval_user_id > 0
                                    ) {
                                        if (
                                            $teamLeaveModel->status == 1 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 5; //pending, rejected, forwarded
                                        } elseif (
                                            $teamLeaveModel->status == 2 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 2; //forwarded
                                        } elseif (
                                            $teamLeaveModel->status == 4 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 4; //rejected
                                        } elseif (
                                            $teamLeaveModel->status == 2 &&
                                            $emp_leave_flow->last_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 7; //forwarded, rejected, accepted
                                        } elseif (
                                            $teamLeaveModel->status == 1 &&
                                            $emp_leave_flow->last_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 6; //pending
                                        } elseif (
                                            $teamLeaveModel->status == 3 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 3; //empty
                                        } elseif (
                                            $teamLeaveModel->status != 1 &&
                                            $teamLeaveModel->status != 2 &&
                                            $emp_leave_flow->last_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 3; //empty
                                        }
                                    } else {
                                        if ($emp_leave_flow->last_approval_user_id == $user_id) {
                                            $emp_status = 9; //rejected, accepted
                                        }
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td width="5%">#{{ $key + 1 }}</td>
                            <td>
                                <div class="media">
                                    <div class="mr-3">
                                        <a href="#">
                                            <img src="{{ optional($teamLeaveModel->employeeModel)->getImage() }}"
                                                class="rounded-circle" width="40" height="40" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ optional($teamLeaveModel->employeeModel)->getFullName() }}</div>
                                        <span
                                            class="text-muted">{{ optional($teamLeaveModel->employeeModel)->official_email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $teamLeaveModel->getDateRangeWithCount()['range'] }}</td>
                            <td>
                                @if (isset($teamLeaveModel->generated_by) && $teamLeaveModel->generated_by == 11)
                                    {{ $teamLeaveModel->generated_no_of_days }}
                                @else
                                    {{ $teamLeaveModel->getDateRangeWithCount()['count'] }}
                                @endif
                            </td>

                            <td>{{ optional($teamLeaveModel->leaveTypeModel)->name }}</td>
                            <td>{{ $teamLeaveModel->getLeaveKind() }}</td>
                            <td>{!! $teamLeaveModel->reason !!}</td>
                            <td>
                                <span
                                    class="badge badge-{{ $teamLeaveModel->getStatusWithColor()['color'] }}">{{ $teamLeaveModel->getStatusWithColor()['status'] }}</span>
                            </td>
                            <td>
                                <a class="btn btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('leave.show', $teamLeaveModel->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if (isset($teamLeaveModel->generated_by) && $teamLeaveModel->generated_by == 11)
                                    {{-- do nothing --}}
                                @else
                                    @if (auth()->user()->user_type == 'super_admin' ||
                                            ($menuRoles->assignedRoles('leave.updateStatus') && in_array($teamLeaveModel->status, [1, 2])))
                                        <a data-toggle="modal" data-target="#updateStatus"
                                            class="btn btn-outline-warning btn-icon updateStatus mr-1"
                                            data-id="{{ $teamLeaveModel->id }}"
                                            data-status="{{ $teamLeaveModel->status }}"
                                            emp_status="{{ $emp_status }}" data-popup="tooltip" data-placement="top"
                                            data-original-title="Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif
                                @endif

                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Team Leave Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $teamLeaveModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>

<!-- popup modal -->
<div id="updateStatus" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {!! Form::open([
                    'route' => 'leave.updateStatus',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'leaveId']) !!}
                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'leaveStatus', 'class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div id="statusMessage" class="row mt-3" style="display:none;">
                        <label class="col-form-label col-lg-3">Message :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('status_message', null, [
                                    'rows' => 3,
                                    'placeholder' => 'Write message..',
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn bg-success text-white">Save Changes</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#leaveStatus').select2();
        $('#leaveStatus').on('change', function() {
            var status = $(this).val();
            if (status == '2' || status == '4') {
                $('#statusMessage').show();
            } else {
                $('#statusMessage').hide();
            }
        });
        $('.updateStatus').on('click', function() {
            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var emp_status = $(this).attr('emp_status');

            let option_html;
            if (emp_status == 1 || emp_status == 6) {
                option_html = '<option value="1">Pending</option>';
            } else if (emp_status == 5) {
                option_html =
                    '<option value="1">Pending</option><option value="2">Recommended</option><option value="4">Rejected</option>';
            } else if (emp_status == 7) {
                option_html =
                    '<option value="2">Recommended</option><option value="3">Accepted</option><option value="4">Rejected</option>';
            } else if (emp_status == 2) {
                option_html = '<option value="2">Recommended</option>';
            } else if (emp_status == 3) {
                option_html = '';
            } else if (emp_status == 4) {
                option_html = '<option value="4">Rejected</option>';
            } else if (emp_status == 9) {
                option_html =
                    '<option value="1">Pending</option><option value="3">Accepted</option><option value="4">Rejected</option>';
            }

            $('#leaveId').val(id);
            $('#leaveStatus').val(status);
            $('#leaveStatus').html(option_html);
            $('#statusMessage').hide();

            // $('.updateLeaveStatusForm').find('#leaveId').val(id);
            $('#leaveStatus option[value=' + status + ']').prop('selected', true);
        });

    });
</script>
@endSection
