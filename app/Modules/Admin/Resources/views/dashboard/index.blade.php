@extends('admin::layout')
@section('title') Dashboard @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Dashboard</a>
@stop

@section('content')


    <div class="row">
        <div class="col-md-6">
            <legend class="text-uppercase font-size-sm font-weight-bold">Employee Modules</legend>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="{{ route('organization.index') }}" class="text-white">
                                <i class="icon-office icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Organization</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="{{ route('employee.index') }}" class="text-white">
                                <i class="icon-users icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Employee</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="{{ route('employee.directory') }}" class="text-white">
                                <i class="icon-address-book2 icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Directory</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <legend class="text-uppercase font-size-sm font-weight-bold">Leave Modules</legend>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="{{ route('leaveType.index') }}" class="text-white">
                                <i class="icon-stack3 icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Leave Type</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="{{ route('leave.index') }}" class="text-white">
                                <i class="icon-file-text3 icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Leave</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="#" class="text-white">
                                <i class="icon-file-excel icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Report</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <legend class="text-uppercase font-size-sm font-weight-bold">Attendance Modules</legend>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="{{ route('shift.index') }}" class="text-white">
                                <i class="icon-alarm icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Shift</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="" class="text-white">
                                <i class="icon-user-check icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Attendance</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="#" class="text-white">
                                <i class="icon-file-excel icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Report</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <legend class="text-uppercase font-size-sm font-weight-bold">Attendance Modules</legend>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="#" class="text-white">
                                <i class="icon-cash3 icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Payroll</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body bg-secondary text-white text-center"
                            style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                            <a href="#" class="text-white">
                                <i class="icon-file-excel icon-3x opacity-75 mt-2"></i>
                                <h3 class="mb-0 mt-2">Report</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- <legend class="text-uppercase font-size-sm font-weight-bold">All Modules</legend>

    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Organization</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Employee</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Employee Directory</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Leave Type</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Leave</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Shift</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-1">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white"></a>
                        <h3 class="mb-0 mt-2" style="padding: 29px;">&nbsp;</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white"></a>
                        <h3 class="mb-0 mt-2" style="padding: 29px;">&nbsp;</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Organization</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Employee</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Employee Directory</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Leave Type</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Leave</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Shift</h3>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-1">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white"></a>
                        <h3 class="mb-0 mt-2" style="padding: 29px;">&nbsp;</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white">
                        <i class="icon-office icon-3x opacity-75 mt-2"></i>
                        <h3 class="mb-0 mt-2">Attendance</h3>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-1">
            <div class="card">
                <div class="card-body bg-secondary text-white text-center" style="background-image: url({{ asset('admin/global/images/backgrounds/panel_bg.png') }}); background-size: contain;">
                    <a href="" class="text-white"></a>
                        <h3 class="mb-0 mt-2" style="padding: 29px;">&nbsp;</h3>
                    </a>
                </div>
            </div>
        </div>
    </div> -->

@stop
