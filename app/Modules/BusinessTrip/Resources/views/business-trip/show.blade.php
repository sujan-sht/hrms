@extends('admin::layout')
@section('title') View Travel Request @endsection
@section('breadcrum')
{{-- <a href="{{ route('businessTrip.index') }}" class="breadcrumb-item">Travel Requests</a> --}}
<a class="breadcrumb-item active">View</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    {!! Form::open([
        'route' => 'businessTrip.updateClaimStatus',
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
    ]) !!}
        {!! Form::hidden('id', $businessTrip->id, []) !!}
        {{-- {!! Form::hidden('employee_id', $businessTrip->employee_id, []) !!} --}}
        {{-- {!! Form::hidden('kind', $businessTrip->kind, []) !!} --}}
        {{-- {!! Form::hidden('url', request()->url()) !!} --}}

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Travel Request Detail</legend>
                                <ul class="media-list">
                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Employee :</span>
                                        <div class="ml-2">{{ optional($businessTrip->employee)->getFullName() }}</div>
                                    </li>


                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">From Date :</span>
                                        <div class="ml-2">{{ setting('calendar_type') == 'BS' ? $businessTrip->from_date_nep :  $businessTrip->from_date}}</div>
                                    </li>


                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">To Date :</span>
                                        <div class="ml-2">{{ setting('calendar_type') == 'BS' ? $businessTrip->to_date_nep :  $businessTrip->to_date }}</div>
                                    </li>

                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Request Days :</span>
                                        <div class="ml-2">{{ $businessTrip->request_days }}</div>
                                    </li>

                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Remarks :</span>
                                        <div class="ml-2">{{ $businessTrip->remarks }}</div>
                                    </li>
                                    {{-- @if($menuRoles->assignedRoles('businessTrip.updateClaimStatus') &&
                                    $businessTrip->status == 3 && $businessTrip->claim_status == 1
                                    && $businessTrip->employee_id == auth()->user()->emp_id
                                    ) --}}
                                        {{-- <div class="col-md-12">
                                            <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Action Detail</legend>
                                            <div class="row">
                                                <label class="col-form-label col-lg-3">Status :</label>
                                                <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                                    <div class="input-group">
                                                        {!! Form::select('status', $statusList, null, ['id' => 'attendanceStatus', 'class' => 'form-control select-search']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3 rejectedRemarksDiv" style="display:none;">
                                                <label for="" class="col-form-label col-lg-3">Remarks: <span
                                                    class="text-danger">*</span></label>
                                                <div class="col-lg-9">
                                                    {!! Form::textarea('rejected_remarks', null, ['class' => 'form-control rejectRemarks']) !!}
                                                </div>
                                            </div>

                                            <div class="form-group row mt-3 forwadedRemarksDiv" style="display:none;">
                                                <label for="" class="col-form-label col-lg-3">Remarks: <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-lg-9">
                                                    {!! Form::textarea('forwaded_remarks', null, ['class' => 'form-control forwardRemarks']) !!}
                                                </div>
                                            </div> --}}
                                            {!! Form::hidden('claim_status', 2, []) !!}
                                            <div class="text-center mt-3">
                                                <button id="submitBtn" type="submit" class="btn btn-success btn-labeled btn-labeled-left">
                                                    <b><i class="icon-database-insert"></i></b> Submit
                                                </button>
                                            </div>
                                        {{-- </div> --}}
                                    {{-- @endif --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@endsection

{{-- @section('script')
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
        });
    </script>
@endsection --}}
