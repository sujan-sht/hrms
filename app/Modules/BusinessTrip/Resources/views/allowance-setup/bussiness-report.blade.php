@extends('admin::layout')

@section('title')
    Travel Report
@endsection

@section('breadcrum')
    <a class="breadcrumb-item active">Travel Requests </a>
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

    @include('businesstrip::business-trip.partial.filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Travel Requests Report</h6>
                All the Travel Requests Report will be listed below.
            </div>
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
                        <th>Grade</th>
                        <th>Designation</th>
                        <th>Unit</th>
                        <th>Type</th>
                        <th>Purpose</th>
                        <th>Number of days</th>
                        <th>Allowance Per Day</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($businessTrips)
                        @foreach ($businessTrips as $key => $businessTrip)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ @$businessTrip['empName'] }}</td>
                                <td>{{ @$businessTrip['level'] }}</td>
                                <td>{{ @$businessTrip['designation'] }}</td>
                                <td>{{ @$businessTrip['branch'] }}</td>
                                <td>{{ @$businessTrip['type'] }}</td>
                                <td>{{ @$businessTrip['purpose'] }}</td>
                                <td>{{ @$businessTrip['num_of_days'] }}</td>
                                <td>{{ @$businessTrip['allowance_per_day'] }}</td>
                                <td>{{ @$businessTrip['amount'] }}</td>
                                <td>
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('businessTrip.downloadPDF', $businessTrip['id']) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Download PDF">
                                        <i class="icon-download"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">No record found.</td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
        <div class="col-12">
            {{-- <span class="float-right pagination align-self-end mt-3">
                {{ $businessTrips->appends(request()->all())->links() }}
            </span> --}}
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
                            @php
                                unset($statusList[5]);
                            @endphp
                            {!! Form::select('status', $statusList, null, ['id' => 'tripStatus', 'class' => 'form-control']) !!}
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
