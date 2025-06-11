@extends('admin::layout')

@section('title')
    Overtime Request List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Overtime Requests </a>
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
            'Claimed' => 'success',
        ];
    @endphp

    @include('overtimerequest::overtime-request.partial.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Overtime Requests</h6>
                All the Overtime Requests Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('overtimeRequest.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('overtimeRequest.create') }}" class="btn btn-success rounded-pill">Create</a>
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
                        @if (Auth::user()->user_type != 'employee')
                            <th>Employee</th>
                        @endif
                        {{-- <th>Title</th> --}}
                        <th>OT Date</th>
                        <th>Time (In minutes)</th>
                        {{-- <th>Requested Day(s)</th> --}}
                        <th>Applicant remarks</th>
                        <th>Management Remarks</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Claim Status</th>
                        <th class="text-center">OT Claimed (mins)</th>
                        <th>Requested Date</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($overtimeRequests->total() > 0)
                        @foreach ($overtimeRequests as $key => $overtimeRequest)
                            <tr>
                                <td>
                                    #{{ $overtimeRequests->firstItem() + $key }}
                                </td>
                                @if (Auth::user()->user_type != 'employee')
                                    <td>
                                        <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ optional($overtimeRequest->employee)->getImage() }}"
                                                        class="rounded-circle" width="40" height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ optional($overtimeRequest->employee)->getFullName() }}</div>
                                                <span
                                                    class="text-muted">{{ optional($overtimeRequest->employee)->official_email ?? optional($overtimeRequest->employee)->personal_email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                {{-- <td>{{ $overtimeRequest->title }}</td> --}}
                                @if (setting('calendar_type') == 'BS')
                                    <td>{{ $overtimeRequest->nepali_date }}</td>
                                @else
                                    <td>{{ $overtimeRequest->date }}</td>
                                @endif

                                <td>{{ $overtimeRequest->ot_time }}</td>


                                {{-- <td>{{ $overtimeRequest->request_days }}</td> --}}
                                <td>{{ $overtimeRequest->remarks }}</td>
                                <td>{{ $overtimeRequest->reject_note }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge badge-{{ $colors[$overtimeRequest->getStatus() ?? 'Pending'] }}">{{ $overtimeRequest->getStatus() ?? 'Pending' }}</span>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge badge-{{ $colors[$overtimeRequest->getClaimStatus() ?? 'Pending'] }}">{{ $overtimeRequest->getClaimStatus() ?? 'Pending' }}</span>
                                </td>
                                <td>{{ $overtimeRequest->claim_status == 2 ? $overtimeRequest->ot_time : '-' }}</td>
                                @if (setting('calendar_type') == 'BS')
                                    <td>{{ date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($overtimeRequest->created_at))) }}
                                    </td>
                                @else
                                    <td>{{ date('Y-m-d', strtotime($overtimeRequest->created_at)) }}</td>
                                @endif
                                <td class="text-center d-flex">
                                    @if ($menuRoles->assignedRoles('overtimeRequest.viewDetail'))
                                        <a class="btn btn-outline-secondary btn-icon mx-1"
                                            href="{{ route('overtimeRequest.viewDetail', $overtimeRequest->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="View">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif

                                    @if (
                                        $menuRoles->assignedRoles('overtimeRequest.updateClaimStatus') &&
                                            $overtimeRequest->status == 3 &&
                                            $overtimeRequest->claim_status == 1 &&
                                            $overtimeRequest->employee_id == auth()->user()->emp_id)
                                        <a class="btn btn-outline-info btn-icon mx-1"
                                            href="{{ route('overtimeRequest.claim', $overtimeRequest->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Claim">
                                            <i class="icon-stamp"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('overtimeRequest.edit') && $overtimeRequest->status == 1)
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('overtimeRequest.edit', $overtimeRequest->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if (
                                        $menuRoles->assignedRoles('overtimeRequest.updateStatus') &&
                                            ($overtimeRequest->status == 1 || $overtimeRequest->status == 2) &&
                                            $overtimeRequest->employee_id != Auth::user()->emp_id)
                                        <a class="btn btn-outline-warning btn-icon mr-1 updateStatusClick"
                                            data-toggle="modal" data-target="#updateStatus"
                                            link="{{ route('overtimeRequest.updateStatus', $overtimeRequest->id) }}"
                                            data-id="{{ $overtimeRequest->id }}"
                                            data-value="{{ $overtimeRequest->status }}" data-placement="bottom"
                                            data-popup="tooltip" data-employee-id="{{ $overtimeRequest->employee_id }}"
                                            data-original-title="Update Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif
                                    {{-- @if ($menuRoles->assignedRoles('overtimeRequest.downloadPDF'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('overtimeRequest.downloadPDF', $overtimeRequest->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Download PDF">
                                            <i class="icon-download"></i>
                                        </a>
                                    @endif --}}
                                    @if (
                                        $menuRoles->assignedRoles('overtimeRequest.cancelRequest') &&
                                            $overtimeRequest->status == 1 &&
                                            $overtimeRequest->employee_id == Auth::user()->emp_id)
                                        {!! Form::open([
                                            'route' => 'overtimeRequest.cancelRequest',
                                            'method' => 'PUT',
                                            'class' => 'form-horizontal',
                                            'role' => 'form',
                                        ]) !!}
                                        {!! Form::hidden('id', $overtimeRequest->id, ['id' => 'overtimeRequestId']) !!}
                                        {!! Form::hidden('status', $value = 5) !!}

                                        <button class="btn btn-outline-warning btn-icon mr-1 confirmCancel"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Cancel"
                                            link="{{ route('overtimeRequest.cancelRequest', $overtimeRequest->id) }}">
                                            <i class="icon-cancel-square"></i></button>

                                        {!! Form::close() !!}
                                    @endif

                                    @if (
                                        $menuRoles->assignedRoles('overtimeRequest.delete') &&
                                            ($overtimeRequest->status == 1 || $overtimeRequest->status == 4))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('overtimeRequest.delete', $overtimeRequest->id) }}">
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
                {{ $overtimeRequests->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

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
                        'route' => 'overtimeRequest.updateStatus',
                        'method' => 'PUT',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'tripId']) !!}
                    {!! Form::hidden('employee_id', null, ['class' => 'employeeId']) !!}
                    <input type="hidden" name="id" class="updateid">

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            @php
                                unset($statusList[5]);
                            @endphp
                            {!! Form::select('status', $statusList, null, ['id' => 'tripStatus', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group row remarks" style="display: none;">
                        <label class="col-form-label col-lg-3">Remarks:</label>
                        <div class="col-lg-12">

                            {!! Form::textarea('status_update_remarks', null, ['class' => 'form-control', 'placeholder' => 'Enter Remarks']) !!}
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
    <script>
        $(document).ready(function() {
            $('.updateStatusClick').on('click', function() {
                let id = $(this).data('id');
                $('.updateid').val(id);
                $('.employeeId').val($(this).data('employee-id'));
                $('#tripStatus').val($(this).attr('data-value'));
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
                            text: 'Overtime Request has been cancelled.',
                            icon: 'success',
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        $(this).closest('form').submit();
                    }
                });
            });


            $('#tripStatus').on('change', function() {
                var status = $(this).val()
                if (status != 1) {
                    $('.remarks').css('display', 'block')
                } else {
                    $('.remarks').css('display', 'none')
                }
            })

            //
        });
    </script>
@endsection
