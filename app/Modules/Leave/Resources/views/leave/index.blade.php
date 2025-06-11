@extends('admin::layout')
@section('title') Leave @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Leaves</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>

@include('leave::leave.partial.advance-filter', ['route' => route('leave.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Leaves</h6>
            All the Leaves Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <span class="bulkUpdateStatusDiv d-none">
                <a data-toggle="modal" data-target="#bulkUpdateStatus"
                    class="btn btn-outline-warning btn-icon bulkUpdateStatus mx-1"
                    data-popup="tooltip" data-placement="top"
                    data-original-title="Status">
                    <i class="icon-flag3"></i>
                </a>
            </span>
            
            @if ($menuRoles->assignedRoles('leave.calendar'))
                <a href="{{ route('leave.calendar') }}" class="btn btn-purple rounded-pill">View Calendar</a>
            @endif
            @if (Auth::user()->user_type != 'employee')
                <a href="{{ route('leave.exportLeaveHistory', request()->all()) }}"
                    class="btn btn-primary rounded-pill"><i class="icon-file-excel"></i> Export</a>
            @endif

            <a href="{{ route('leave.create') }}" class="btn btn-success rounded-pill">Apply Leave</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    @if ($menuRoles->assignedRoles('leave.updateStatusBulk'))
                        <th>
                            <div class="pretty p-default">
                                <input type="checkbox" id="checkAll" class="checkAll" style="width:25px;height:30px" />
                            </div>
                        </th>
                    @else
                        <th></th>
                    @endif
                    <th>S.N</th>
                    <th>Employee</th>
                    <th>Leave Date</th>
                    <th>Number of Days</th>
                    <th>Leave Type</th>
                    <th>Leave Category</th>
                    <th>Reason</th>
                    <th>Applied Date</th>
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
                                    if (
                                        !empty($emp_leave_flow->first_approval_user_id) &&
                                        $emp_leave_flow->first_approval_user_id > 0
                                    ) {
                                        if (
                                            $leaveModel->status == 1 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 5; //pending, rejected, forwarded
                                        } elseif (
                                            $leaveModel->status == 2 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 2; //forwarded
                                        } elseif (
                                            $leaveModel->status == 4 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 4; //rejected
                                        } elseif (
                                            $leaveModel->status == 2 &&
                                            $emp_leave_flow->last_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 7; //forwarded, rejected, accepted
                                        } elseif (
                                            $leaveModel->status == 1 &&
                                            $emp_leave_flow->last_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 6; //pending
                                        } elseif (
                                            $leaveModel->status == 3 &&
                                            $emp_leave_flow->first_approval_user_id == $user_id
                                        ) {
                                            $emp_status = 3; //empty
                                        } elseif (
                                            $leaveModel->status != 1 &&
                                            $leaveModel->status != 2 &&
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
                            @if (($leaveModel->status == 1 || $leaveModel->status == 2) && auth()->user()->emp_id != optional($leaveModel->employeeModel)->id && $menuRoles->assignedRoles('leave.updateStatusBulk'))
                                <td>{!! Form::checkbox('leave_ids[]', $leaveModel->id, false, ['class' => 'checkItem']) !!}</td>
                            @else
                                <td></td>
                            @endif
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
                            <td>
                                @if (isset($leaveModel->generated_by) && $leaveModel->generated_by == 11)
                                    {{ $leaveModel->generated_no_of_days }}
                                @else
                                    {{ $leaveModel->getDateRangeWithCount()['count'] }}
                                @endif
                            </td>
                            <td>{{ optional($leaveModel->leaveTypeModel)->name }}</td>
                            <td>{{ $leaveModel->getLeaveKind() }}
                                @if ($leaveModel->leave_kind == 1)
                                    ({{ $leaveModel->getHalfType() }})
                                @endif
                            </td>
                            <td>{!! $leaveModel->reason !!}</td>
                            <td>
                                @if (setting('calendar_type') == 'BS')
                                    {{ date_converter()->eng_to_nep_convert($leaveModel->created_at) }}
                                @else
                                    {{ date('M d, Y', strtotime($leaveModel->created_at)) }}
                                @endif
                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ $leaveModel->getStatusWithColor()['color'] }}">{{ $leaveModel->getStatusWithColor()['status'] }}</span>
                            </td>
                            <td class="d-flex">
                                <a class="btn btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('leave.show', $leaveModel->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if (Auth::user()->user_type != 'employee')
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('leave.downloadPDF', $leaveModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Download PDF">
                                        <i class="icon-download"></i>
                                    </a>
                                @endif
                                @if (isset($leaveModel->generated_by) && $leaveModel->generated_by == 11)
                                    {{-- do nothing --}}
                                @else
                                    @if ($emp_id == $leaveModel->employee_id)
                                        <!-- do nothing -->
                                    @elseif (auth()->user()->user_type == 'super_admin' || $menuRoles->assignedRoles('leave.updateStatus'))
                                        @if (in_array($leaveModel->status, [1, 2]))
                                            <a data-toggle="modal" data-target="#updateStatus"
                                                class="btn btn-outline-warning btn-icon updateStatus mx-1"
                                                data-id="{{ $leaveModel->id }}"
                                                data-status="{{ $leaveModel->status }}"
                                                emp_status="{{ $emp_status }}" data-popup="tooltip"
                                                data-placement="top" data-original-title="Status">
                                                <i class="icon-flag3"></i>
                                            </a>
                                        @endif
                                    @endif

                                    {{-- Cancel Attendance Request --}}
                                    @if (
                                        $menuRoles->assignedRoles('leave.cancelLeaveRequest') &&
                                            $leaveModel->status == 3 &&
                                            (auth()->user()->user_type == 'super_admin' ||
                                                auth()->user()->user_type == 'admin' ||
                                                auth()->user()->user_type == 'hr' ||
                                                auth()->user()->user_type == 'division_hr'))
                                        {!! Form::open([
                                            'route' => 'leave.cancelLeaveRequest',
                                            'method' => 'PUT',
                                            'class' => 'form-horizontal',
                                            'role' => 'form',
                                        ]) !!}
                                        {!! Form::hidden('id', $leaveModel->id, ['id' => 'leaveId']) !!}
                                        {!! Form::hidden('status', $value = 5) !!}
                                        {{-- {!! Form::hidden('url', request()->url()) !!} --}}

                                        <button class="btn btn-outline-warning btn-icon mr-1 confirmCancel"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Cancel"
                                            link="{{ route('leave.cancelLeaveRequest', $leaveModel->id) }}">
                                            <i class="icon-cancel-square"></i></button>

                                        {!! Form::close() !!}
                                    @elseif( $menuRoles->assignedRoles('leave.cancelLeaveRequest') && $leaveModel->status == 1 && auth()->user()->user_type == 'employee')
                                        {!! Form::open([
                                            'route' => 'leave.cancelLeaveRequest',
                                            'method' => 'PUT',
                                            'class' => 'form-horizontal',
                                            'role' => 'form',
                                        ]) !!}
                                        {!! Form::hidden('id', $leaveModel->id, ['id' => 'leaveId']) !!}
                                        {!! Form::hidden('status', $value = 5) !!}

                                        <button class="btn btn-outline-warning btn-icon mr-1 confirmCancel"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Cancel"
                                            link="{{ route('leave.cancelLeaveRequest', $leaveModel->id) }}">
                                            <i class="icon-cancel-square"></i></button>

                                        {!! Form::close() !!}
                                    @endif
                                    {{-- Cancel Attendance Request --}}

                                    @if (auth()->user()->user_type == 'super_admin' ||
                                            ($menuRoles->assignedRoles('leave.delete') && in_array($leaveModel->status, [4])))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                            link="{{ route('leave.delete', $leaveModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
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
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $leaveModels->appends(request()->all())->links() }}
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
                    'class' => 'form-horizontal updateLeaveStatusForm',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('id', null, ['id' => 'leaveId']) !!}
                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            @php
                                unset($statusList[5]);
                            @endphp
                            {!! Form::select('status', $statusList, null, ['id' => 'leaveStatus', 'class' => 'form-control select2']) !!}
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

<div id="bulkUpdateStatus" class="modal fade">
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
                    'route' => 'leave.updateStatusBulk',
                    'method' => 'POST',
                    'class' => 'form-horizontal',
                    'role' => 'form',
                ]) !!}
                {!! Form::hidden('leave_multiple_id[]', null, ['id' => 'leaveIds']) !!}

                <div class="form-group">
                    <div class="row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            @php
                                unset($statusList[1], $statusList[5]);
                            @endphp
                            {!! Form::select('status', $statusList, null, [
                                'id' => 'leaveStatus',
                                'class' => 'form-control select2',
                                'placeholder' => 'Select Status',
                            ]) !!}
                        </div>
                    </div>
                    {{-- <div id="statusMessage" class="row mt-3" style="display:none;">
                        <label class="col-form-label col-lg-3">Message : <span class="text-danger">*</span></label>
                        <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                            <div class="input-group">
                                {!! Form::textarea('status_message', null, [
                                    'rows' => 3,
                                    'placeholder' => 'Write message..',
                                    'class' => 'form-control makeRequired',
                                ]) !!}
                            </div>
                        </div>
                    </div> --}}
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
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script>
    $(document).ready(function() {
        // initiate select2
        // $('.select2').select2();

        $('#leaveStatus').on('change', function() {
            var status = $(this).val();
            if (status == '2' || status == '4') {
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
        });

        //cancel request
        $('.confirmCancel').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'cancelled!',
                        text: 'Leave request has been cancelled.',
                        icon: 'success',
                        showCancelButton: false,
                        showConfirmButton: false,
                    });
                    $(this).closest('form').submit();
                }
            });
        });
        //

        //multiple leaves update status
        $('#checkAll').checkAll();
        $('.checkItem').on('click', function() {
            var anyChecked = $('.checkItem:checked').length > 0;
            $('.bulkUpdateStatusDiv').toggleClass('d-none', !anyChecked);
        });

        $('.checkAll').on('click', function() {
            var anyChecked = $('.checkAll:checked').length > 0;
            $('.bulkUpdateStatusDiv').toggleClass('d-none', !anyChecked);
        });

        $(document).on("click", '.bulkUpdateStatus', function() {
            // $("#bulkUpdateStatus").html('');
            var leave_ids = $("input[name='leave_ids[]']:checked").map(function() {
                return $(this).val();
            }).get();
            var leave_ids_string = JSON.stringify(leave_ids);

            $('#leaveIds').val(leave_ids_string)
            $('#bulkUpdateStatus').modal('show')
        });
        //
        
        // $('.updateStatus').on('click', function() {
        //     var id = $(this).attr('data-id');
        //     var status = $(this).attr('data-status');
        //     var emp_status = $(this).attr('emp_status');

        //     let option_html;
        //     if (emp_status == 1 || emp_status == 6) {
        //         option_html = '<option value="1">Pending</option>';
        //     } else if (emp_status == 5) {
        //         option_html = '<option value="1">Pending</option><option value="4">Rejected</option><option value="2">Forwarded</option>';
        //     } else if (emp_status == 7) {
        //         option_html = '<option value="2">Forwarded</option><option value="4">Rejected</option><option value="3">Accepted</option>';
        //     } else if (emp_status == 2) {
        //         option_html = '<option value="2">Forwarded</option>';
        //     } else if (emp_status == 3) {
        //         option_html = '';
        //     } else if (emp_status == 4) {
        //         option_html = '<option value="4">Rejected</option>';
        //     } else if (emp_status == 9) {
        //         option_html = '<option value="3">Accepted</option><option value="4">Rejected</option>';
        //     } else {
        //         option_html = '<option value="1">Pending</option><option value="2">Forwarded</option><option value="3">Accepted</option><option value="4">Rejected</option>';
        //     }

        //     $('#leaveId').val(id);
        //     $('#leaveStatus').val(status);
        //     $('#leaveStatus').html(option_html);
        //     $('#statusMessage').hide();
        // });
    });
</script>
@endSection
