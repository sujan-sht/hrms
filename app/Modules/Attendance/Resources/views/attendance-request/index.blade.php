@extends('admin::layout')

@section('title')
    Attendance Request List
@endsection

@section('breadcrum')
    <a href="{{ route('shiftGroup.index') }}" class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">Attendance Requests </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @php
        $colors = [
            'Pending' => 'secondary',
            'Forwarded' => 'primary',
            'Approved' => 'success',
            'Rejected' => 'danger',
            'Cancelled' => 'warning',
            'Recommended' => 'info',
        ];
    @endphp

    @include('attendance::attendance-request.partial.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Attendance Requests</h6>
                All the Attendance Requests Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1 mr-2">
                <span class="bulkUpdateStatusDiv d-none">
                    <a data-toggle="modal" data-target="#bulkUpdateStatus"
                        class="btn btn-outline-warning btn-icon bulkUpdateStatus mx-1" data-popup="tooltip"
                        data-placement="top" data-original-title="Status">
                        <i class="icon-flag3"></i>
                    </a>
                </span>

                @if ($menuRoles->assignedRoles('attendanceRequest.create'))
                    <a href="{{ route('attendanceRequest.create') }}" class="btn btn-success"><i class="icon-user-plus"></i>
                        Request
                        Attendance</a>
                @endif
            </div>
        </div>
    </div>
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        @if ($menuRoles->assignedRoles('attendanceRequest.updateStatusBulk'))
                            <th>
                                <div class="pretty p-default">
                                    <input type="checkbox" id="checkAll" class="checkAll" style="width:25px;height:30px" />
                                </div>
                            </th>
                        @else
                            <th></th>
                        @endif
                        <th>S.N</th>
                        @if (Auth::user()->user_type != 'employee')
                            <th>Employee Name</th>
                        @endif
                        <th>Requested Date</th>
                        <th>Number of Days</th>
                        <th>Type</th>
                        <th>Kind</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Remarks</th>
                        <th class="text-center">Status</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($requests->total() > 0)
                        @foreach ($requests as $key => $request)
                            <tr>
                                @if (
                                    ($request->status == 1 || $request->status == 2) &&
                                        auth()->user()->emp_id != optional($request->employee)->id &&
                                        $menuRoles->assignedRoles('attendanceRequest.updateStatusBulk'))
                                    <td>{!! Form::checkbox('request_ids[]', $request->id, false, ['class' => 'checkItem']) !!}</td>
                                @else
                                    <td></td>
                                @endif
                                <td>
                                    #{{ $requests->firstItem() + $key }}
                                </td>
                                @if (Auth::user()->user_type != 'employee')
                                    <td>
                                        <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ optional($request->employee)->getImage() }}"
                                                        class="rounded-circle" width="40" height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ optional($request->employee)->getFullName() }}</div>
                                                <span
                                                    class="text-muted">{{ optional($request->employee)->official_email ?? optional($request->employee)->personal_email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                {{-- <td>{{ $request->getDateRangeWithCount()['range'] }}</td>
                                <td>{{ $request->getDateRangeWithCount()['count'] }}</td> --}}

                                <td>{{ setting('calendar_type') == 'BS' ? $request->nepali_date : date('M d, Y', strtotime($request->date)) }}
                                </td>
                                <td>{{ isset($request->kind) && ($request->kind == 1 || $request->kind == 2) ? 0.5 : 1 }}
                                </td>

                                <td>
                                    {{ $request->getType() }}
                                </td>
                                <td>
                                    {{ $request->kind == null ? '---' : $request->getKind() }}
                                </td>
                                <td>
                                    {{ $request->time ? date('h:i A', strtotime($request->time)) : '---' }}
                                </td>
                                <td>
                                    {{ Str::limit($request->detail, 50) }}
                                </td>

                                <td>
                                    {{ Str::limit($request->rejected_remarks, 50) ?? '' }}
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge badge-{{ $colors[$request->getStatus() ?? 'Pending'] }}">{{ $request->getStatus() ?? 'Pending' }}</span>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-outline-secondary btn-icon mx-1"
                                        href="{{ route('attendanceRequest.show', $request->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="View">
                                        <i class="icon-eye"></i>
                                    </a>
                                    @if (
                                        $menuRoles->assignedRoles('attendanceRequest.updateStatus') &&
                                            ($request->status == 1 || $request->status == 2) &&
                                            $request->employee_id != Auth::user()->emp_id)
                                        <a class="btn btn-outline-warning btn-icon mr-1 updateStatusClick"
                                            data-toggle="modal" data-target="#updateStatus"
                                            link="{{ route('attendanceRequest.updateStatus', $request->id) }}"
                                            data-id="{{ $request->id }}" data-value="{{ $request->status }}"
                                            data-kind="{{ $request->kind }}" data-placement="bottom" data-popup="tooltip"
                                            data-employee-id="{{ optional($request->employee)->id }}"
                                            data-original-title="Update Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif

                                    {{-- @if ($menuRoles->assignedRoles('attendanceRequest.edit') && $request->getStatus() == 'Pending')
                                        <a class="btn btn-outline-primary btn-icon mr-1" data-popup="tooltip"
                                            data-placement="bottom"
                                            href="{{ route('attendanceRequest.edit', $request->id) }}" class="action-icon"
                                            title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif --}}

                                    {{-- Cancel Attendance Request --}}
                                    @if (
                                        $menuRoles->assignedRoles('attendanceRequest.cancelAttendanceRequest') &&
                                            $request->getStatus() == 'Pending' &&
                                            $request->employee_id == Auth::user()->emp_id)
                                        {!! Form::open([
                                            'route' => 'attendanceRequest.cancelAttendanceRequest',
                                            'method' => 'PUT',
                                            'class' => 'form-horizontal',
                                            'role' => 'form',
                                        ]) !!}
                                        {!! Form::hidden('id', $request->id, ['id' => 'attendanceId']) !!}
                                        {!! Form::hidden('status', $value = 5) !!}
                                        {!! Form::hidden('url', request()->url()) !!}

                                        <button class="btn btn-outline-warning btn-icon mr-1 confirmCancel"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Cancel"
                                            link="{{ route('attendanceRequest.cancelAttendanceRequest', $request->id) }}">
                                            <i class="icon-cancel-square"></i></button>

                                        {!! Form::close() !!}
                                    @endif
                                    {{-- Cancel Attendance Request --}}

                                    @if (
                                        $menuRoles->assignedRoles('attendanceRequest.delete') &&
                                            ($request->status == 1 || $request->status == 4 || $request->status == 5))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('attendanceRequest.delete', $request->id) }}">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No record found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $requests->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
    <!-- update status popup modal -->
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
                        'route' => 'attendanceRequest.updateStatus',
                        'method' => 'PUT',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'attendanceId']) !!}
                    {!! Form::hidden('employee_id', null, ['class' => 'employee_id']) !!}
                    {!! Form::hidden('kind', null, ['class' => 'kind']) !!}
                    {!! Form::hidden('url', request()->url()) !!}

                    <input type="hidden" name="id" class="updateid">

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', $statusList, null, ['id' => 'attendanceStatus', 'class' => 'form-control select2']) !!}
                        </div>
                    </div>

                    <div class="form-group row rejectedRemarksDiv" style="display:none;">
                        <label for="" class="col-form-label col-lg-3">Remarks: <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {!! Form::textarea('rejected_remarks', null, ['class' => 'form-control rejectRemarks']) !!}

                        </div>
                    </div>

                    <div class="form-group row forwadedRemarksDiv" style="display:none;">
                        <label for="" class="col-form-label col-lg-3">Remarks: <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-9">
                            {!! Form::textarea('forwaded_remarks', null, ['class' => 'form-control forwardRemarks']) !!}

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
    <!-- update status popup modal -->

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
                        'route' => 'attendanceRequest.updateStatusBulk',
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('request_multiple_id[]', null, ['id' => 'requestIds']) !!}

                    <div class="form-group">
                        <div class="row">
                            <label class="col-form-label col-lg-3">Status :</label>
                            <div class="col-lg-9">
                                @php
                                    unset($statusList[1], $statusList[5]);
                                @endphp
                                {!! Form::select('status', $statusList, null, [
                                    'id' => 'requestStatus',
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
    <script>
        $(document).ready(function() {
            $('.updateStatusClick').on('click', function() {
                let id = $(this).data('id');
                $('.updateid').val(id);
                $('.employee_id').val($(this).data('employee-id'));
                $('.kind').val($(this).data('kind'));
                $('#attendanceStatus').val($(this).attr('data-value'));
            });

            // show/hide remarks section
            $('#attendanceStatus').on('change', function() {
                var status = $(this).val();
                $('.rejectedRemarksDiv').hide();
                $('.rejectRemarks').prop('required', false);

                $('.forwadedRemarksDiv').hide();
                $('.forwardRemarks').prop('required', false);


                if (status == 4) {
                    $('.rejectedRemarksDiv').show();
                    $('.rejectRemarks').prop('required', true);
                } else if (status == 2) {
                    $('.forwadedRemarksDiv').show();
                    $('.forwardRemarks').prop('required', true);
                }

            })
            //

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
                            text: 'Attendance request has been cancelled.',
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
                var request_ids = $("input[name='request_ids[]']:checked").map(function() {
                    return $(this).val();
                }).get();
                var request_ids_string = JSON.stringify(request_ids);

                $('#requestIds').val(request_ids_string)
                $('#bulkUpdateStatus').modal('show')
            });
            //

        });
    </script>

    {{-- <!-- Sweet Alerts js -->
    <script src="{{ asset('admin/assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/js/extra_sweetalert.js') }}"></script>
    <!-- Sweet alert init js-->
    <script src="{{ asset('admin/assets/js/pages/sweet-alerts.init.js') }}"></script> --}}
@endsection
