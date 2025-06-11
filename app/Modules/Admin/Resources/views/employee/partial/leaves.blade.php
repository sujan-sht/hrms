@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

<div class="card">

    <div class="card-header bg-transparent header-elements-inline">
        <h4 class="card-title font-weight-semibold">
            Employee Leave Request
        </h4>
        <div class="header-elements">
            <div class="list-icons ml-3">
                <a class="btn btn-success btn-sm rounded-pill" href="{{ route('leave.index') }}">
                    View More
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        <th>Leave Date</th>
                        <th>No. of Days</th>
                        <th>Leave Type</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($leaveModels->total() != 0)
                        @foreach ($leaveModels as $key => $leaveModel)
                            @inject('leave_flow', '\App\Modules\Leave\Repositories\LeaveRepository')
                            @php
                                $emp_leave_flow = $leave_flow->getEmployeeApprovalFlow($leaveModel->employee_id);
                                $emp_status = 8;
                                if ($emp_id == $leaveModel->employee_id) {
                                    $emp_status = 1; //pending
                                } else {
                                    if (!empty($emp_leave_flow)) {
                                        if (!empty($emp_leave_flow->first_approval_user_id) && $emp_leave_flow->first_approval_user_id > 0) {
                                            if ($leaveModel->status == 1 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                                $emp_status = 5; //rejected, forwarded
                                            } elseif ($leaveModel->status == 2 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                                $emp_status = 2; //forwarded
                                            } elseif ($leaveModel->status == 4 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                                $emp_status = 4; //rejected
                                            } elseif ($leaveModel->status == 2 && $emp_leave_flow->last_approval_user_id == $user_id) {
                                                $emp_status = 7; //rejected, accepted
                                            } elseif ($leaveModel->status == 1 && $emp_leave_flow->last_approval_user_id == $user_id) {
                                                $emp_status = 6; //pending
                                            } elseif ($leaveModel->status == 3 && $emp_leave_flow->first_approval_user_id == $user_id) {
                                                $emp_status = 3; //empty
                                            } elseif ($leaveModel->status != 1 && $leaveModel->status != 2 && $emp_leave_flow->last_approval_user_id == $user_id) {
                                                $emp_status = 3; //empty
                                            }
                                        } else {
                                            if ($emp_leave_flow->last_approval_user_id == $user_id) {
                                                $emp_status = 7; //rejected, accepted
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <tr>
                                <td width="5%">#{{ $leaveModels->firstItem() + $key }}</td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ optional($leaveModel->employeeModel)->getImage() }}"
                                                    class="rounded-circle" width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ optional($leaveModel->employeeModel)->getFullName() }}</div>
                                            <span
                                                class="text-muted">{{ optional($leaveModel->employeeModel)->official_email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $leaveModel->getDateRangeWithCount()['range'] }}</td>
                                <td>{{ $leaveModel->getDateRangeWithCount()['count'] }}</td>
                                <td>{{ optional($leaveModel->leaveTypeModel)->name }}</td>
                                <td>{!! $leaveModel->reason !!}</td>
                                <td class="leaveStatus">
                                    <span
                                        class="badge badge-{{ $leaveModel->getStatusWithColor()['color'] }}">{{ $leaveModel->getStatusWithColor()['status'] }}</span>
                                </td>
                                <td>
                                    @if($emp_id == $leaveModel->employee_id)
                                        <!-- do nothing -->
                                    @elseif (auth()->user()->user_type == 'super_admin' || ($menuRoles->assignedRoles('leave.updateStatus') && in_array($leaveModel->status, [1,2])))
                                        <a data-toggle="modal" data-target="#updateStatus" class="btn btn-outline-warning btn-icon updateStatus mx-1"
                                                data-id="{{ $leaveModel->id }}" data-status="{{ $leaveModel->status }}" emp_status="{{ $emp_status }}" 
                                                data-popup="tooltip" data-placement="top" data-original-title="Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Leave Type Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
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
                    'class' => 'form-horizontal updateLeaveStatusForm',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'leaveId']) !!}
                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'leaveStatus', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>
                    <div id="statusMessage" class="row mt-3" style="display:none;">
                        <label class="col-form-label col-lg-3">Message :</label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('status_message', null, ['rows' => 3, 'placeholder' => 'Write message..', 'class' => 'form-control']) !!}
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

<script>
    $(document).ready(function() {
        // initiate select2
        $('.select2').select2();

        $('#leaveStatus').on('change', function() {
            var status = $(this).val();
            if(status == '2' || status == '4') {
                $('#statusMessage').show();
            } else {
                $('#statusMessage').hide();
            }
        });

        $('.updateStatus').on('click', function(e) {
            e.preventDefault();
            $('#leaveStatus').prop('selected', false);
            var id = $(this).data('id');
            var leaveStatus = $(this).data('status');

            $('.updateLeaveStatusForm').find('#leaveId').val(id);
            $('#leaveStatus option[value=' + leaveStatus + ']').prop('selected', true);
            // $('#updateStatus').modal('show');
        });

    });
</script>
