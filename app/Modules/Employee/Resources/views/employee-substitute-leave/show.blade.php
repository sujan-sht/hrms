@extends('admin::layout')
@section('title') View Substitute Leave @endsection
@section('breadcrum')
<a href="{{ route('overtimeRequest.index') }}" class="breadcrumb-item">Substitute Leaves</a>
<a class="breadcrumb-item active">View</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Substitute Leave Detail</legend>
                    <div class="col-md-12">
                        <ul class="media-list">
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Applied Date :</span>
                                @php
                                    $createdDate =
                                        setting('calendar_type') == 'BS'
                                            ? date_converter()->eng_to_nep_convert(
                                                date('Y-m-d', strtotime($employeeSubstituteLeaveModel->created_at)),
                                            )
                                            : date('M d, Y', strtotime($employeeSubstituteLeaveModel->created_at));
                                    $updatedDate =
                                        setting('calendar_type') == 'BS'
                                            ? date_converter()->eng_to_nep_convert(
                                                date('Y-m-d', strtotime($employeeSubstituteLeaveModel->updated_at)),
                                            )
                                            : date('M d, Y', strtotime($employeeSubstituteLeaveModel->updated_at));
                                @endphp
                                <div class="ml-2">{{ $createdDate . ' ' . Carbon\Carbon::parse($employeeSubstituteLeaveModel->created_at)->format('h:i A') }}</div>
                            </li>
                                        <li class="media mt-2"> Checkin: <strong class="ml-2"> {{ $employeeSubstituteLeaveModel->checkin }} </strong> </li>
                                        <li  class="media mt-2"> Checkout: <strong class="ml-2">  {{ $employeeSubstituteLeaveModel->checkout }} </strong></li>
                                        <li  class="media mt-2"> Total Working Hr: <strong class="ml-2"> {{ $employeeSubstituteLeaveModel->total_working_hr }}  </strong></li>
                            {{-- <li class="media mt-2">
                                <span class="font-weight-semibold">Leave Type :</span>
                                <div class="ml-2">{{ optional($employeeSubstituteLeaveModel->leaveTypeModel)->name }}</div>
                            </li> --}}
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Leave Date :</span>
                                <div class="ml-2">{{ setting('calendar_type') == "BS" ?  $employeeSubstituteLeaveModel->nepali_date : date('M d, Y', strtotime($employeeSubstituteLeaveModel->date)) }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Remark :</span>
                                <div class="ml-2">{{  $employeeSubstituteLeaveModel->remark }}</div>
                            </li>
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Status :</span>
                                <div class="ml-2"><span
                                        class="badge badge-{{ $employeeSubstituteLeaveModel->getStatusWithColor()['color'] }}">{{ $employeeSubstituteLeaveModel->getStatusWithColor()['status'] }}</span>
                                </div>
                            </li>
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
                        <div class="ml-2">{{ optional($employeeSubstituteLeaveModel->employee)->full_name }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Designation :</span>
                        <div class="ml-2">
                            {{ optional(optional($employeeSubstituteLeaveModel->employee)->designation)->dropvalue }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-semibold">Department :</span>
                        <div class="ml-2">{{ optional(optional($employeeSubstituteLeaveModel->employee)->department)->dropvalue }}
                        </div>
                    </li>
                </ul>
                @if ($employeeSubstituteLeaveModel->status == '2' || $employeeSubstituteLeaveModel->status == '3')
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Forwarded Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Forwarded By :</span>
                            <div class="ml-2">
                                {{ optional(optional($employeeSubstituteLeaveModel->forwardedUser)->userEmployer)->full_name }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Designation :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                            </div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Department :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->department)->dropvalue }}
                            </div>
                        </li>
                        @if (!is_null($employeeSubstituteLeaveModel->forwarded_remarks))
                            <li class="media mt-2">
                                <span class="font-weight-semibold">Message :</span>
                                <div class="ml-2">{{ $employeeSubstituteLeaveModel->forwarded_remarks }}</div>
                            </li>
                        @endif

                    </ul>
                @endif
                @if ($employeeSubstituteLeaveModel->status == '3')
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Accepted Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Accepted By :</span>
                            <div class="ml-2">
                                {{ optional(optional($employeeSubstituteLeaveModel->acceptedUser)->userEmployer)->full_name }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Designation :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                            </div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Department :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->department)->dropvalue }}
                            </div>
                        </li>
                    </ul>
                @endif
                @if ($employeeSubstituteLeaveModel->status == '4')
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Rejected Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Rejected By :</span>
                            <div class="ml-2">
                                {{ optional(optional($employeeSubstituteLeaveModel->rejectedUser)->userEmployer)->full_name }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Designation :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                            </div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Department :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->department)->dropvalue }}
                            </div>
                        </li>
                    </ul>
                @endif
                @if ($employeeSubstituteLeaveModel->status == '5')
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Cancelled Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Cancelled By :</span>
                            <div class="ml-2">{{ optional($employeeSubstituteLeaveModel->cancelledUser)->full_name }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Designation :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->designation)->dropvalue }}
                            </div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Department :</span>
                            <div class="ml-2">
                                {{ optional(optional(optional($employeeSubstituteLeaveModel->statusBy)->userEmployer)->department)->dropvalue }}
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
@endsection
