@extends('admin::layout')
@section('title')
    View Attendance Request
@endsection
@section('breadcrum')
    <a href="{{ route('attendanceRequest.index') }}" class="breadcrumb-item">Attendance Requests</a>
    <a class="breadcrumb-item active">View</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    {!! Form::open([
        'route' => 'attendanceRequest.updateStatus',
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
    ]) !!}
    {!! Form::hidden('id', $attendanceRequest->id, []) !!}
    {!! Form::hidden('employee_id', $attendanceRequest->employee_id, []) !!}
    {!! Form::hidden('kind', $attendanceRequest->kind, []) !!}
    {!! Form::hidden('url', request()->url()) !!}


    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <legend class="text-uppercase font-size-sm font-weight-bold">Attendance Request Detail</legend>
                            <ul class="media-list">
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Applied Date :</span>
                                    @php
                                        $createdDate =
                                            setting('calendar_type') == 'BS'
                                                ? date_converter()->eng_to_nep_convert(
                                                    date('Y-m-d', strtotime($attendanceRequest->created_at)),
                                                )
                                                : date('M d, Y', strtotime($attendanceRequest->created_at));

                                        if (setting('calendar_type') == 'BS') {
                                            $forwardedDate =
                                                date_converter()->eng_to_nep_convert(
                                                    $attendanceRequest->forwarded_date,
                                                ) .
                                                ' ' .
                                                Carbon\Carbon::parse($attendanceRequest->forwarded_date)->format(
                                                    'h:i A',
                                                );
                                            $approvedDate =
                                                date_converter()->eng_to_nep_convert(
                                                    $attendanceRequest->approved_date,
                                                ) .
                                                ' ' .
                                                Carbon\Carbon::parse($attendanceRequest->approved_date)->format(
                                                    'h:i A',
                                                );
                                            $rejectedDate =
                                                date_converter()->eng_to_nep_convert(
                                                    $attendanceRequest->rejected_date,
                                                ) .
                                                ' ' .
                                                Carbon\Carbon::parse($attendanceRequest->rejected_date)->format(
                                                    'h:i A',
                                                );
                                            $cancelledDate =
                                                date_converter()->eng_to_nep_convert(
                                                    $attendanceRequest->cancelled_date,
                                                ) .
                                                ' ' .
                                                Carbon\Carbon::parse($attendanceRequest->cancelled_date)->format(
                                                    'h:i A',
                                                );
                                        } else {
                                            $forwardedDate = Carbon\Carbon::parse(
                                                $attendanceRequest->forwarded_date,
                                            )->format('Y-m-d h:i A');
                                            $approvedDate = Carbon\Carbon::parse(
                                                $attendanceRequest->approved_date,
                                            )->format('Y-m-d h:i A');
                                            $rejectedDate = Carbon\Carbon::parse(
                                                $attendanceRequest->rejected_date,
                                            )->format('Y-m-d h:i A');
                                            $cancelledDate = Carbon\Carbon::parse(
                                                $attendanceRequest->cancelled_date,
                                            )->format('Y-m-d h:i A');
                                        }

                                    @endphp
                                    <div class="ml-2">
                                        {{ $createdDate . ' ' . Carbon\Carbon::parse($attendanceRequest->created_at)->format('h:i A') }}
                                    </div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Type :</span>
                                    <div class="ml-2">{{ $attendanceRequest->getType() }}</div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Status :</span>
                                    <div class="ml-2">{{ $attendanceRequest->getStatus() }}</div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Requested Date :</span>
                                    <div class="ml-2">
                                        {{-- {{ $attendanceRequest->date }}
                                            @if (isset($lastRequestDate))
                                                - {{ $lastRequestDate->date}}
                                            @endif --}}
                                        {{ setting('calendar_type') == 'BS' ? $attendanceRequest->nepali_date : date('M d, Y', strtotime($attendanceRequest->date)) }}
                                    </div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Number of Days :</span>
                                    <div class="ml-2">
                                        {{ isset($attendanceRequest->kind) && ($attendanceRequest->kind == 1 || $attendanceRequest->kind == 2) ? 0.5 : 1 }}
                                    </div>
                                </li>
                                @if ($attendanceRequest->time)
                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Requested Time :</span>
                                        <div class="ml-2">
                                            {{ $attendanceRequest->time ? date('h:i A', strtotime($attendanceRequest->time)) : '' }}
                                        </div>
                                    </li>
                                @endif
                                @if ($attendanceRequest->kind)
                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Kind :</span>
                                        <div class="ml-2">
                                            {{ $attendanceRequest->getKind() }}
                                        </div>
                                    </li>
                                @endif
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Reason :</span>
                                    <div class="ml-2">{{ $attendanceRequest->detail }}</div>
                                </li>

                                @if (
                                    $menuRoles->assignedRoles('attendanceRequest.updateStatus') &&
                                        $attendanceRequest->employee_id != auth()->user()->emp_id)
                                    <div class="col-md-12">
                                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Action Detail
                                        </legend>
                                        <div class="row">
                                            <label class="col-form-label col-lg-3">Status :</label>
                                            <div class="col-lg-9 form-group-feedback form-group-feedback-right">
                                                <div class="input-group">
                                                    {!! Form::select('status', $statusList, $attendanceRequest->status ?? null, [
                                                        'id' => 'attendanceStatus',
                                                        'class' => 'form-control select-search',
                                                    ]) !!}
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
                                        </div>
                                        <div class="text-center mt-3">
                                            <button id="submitBtn" type="submit"
                                                class="btn btn-success btn-labeled btn-labeled-left">
                                                <b><i class="icon-database-insert"></i></b> Save Changes
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Employee Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Full Name :</span>
                            <div class="ml-2">{{ optional($attendanceRequest->employee)->full_name }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Designation :</span>
                            <div class="ml-2">
                                {{ optional(optional($attendanceRequest->employee)->designation)->title }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Sub-Function :</span>
                            <div class="ml-2">
                                {{ optional(optional($attendanceRequest->employee)->department)->title }}</div>
                        </li>
                    </ul>
                    @if (($attendanceRequest->status == '2' || $attendanceRequest->status == '3') && auth()->user()->user_type != 'employee')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Recommended Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Recommended Date :</span>
                                <div class="ml-2">{{ $forwardedDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Recommended By :</span>
                                <div class="ml-2">
                                    {{ optional(optional($attendanceRequest->forwardedByModel)->userEmployer)->full_name }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Recommended Remarks :</span>
                                <div class="ml-2">{{ $attendanceRequest->forwarded_remarks }}</div>
                            </li>
                        </ul>
                    @endif
                    @if ($attendanceRequest->status == '3' && auth()->user()->user_type != 'employee')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Approved Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Approved Date :</span>
                                <div class="ml-2">{{ $approvedDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Approved By :</span>
                                <div class="ml-2">
                                    {{ optional(optional($attendanceRequest->approvedByModel)->userEmployer)->full_name }}
                                </div>
                            </li>

                        </ul>
                    @endif
                    @if ($attendanceRequest->status == '4' && auth()->user()->user_type != 'employee')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Rejected Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Rejected Date :</span>
                                <div class="ml-2">{{ $rejectedDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Rejected By :</span>
                                <div class="ml-2">
                                    {{ optional(optional($attendanceRequest->rejectedByModel)->userEmployer)->full_name }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Message :</span>
                                <div class="ml-2">{{ $attendanceRequest->rejected_remarks }}</div>
                            </li>
                        </ul>
                    @endif
                    @if ($attendanceRequest->status == '5' && auth()->user()->user_type != 'employee')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Cancelled Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Cancelled Date :</span>
                                <div class="ml-2">{{ $cancelledDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Cancelled By :</span>
                                <div class="ml-2">
                                    {{ optional(optional($attendanceRequest->cancelledByModel)->userEmployer)->full_name }}
                                </div>
                            </li>

                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
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
        });
    </script>
@endsection
