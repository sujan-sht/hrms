@extends('admin::layout')
@section('title') View Overtime Request @endsection
@section('breadcrum')
<a href="{{ route('overtimeRequest.index') }}" class="breadcrumb-item">Overtime Requests</a>
<a class="breadcrumb-item active">View</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
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
                                <legend class="text-uppercase font-size-sm font-weight-bold">Overtime Request Detail</legend>
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul class="media-list">
                                            <li class="media">
                                                <span class="font-weight-semibold">Employee :</span>
                                                <div class="ml-2">{{ optional($overtimeRequest->employee)->getFullName() }}</div>
                                            </li>
        
                                            <li class="media mt-2">
                                                <span class="font-weight-semibold">Date :</span>
                                                <div class="ml-2">{{ setting('calendar_type') == 'BS' ? $overtimeRequest->nepali_date :  $overtimeRequest->date}}</div>
                                            </li>
        
                                            <li class="media mt-2">
                                                <span class="font-weight-semibold">Start Time:</span>
                                                <div class="ml-2">{{ date('h:i A', strtotime($overtimeRequest->start_time)) }}</div>
                                            </li>
        
                                            <li class="media mt-2">
                                                <span class="font-weight-semibold">End Time:</span>
                                                <div class="ml-2">{{ date('h:i A', strtotime($overtimeRequest->end_time)) }}</div>
                                            </li>
        
                                            <li class="media mt-2">
                                                <span class="font-weight-semibold">Time (In minutes) :</span>
                                                <div class="ml-2">{{ $overtimeRequest->ot_time }}</div>
                                            </li>
        
                                            <li class="media mt-2">
                                                <span class="font-weight-semibold">Remarks :</span>
                                                <div class="ml-2">{{ $overtimeRequest->remarks }}</div>
                                            </li>
                                        </ul>
                                    </div>

                                    @if (isset($overtimeRequest->forwarded_date))
                                        <div class="col-md-3">
                                            <h3 class="font-size-sm font-weight-bold">Forwarded Details</h3>
                                            <ul class="media-list">
                                                <li class="media">
                                                    <span class="font-weight-semibold">Remarks:</span>
                                                    <div class="ml-2">{{ $overtimeRequest->forwarded_remarks }}</div>
                                                </li>
            
                                                <li class="media mt-2">
                                                    <span class="font-weight-semibold">By:</span>
                                                    <div class="ml-2">{{ optional(optional($overtimeRequest->forwardUserModel)->userEmployer)->full_name }}</div>
                                                </li>

                                                <li class="media mt-2">
                                                    <span class="font-weight-semibold">Date:</span>
                                                    <div class="ml-2">{{ $overtimeRequest->forwarded_date ? (setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($overtimeRequest->forwarded_date) :  $overtimeRequest->forwarded_date) : ''}}</div>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    @if (isset($overtimeRequest->approved_date))
                                        <div class="col-md-3">
                                            <h3 class="font-size-sm font-weight-bold">Approved Details</h3>
                                            <ul class="media-list">
                                                <li class="media">
                                                    <span class="font-weight-semibold">Remarks:</span>
                                                    <div class="ml-2">{{ $overtimeRequest->approved_remarks }}</div>
                                                </li>
            
                                                <li class="media mt-2">
                                                    <span class="font-weight-semibold">By:</span>
                                                    <div class="ml-2">{{ optional(optional($overtimeRequest->approvedUserModel)->userEmployer)->full_name }}</div>
                                                </li>

                                                <li class="media mt-2">
                                                    <span class="font-weight-semibold">Date:</span>
                                                    <div class="ml-2">{{ $overtimeRequest->approved_date ? (setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($overtimeRequest->approved_date) :  $overtimeRequest->approved_date) : ''}}</div>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    @if (isset($overtimeRequest->rejected_date))
                                        <div class="col-md-3">
                                            <h3 class="font-size-sm font-weight-bold">Rejected Details</h3>
                                            <ul class="media-list">
                                                <li class="media">
                                                    <span class="font-weight-semibold">Remarks:</span>
                                                    <div class="ml-2">{{ $overtimeRequest->rejected_remarks }}</div>
                                                </li>
            
                                                <li class="media mt-2">
                                                    <span class="font-weight-semibold">By:</span>
                                                    <div class="ml-2">{{ optional(optional($overtimeRequest->rejectedUserModel)->userEmployer)->full_name }}</div>
                                                </li>

                                                <li class="media mt-2">
                                                    <span class="font-weight-semibold">Date:</span>
                                                    <div class="ml-2">{{ $overtimeRequest->rejected_date ? (setting('calendar_type') == 'BS' ? date_converter()->eng_to_nep_convert($overtimeRequest->rejected_date) :  $overtimeRequest->rejected_date) : ''}}</div>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                                class="icon-backward2"></i></b>Go Back</a>
                </div>
            </div>
        </div>
@endsection