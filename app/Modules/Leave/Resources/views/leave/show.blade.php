@extends('admin::layout')
@section('title')
    View Leave
@endsection
@section('breadcrum')
    <a href="{{ route('leave.index') }}" class="breadcrumb-item">Leaves</a>
    <a class="breadcrumb-item active">View</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    {!! Form::model($leaveModel, [
        'method' => 'PUT',
        'route' => ['leave.update', $leaveModel->id],
        'class' => 'form-horizontal',
        'id' => 'leaveFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <legend class="text-uppercase font-size-sm font-weight-bold">Leave Detail</legend>
                        <div class="col-md-12">
                            <ul class="media-list">
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Applied Date :</span>
                                    @php
                                        $createdDate =
                                            setting('calendar_type') == 'BS'
                                                ? date_converter()->eng_to_nep_convert(
                                                    date('Y-m-d', strtotime($leaveModel->created_at)),
                                                )
                                                : date('M d, Y', strtotime($leaveModel->created_at));
                                        if (setting('calendar_type') == 'BS') {
                                            $forwardedDate =
                                                date_converter()->eng_to_nep_convert($leaveModel->forwarded_date) .
                                                ' ' .
                                                Carbon\Carbon::parse($leaveModel->forwarded_date)->format('h:i A');
                                            $approvedDate =
                                                date_converter()->eng_to_nep_convert($leaveModel->approved_date) .
                                                ' ' .
                                                Carbon\Carbon::parse($leaveModel->approved_date)->format('h:i A');
                                            $rejectedDate =
                                                date_converter()->eng_to_nep_convert($leaveModel->rejected_date) .
                                                ' ' .
                                                Carbon\Carbon::parse($leaveModel->rejected_date)->format('h:i A');
                                            $cancelledDate =
                                                date_converter()->eng_to_nep_convert($leaveModel->cancelled_date) .
                                                ' ' .
                                                Carbon\Carbon::parse($leaveModel->cancelled_date)->format('h:i A');
                                        } else {
                                            $forwardedDate = Carbon\Carbon::parse($leaveModel->forwarded_date)->format(
                                                'Y-m-d h:i A',
                                            );
                                            $approvedDate = Carbon\Carbon::parse($leaveModel->approved_date)->format(
                                                'Y-m-d h:i A',
                                            );
                                            $rejectedDate = Carbon\Carbon::parse($leaveModel->rejected_date)->format(
                                                'Y-m-d h:i A',
                                            );
                                            $cancelledDate = Carbon\Carbon::parse($leaveModel->cancelled_date)->format(
                                                'Y-m-d h:i A',
                                            );
                                        }
                                        $updatedDate =
                                            setting('calendar_type') == 'BS'
                                                ? date_converter()->eng_to_nep_convert(
                                                    date('Y-m-d', strtotime($leaveModel->updated_at)),
                                                )
                                                : date('M d, Y', strtotime($leaveModel->updated_at));
                                    @endphp
                                    <div class="ml-2">
                                        {{ $createdDate . ' ' . Carbon\Carbon::parse($leaveModel->created_at)->format('h:i A') }}
                                    </div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Leave Type :</span>
                                    <div class="ml-2">{{ optional($leaveModel->leaveTypeModel)->name }}</div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Leave Date :</span>
                                    <div class="ml-2">{{ $leaveModel->getDateRangeWithCount()['range'] }}</div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Leave Duration :</span>
                                    <div class="ml-2">
                                        @if (isset($leaveModel->generated_by) && $leaveModel->generated_by == 11)
                                            {{ $leaveModel->generated_no_of_days }}
                                        @else
                                            {{ $leaveModel->getDateRangeWithCount()['count'] }}
                                        @endif
                                        Days
                                    </div>
                                </li>
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Reason :</span>
                                    <div class="ml-2">{{ $leaveModel->reason }}</div>
                                </li>
                                @if (optional($leaveModel->leaveTypeModel)->code == 'SUBLV')
                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Substitute Date :</span>
                                        <div class="ml-2">
                                            {{ setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert(date('Y-m-d', strtotime($leaveModel->substitute_date))) : date('M d, Y', strtotime($leaveModel->substitute_date)) }}
                                        </div>
                                    </li>
                                @endif
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Status :</span>
                                    <div class="ml-2"><span
                                            class="badge badge-{{ $leaveModel->getStatusWithColor()['color'] }}">{{ $leaveModel->getStatusWithColor()['status'] }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        @if (count($leaveModel->attachments) > 0)
                            <div class="col-md-6">
                                <legend class="text-uppercase font-size-sm font-weight-bold">Attachments</legend>
                                <table class="table mt-0">
                                    @foreach ($leaveModel->attachments as $file)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="mr-3">
                                                        <a class="btn btn-teal rounded-pill btn-icon">
                                                            <span class="">{{ strtoupper($file->extension) }}</span>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="{{ $file->attachment }}" target="_blank"
                                                            class="text-body font-weight-semibold letter-icon-title">{{ $file->title }}</a>
                                                        <div class="text-muted font-size-sm">{{ $file->getSize() }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                        @if ($leaveModel->alt_employee_id)
                            <div class="col-md-12">
                                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Alternative Employee
                                    Detail</legend>
                                <ul class="media-list">
                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Full Name :</span>
                                        <div class="ml-2">{{ optional($leaveModel->altEmployeeModel)->full_name }}</div>
                                    </li>
                                    <li class="media mt-2">
                                        <span class="font-weight-semibold">Message :</span>
                                        <div class="ml-2">{{ $leaveModel->alt_employee_message }}</div>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        {{-- @if ($leaveModel->status == 1) --}}
                        @if (isset($leaveModel->generated_by) && $leaveModel->generated_by == 11)
                            {{-- do nothing --}}
                        @else
                            @if ($menuRoles->assignedRoles('leave.updateStatus') && $leaveModel->employee_id != auth()->user()->emp_id)
                                <div class="col-md-12">
                                    <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Action Detail
                                    </legend>
                                    <div class="row">
                                        <label class="col-form-label col-lg-2">Status :</label>
                                        <div class="col-lg-3 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::select('status', $statusList, null, ['id' => 'leaveStatus', 'class' => 'form-control select-search']) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div id="statusMessage" class="row mt-3" style="display:none;">
                                        <label class="col-form-label col-lg-2">Message :</label>
                                        <div class="col-lg-10 form-group-feedback form-group-feedback-right">
                                            <div class="input-group">
                                                {!! Form::textarea('status_message', null, [
                                                    'rows' => 3,
                                                    'placeholder' => 'Write message..',
                                                    'class' => 'form-control',
                                                ]) !!}
                                            </div>
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
                        @endif
                        {{-- @endif --}}
                    </div>
                    <div class="row">
                        {{-- @if ($leaveModel->status == '1' || $leaveModel->status == '2') --}}
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Remaining Leave Detail</legend>

                        {!! Form::hidden('employee_id', $leaveModel->employee_id, ['class' => 'employeeId']) !!}
                        {!! Form::hidden('leave_kind', $leaveModel->leave_kind, ['class' => 'leaveKind']) !!}

                        <div class="col-lg-12 mb-3">
                            <div id="remainingLeaveDetail"></div>
                        </div>
                        {{-- @endif --}}
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
                            <div class="ml-2">{{ optional($leaveModel->employeeModel)->full_name }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Designation :</span>
                            <div class="ml-2">
                                {{ optional(optional($leaveModel->employeeModel)->designation)->dropvalue }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Sub-Function :</span>
                            <div class="ml-2">{{ optional(optional($leaveModel->employeeModel)->department)->dropvalue }}
                            </div>
                        </li>
                    </ul>
                    @if ($leaveModel->status == '2' || $leaveModel->status == '3')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Forwarded Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Forwarded By :</span>
                                <div class="ml-2">
                                    {{ optional(optional($leaveModel->forwardUserModel)->userEmployer)->full_name }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Forwarded Date :</span>
                                <div class="ml-2">
                                    {{ $forwardedDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Designation :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Sub-Function :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->department)->dropvalue }}
                                </div>
                            </li>
                            @if (!is_null($leaveModel->forward_message))
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Message :</span>
                                    <div class="ml-2">{{ $leaveModel->forward_message }}</div>
                                </li>
                            @endif

                        </ul>
                    @endif
                    @if ($leaveModel->status == '3')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Accepted Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Accepted By :</span>
                                <div class="ml-2">
                                    {{ optional(optional($leaveModel->acceptModel)->userEmployer)->full_name }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Accepted Date :</span>
                                <div class="ml-2">
                                    {{ $approvedDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Designation :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Sub-Function :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->department)->dropvalue }}
                                </div>
                            </li>
                            @if (!is_null($leaveModel->accept_message))
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Message :</span>
                                    <div class="ml-2">{{ $leaveModel->accept_message }}</div>
                                </li>
                            @endif


                        </ul>
                    @endif
                    @if ($leaveModel->status == '4')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Rejected Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Rejected By :</span>
                                <div class="ml-2">
                                    {{ optional(optional($leaveModel->rejectUserModel)->userEmployer)->full_name }}</div>
                            </li>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Rejected Date :</span>
                                <div class="ml-2">
                                    {{ $rejectedDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Designation :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Sub-Function :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->department)->dropvalue }}
                                </div>
                            </li>
                            @if (!is_null($leaveModel->reject_message))
                                <li class="media mt-2">
                                    <span class="font-weight-semibold">Message :</span>
                                    <div class="ml-2">{{ $leaveModel->reject_message }}</div>
                                </li>
                            @endif

                        </ul>
                    @endif
                    @if ($leaveModel->status == '5')
                        <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Cancelled Detail</legend>
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Cancelled By :</span>
                                <div class="ml-2">{{ optional($leaveModel->cancelModel)->full_name }}</div>
                            </li>

                            <li class="media mt-2">
                                <span class="font-weight-semibold">Cancelled Date :</span>
                                <div class="ml-2">
                                    {{ $cancelledDate }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Designation :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                                </div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Sub-Function :</span>
                                <div class="ml-2">
                                    {{ optional(optional(optional($leaveModel->statusBy)->userEmployer)->department)->dropvalue }}
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
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // initiate select2
            $('.select-search').select2();

            $('#leaveStatus').on('change', function() {
                var status = $(this).val();
                if (status == '2' || status == '4') {
                    $('#statusMessage').show();
                } else {
                    $('#statusMessage').hide();
                }
            });
            getRemainingLeaveDetail()

            function getRemainingLeaveDetail() {
                $.ajax({
                    url: "{{ route('leave.getRemainingList') }}",
                    method: 'GET',
                    data: {
                        employee_id: $('.employeeId').val(),
                        leave_kind: $('.leaveKind').val(),
                    },
                    success: function(resp) {
                        $('#remainingLeaveDetail').html(resp.view)
                    }
                })
            }
        })
    </script>
@endsection
