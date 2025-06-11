@extends('admin::employee.layout')
@section('title') Claim & Request Management @stop
@section('breadcrum') Claim & Request Management @stop

@section('content')


    <div class="box">
        <div class="row">
            {{-- <div class="col-12 col-sm-6 col-md-4 pr-2">
            <div class="card mb-3 bg-white">
                <div class="card-body">
                    <a href="{{route('claimRequest.attendance-adjust')}}">
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h5>Attendance Adjustment
                            <small>Click to adjust your attendance</small></h5>
                    </a>
                </div>
            </div>
        </div> --}}

            <div class="col-12 col-sm-6 col-md-4 pl-2">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('employeerequest.index') }}">
                            <div class="icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h5>Request Management
                                <small>Click to request</small>
                            </h5>
                        </a>
                    </div>
                </div>
            </div>

            @if (optional(auth()->user()->userEmployer)->organization_id == 4 ||
                    optional(optional(auth()->user()->userEmployer)->organization)->company_name == 'Lumbini Vidyut Udyog')
                <div class="col-12 col-sm-6 col-md-4 pr-2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ route('claimRequest.preovertime') }}">
                                <div class="icon">
                                    <i class="fas fa-chevron-circle-right"></i>
                                </div>
                                <h5>Pre Overtime Request
                                    <small>Click to request pre-overtime</small>
                                </h5>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- <div class="col-12 col-sm-6 col-md-4 pl-2 pr-2">
            <div class="card mb-2">
                <div class="card-body">
                    <a href="{{route('claimRequest.overtime')}}">
                        <div class="icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <h5>
                            claim Overtime
                            <small>Click to claim your overtimes</small></h5>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4 pl-2">
            <div class="card mb-3">
                <div class="card-body">
                    <a href="#">
                        <div class="icon">
                            <i class="fas fa-bus"></i>
                        </div>
                        <h5>Travel Attendance
                            <small>Click to claim travel attendance</small></h5>
                    </a>
                </div>
            </div>
        </div>


        <div class="col-12 col-sm-6 col-md-4 pl-2 pr-2">
            <div class="card">
                <div class="card-body">
                    <a href="#">
                        <div class="icon">
                            <i class="fa fa-clock"></i>
                        </div>
                        <h5>Credit Hour Request
                            <small>Click to request credit hour</small></h5>
                    </a>
                </div>
            </div>
        </div> --}}


        </div>
    </div>


@endsection
