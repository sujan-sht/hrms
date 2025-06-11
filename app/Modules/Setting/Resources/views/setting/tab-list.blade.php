@extends('admin::layout')
@section('title') Settings @stop
@section('breadcrum')
    <a class="breadcrumb-item active">All Setups</a>
@endsection
@section('css')
    <style>
        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #45748a !important;
        }
    </style>
@endsection



@php
    $subMenusOfGeneralSettings = [
        [
            'title' => 'Organization Setup',
            'idName' => 'organization',
            'href' => '#organization',
            'arial_controls' => 'organization',
        ],
        [],
    ];

@endphp
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-toggle="tab" data-target="#nav-home" type="button"
                        role="tab" aria-controls="nav-home" aria-selected="true"><i class="icon-cogs"></i> General
                        Setup</button>
                    {{-- <button class="nav-link" id="fiscal-year-tab" data-toggle="tab" data-target="#fiscal-year"
                        type="button" role="tab" aria-controls="fiscal-year" aria-selected="true"><i
                            class="icon-map4"></i> Fiscal Year Setting</button>
                    <button class="nav-link" id="nav-profile-tab" data-toggle="tab" data-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false"><i
                            class="icon-clipboard2"></i> Leave
                        Setting</button>
                    <button class="nav-link" id="nav-contact-tab" data-toggle="tab" data-target="#nav-contact"
                        type="button" role="tab" aria-controls="nav-contact" aria-selected="false"> <i
                            class="icon-touch"></i> Attendance
                        Setting</button>
                    <button class="nav-link" id="payroll-tab" data-toggle="tab" data-target="#payroll" type="button"
                        role="tab" aria-controls="payroll" aria-selected="false"> <i class="icon-cash"></i> Payroll
                        Setting</button> --}}

                    <button class="nav-link" id="email-setup-tab" data-toggle="tab" data-target="#email-setup"
                        type="button" role="tab" aria-controls="email-setup" aria-selected="false"> <i
                            class="icon-envelop5"></i> Email Setup</button>

                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="general-tab">
                    @include('setting::setting.partial.general.company-form')
                </div>

                <div class="tab-pane fade" id="fiscal-year" role="tabpanel" aria-labelledby="fiscal-year-tab">
                    @include('setting::setting.partial.general.fiscal-year-form')
                </div>

                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <ul class="nav nav-pills mt-3" id="generalSubTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="leave-tab" data-toggle="tab" href="#leave" role="tab"
                                aria-controls="leave">
                                Leave Year
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="leave-type-tab" data-toggle="tab" href="#leave-type" role="tab"
                                aria-controls="leave-type">
                                Leave Type Setup
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="leave-encashment-tab" data-toggle="tab" href="#leave-encashment"
                                role="tab" aria-controls="leave-encashment">
                                Leave Encashment Setup
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="force-tab" data-toggle="tab" href="#force" role="tab"
                                aria-controls="force">
                                Force Leave Setup
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="leave-deduction-tab" data-toggle="tab" href="#leave-deduction"
                                role="tab" aria-controls="leave-deduction">
                                Leave Deduction Setup
                            </a>
                        </li>

                    </ul>

                    <!-- Sub-Tab Content -->
                    <div class="tab-content mt-3" id="generalSubTabsContent">
                        <div class="tab-pane fade show active" id="leave" role="tabpanel" aria-labelledby="leave-tab">
                            @include('setting::setting.partial.leave.leave-year')
                        </div>
                        <div class="tab-pane fade" id="leave-type" role="tabpanel" aria-labelledby="leave-type-tab">
                            @include('setting::setting.partial.leave.leave-type')
                        </div>
                        <div class="tab-pane fade" id="leave-encashment" role="tabpanel"
                            aria-labelledby="leave-encashment-tab">
                            <p>
                                leave Encashment content
                            </p>
                        </div>
                        <div class="tab-pane fade" id="force" role="tabpanel" aria-labelledby="force-tab">
                            <p>Content for Force Leave Setup.</p>
                        </div>
                        <div class="tab-pane fade" id="leave-deduction" role="tabpanel"
                            aria-labelledby="leave-deduction-tab">
                            <p>Content for Leave Deduction Setup.</p>
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <ul class="nav nav-pills mt-3" id="generalSubTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="shift-tab" data-toggle="tab" href="#shift" role="tab"
                                aria-controls="shift">
                                Shift
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="shift-group-tab" data-toggle="tab" href="#shift-group"
                                role="tab" aria-controls="shift-group">
                                Shift Group
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="biometric-setup-tab" data-toggle="tab" href="#biometric-setup"
                                role="tab" aria-controls="biometric-setup">
                                Biometric Setup
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="geofence-tab" data-toggle="tab" href="#geofence" role="tab"
                                aria-controls="geofence">
                                Geofence Setup
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="web-attendance-tab" data-toggle="tab" href="#web-attendance"
                                role="tab" aria-controls="web-attendance">
                                Web Attendance Setup
                            </a>
                        </li>

                    </ul>

                    <!-- Sub-Tab Content -->
                    <div class="tab-content mt-3" id="generalSubTabsContent">
                        <div class="tab-pane fade show active" id="shift" role="tabpanel"
                            aria-labelledby="leave-tab">
                            Shift content
                        </div>
                        <div class="tab-pane fade" id="shift-group" role="tabpanel" aria-labelledby="shift-group-tab">
                            Shift Group
                        </div>
                        <div class="tab-pane fade" id="biometric-setup" role="tabpanel"
                            aria-labelledby="biometric-setup-tab">
                            @include('setting::setting.partial.attendance.biometric-setup')
                        </div>
                        <div class="tab-pane fade" id="geofence" role="tabpanel" aria-labelledby="geofence-tab">
                            @include('setting::setting.partial.attendance.geofence-setup')
                        </div>
                        <div class="tab-pane fade" id="web-attendance" role="tabpanel"
                            aria-labelledby="web-attendance-tab">
                            Web Attendance Setup
                        </div>

                    </div>
                </div>
                <div class="tab-pane fade" id="payroll" role="tabpanel" aria-labelledby="payroll-tab">
                    payroll
                </div>
                <div class="tab-pane fade" id="email-setup" role="tabpanel" aria-labelledby="email-setup-tab">
                    @include('setting::setting.partial.general.email-setup')
                </div>
            </div>
        </div>
    </div>
@stop
