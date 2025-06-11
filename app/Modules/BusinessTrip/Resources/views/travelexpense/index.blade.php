@extends('admin::layout')

@section('title')
    Travel expense List
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Travel expenses </a>
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

    {{-- @include('businesstrip::business-trip.partial.filter') --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of TravelExpenses</h6>
                All the TravelExpenses Information will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('travelexpense.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('travelexpense.create') }}" class="btn btn-success rounded-pill">Create</a>
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
                        <th>Sub-Function</th>
                        <th>Designation</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Total Amount</th>
                        <th>Expenses Type</th>
                        <th>Departure</th>
                        <th>Destination</th>
                        <th style="width: 12%;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($lists->total() > 0)
                        @foreach ($lists as $key => $list)
                            <tr>
                                <td>
                                    #{{ $lists->firstItem() + $key }}
                                </td>

                                <td>
                                    {{-- <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ optional($list->employee)->getImage() }}"
                                                        class="rounded-circle" width="40" height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ optional($list->employee)->getFullName() }}</div>
                                                <span
                                                    class="text-muted">{{ optional($list->employee)->official_email ?? optional($list->employee)->personal_email }}
                                                </span>
                                                 <span
                                                    class="text-muted">{{$list->designation }}
                                                </span>
                                            </div>
                                        </div> --}}
                                    {{ $list->employee->getFullName() ?? '' }}
                                </td>

                                <td>{{ $list->department }}</td>
                                <td>{{ $list->designation }}</td>
                                {{-- <td>{{ $travelTypes[$list->type_id ?? ''] }}</td> --}}

                                @if (setting('calendar_type') == 'BS')
                                    <td>{{ $list->from_date_nep }}</td>
                                    <td>{{ $list->to_date_nep }}</td>
                                @else
                                    <td>{{ $list->from_date }}</td>
                                    <td>{{ $list->to_date }}</td>
                                @endif

                                <td>{{ $list->total_amount }}</td>
                                <td>{{ $travelTypes[$list->expenses_type] }}</td>
                                <td>{{ $list->departure }}</td>
                                <td>{{ $list->destination }}</td>
                                <td class="text-center d-flex">


                                    @if ($menuRoles->assignedRoles('travelexpense.destroy'))
                                        <a class="btn btn-outline-danger btn-icon mr-1 confirmDelete"
                                            data-placement="bottom" data-popup="tooltip" data-original-title="Delete"
                                            link="{{ route('travelexpense.destroy', $list->id) }}">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('travelexpense.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('travelexpense.edit', $list->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
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
                {{ $lists->appends(request()->all())->links() }}
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
                    {!! Form::hidden('employee_id', null, ['class' => 'employeeId']) !!}
                    <input type="hidden" name="id" class="updateid">

                    <div class="form-group row">
                        <label class="col-form-label col-lg-3">Status :</label>
                        <div class="col-lg-9">
                            {{-- @php
                                unset($statusList[5]);
                            @endphp --}}
                            {{-- {!! Form::select('status', $statusList, null, ['id' => 'tripStatus', 'class' => 'form-control']) !!} --}}
                        </div>
                    </div>

                    <div class="form-group row rejectedRemarks" style="display: none;">
                        <label class="col-form-label col-lg-3">Reject Note :</label>
                        <div class="col-lg-9">

                            {!! Form::textarea('reject_note', null, ['class' => 'form-control', 'placeholder' => 'Enter Reject Note']) !!}
                            {{-- {!! Form::text('reject_note', null, ['class' => 'form-control', 'placeholder' => 'Enter Reject Note']) !!} --}}
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
                            text: 'Travel Request has been cancelled.',
                            icon: 'success',
                            showCancelButton: false,
                            showConfirmButton: false,
                        });
                        $(this).closest('form').submit();
                    }
                });
            });


            $('#tripStatus').on('change', function() {
                $('.rejectedRemarks').toggle($(this).val() == 4);
            })

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
