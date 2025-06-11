@extends('admin::layout')

@section('title')
    Team Travel Request List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Team Travel Requests</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @php
        $colors = ['Pending' => 'secondary', 'Forwarded' => 'primary', 'Approved' => 'success', 'Rejected' => 'danger', 'Cancelled' => 'warning', 'Claimed' => 'success'];
    @endphp

    @include('businesstrip::business-trip.partial.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Team Travel Requests</h6>
                All the Team Travel Requests Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('businessTrip.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('businessTrip.create') }}" class="btn btn-success rounded-pill">Create</a>
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
                        <th>Title</th>
                        <th>Type</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Requested Day(s)</th>
                        <th>Applicant remarks</th>
                        <th>Management Remarks</th>
                        {{-- <th>Eligible Amount</th> --}}
                        <th class="text-center">Status</th>
                        <th class="text-center">Claim Status</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($businessTrips->total() > 0)
                        @foreach ($businessTrips as $key => $businessTrip)
                            <tr>
                                <td>
                                    #{{ $businessTrips->firstItem() + $key }}
                                </td>
                                @if (Auth::user()->user_type != 'employee')
                                    <td>
                                        <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ optional($businessTrip->employee)->getImage() }}"
                                                        class="rounded-circle" width="40" height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ optional($businessTrip->employee)->getFullName() }}</div>
                                                <span
                                                    class="text-muted">{{ optional($businessTrip->employee)->official_email ?? optional($businessTrip->employee)->personal_email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                <td>{{ $businessTrip->title }}</td>

                                <td>{{ optional($businessTrip->type)->title }}</td>

                                @if (setting('calendar_type') == 'BS')
                                    <td>{{ $businessTrip->from_date_nep }}</td>
                                    <td>{{ $businessTrip->to_date_nep }}</td>
                                @else
                                    <td>{{ $businessTrip->from_date }}</td>
                                    <td>{{ $businessTrip->to_date }}</td>
                                @endif

                                <td>{{ $businessTrip->request_days }}</td>
                                <td>{{ $businessTrip->remarks }}</td>
                                <td>{{ $businessTrip->reject_note }}</td>

                                {{-- <td>Rs. {{ $businessTrip->eligible_amount }}</td> --}}

                                <td class="text-center">
                                    <span class="badge badge-{{ $colors[$businessTrip->getStatus() ?? 'Pending'] }}">{{ $businessTrip->getStatus() ?? 'Pending' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-{{ $colors[$businessTrip->getClaimStatus() ?? 'Pending'] }}">{{ $businessTrip->getClaimStatus() ?? 'Pending' }}</span>
                                    {{-- @if ($businessTrip->claim_status == 2 && $businessTrip->employee_id != optional(auth()->user()->userEmployer)->id) --}}
                                    @if ($businessTrip->claim_status == 2)
                                        <span>Rs. {{ $businessTrip->eligible_amount ? $businessTrip->eligible_amount : 0 }}</span>
                                    @endif
                                </td>
                                <td class="text-center d-flex">

                                    {{-- <a class="btn btn-outline-secondary btn-icon mx-1"
                                    href="{{ route('businessTrip.show', $businessTrip->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                    </a> --}}

                                    {{-- @if($menuRoles->assignedRoles('businessTrip.edit') && $businessTrip->status == 1)
                                        <a class="btn btn-outline-primary btn-icon mx-1" href="{{ route('businessTrip.edit', $businessTrip->id) }}" data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif --}}

                                    {{-- @if (
                                        $menuRoles->assignedRoles('businessTrip.updateStatus') &&
                                            ($businessTrip->status == 1 || $businessTrip->status == 2) &&
                                            $businessTrip->employee_id != Auth::user()->emp_id)
                                        <a class="btn btn-outline-warning btn-icon mr-1 updateStatusClick"
                                            data-toggle="modal" data-target="#updateStatus"
                                            link="{{ route('businessTrip.updateStatus', $businessTrip->id) }}"
                                            data-id="{{ $businessTrip->id }}" data-value="{{ $businessTrip->status }}"
                                            data-placement="bottom" data-popup="tooltip"
                                            data-employee-id="{{ $businessTrip->employee_id }}"
                                            data-original-title="Update Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif --}}

                                    @if (
                                        $menuRoles->assignedRoles('businessTrip.updateStatus') &&
                                            ($businessTrip->status == 1 || $businessTrip->status == 2) &&
                                            $businessTrip->employee_id != Auth::user()->emp_id)
                                        <a class="btn btn-outline-warning btn-icon mr-1 updateStatusClick" data-toggle="modal"
                                            data-target="#updateStatus"
                                            link="{{ route('businessTrip.updateStatus', $businessTrip->id) }}"
                                            data-id="{{ $businessTrip->id }}" data-value="{{ $businessTrip->status }}"
                                            data-statusList="{{ $businessTrip->status_list }}" data-placement="bottom"
                                            data-popup="tooltip" data-original-title="Update Status">
                                            <i class="icon-flag3"></i>
                                        </a>
                                    @endif

                                    {{-- Cancel Travel Request --}}
                                    {{-- @if (
                                        $menuRoles->assignedRoles('businessTrip.cancelRequests') &&
                                        $businessTrip->status == 1 &&
                                        $businessTrip->employee_id == Auth::user()->emp_id)
                                        {!! Form::open([
                                            'route' => 'businessTrip.cancelRequests',
                                            'method' => 'PUT',
                                            'class' => 'form-horizontal',
                                            'role' => 'form',
                                        ]) !!}
                                        {!! Form::hidden('id', $businessTrip->id, ['id' => 'businessTripId']) !!}
                                        {!! Form::hidden('status', $value = 5) !!}

                                        <button class="btn btn-outline-warning btn-icon mr-1 confirmCancel"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Cancel"
                                            link="{{ route('businessTrip.cancelRequests', $request->id) }}">
                                            <i class="icon-cancel-square"></i></button>

                                        {!! Form::close() !!}
                                    @endif --}}
                                    {{-- Cancel Travel Request --}}

                                    {{-- @if (
                                        $menuRoles->assignedRoles('businessTrip.delete') &&
                                            ($businessTrip->status == 1 || $businessTrip->status == 4))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('businessTrip.delete', $businessTrip->id) }}">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif --}}
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
                {{ $businessTrips->appends(request()->all())->links() }}
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
                        'route' => 'businessTrip.updateStatus',
                        'method' => 'PUT',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    {!! Form::hidden('id', null, ['id' => 'tripId']) !!}
                    <input type="hidden" name="id" class="updateid">

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {!! Form::select('status', [], null, ['id' => 'tripStatus', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group row rejectedRemarks" style="display: none;">
                        <label class="col-form-label col-lg-3">Reject Note :</label>
                        <div class="col-lg-9">

                            {!! Form::textarea('reject_note', null, ['class' => 'form-control', 'placeholder' => 'Enter Reject Note']) !!}
                        </div>
                    </div>

                    {{-- <div class="form-group row rejectedRemarksDiv" style="display:none;">
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
                    </div> --}}

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
            $(document).on('click', '.updateStatusClick', function() {
                let id = $(this).data('id');
                $('.updateid').val(id);
                $('#tripStatus').empty();

                statusList = $(this).data('statuslist');

                let values = Object.values(statusList)
                let keys = Object.keys(statusList)

                let option = ''
                values.forEach((element, index) => {
                    return option += `<option value="${keys[index]}">${element}</option>`
                });
                $('#tripStatus').append(option)
                $('#tripStatus').val($(this).attr('data-value'));
                $("#tripStatus option:selected").attr('disabled', 'disabled')
            })

            $('#tripStatus').on('change', function() {
                $('.rejectedRemarks').toggle($(this).val() == 4);
            })

                //cancel request
                // $('.confirmCancel').on('click', function(e) {
                //     e.preventDefault();
                //     Swal.fire({
                //         title: 'Are you sure?',
                //         text: "You won't be able to revert this!",
                //         icon: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#3085d6',
                //         cancelButtonColor: '#d33',
                //         confirmButtonText: 'Yes, cancel it!'
                //     }).then((result) => {
                //         if (result.isConfirmed) {
                //             Swal.fire({
                //                 title: 'cancelled!',
                //                 text: 'Business trip request has been cancelled.',
                //                 icon: 'success',
                //                 showCancelButton: false,
                //                 showConfirmButton: false,
                //             });
                //             $(this).closest('form').submit();
                //         }
                //     });
                // });
                //

            // $('#attendanceStatus').on('change', function() {
            //     var status = $(this).val();
            //     $('.rejectedRemarksDiv').hide();
            //     $('.rejectRemarks').prop('required', false);

            //     $('.forwadedRemarksDiv').hide();
            //     $('.forwardRemarks').prop('required', false);


            //     if (status == 4) {
            //         $('.rejectedRemarksDiv').show();
            //         $('.rejectRemarks').prop('required', true);
            //     } else if (status == 2) {
            //         $('.forwadedRemarksDiv').show();
            //         $('.forwardRemarks').prop('required', true);
            //     }

            // })
        });
    </script>
@endsection
