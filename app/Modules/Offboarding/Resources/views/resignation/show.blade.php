@extends('admin::layout')
@section('title') Applicant Profile @endSection
@section('breadcrum')
<a href="{{route('applicant.index')}}" class="breadcrumb-item">Applicants</a>
<a class="breadcrumb-item active">Profile</a>
@stop

@section('content')
    <div class="profile-cover">
        <div class="profile-cover-img" style="background-image: url(../../../admin/banner.jpg)"></div>
        <div class="media align-items-center text-center text-lg-left flex-column flex-lg-row m-0">
            <div class="mr-lg-3 mb-2 mb-lg-0">
                <a href="#" class="profile-thumb">
                    <img src="{{ asset('admin/default.png') }}" class="border-white rounded-circle" width="48" height="48" alt="">
                </a>
            </div>

            <div class="media-body text-white">
                <h1 class="mb-0">{{ $applicantModel->getFullName() }}</h1>
                <span class="d-block">{{ $applicantModel->email }}</span>
            </div>

            <div class="ml-lg-3 mt-2 mt-lg-0">
                <ul class="list-inline list-inline-condensed mb-0">
                    <li class="list-inline-item"><a href="#" class="btn btn-light border-transparent"><i class="icons icon-file-picture mr-2"></i> Cover image</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="navbar navbar-expand-lg navbar-light">
        <div class="text-center d-lg-none w-100">
            <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-second">
                <i class="icon-menu7 mr-2"></i>
                Profile navigation
            </button>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav py-2">
                <li class="nav-item pr-1">
                    <a href="#activity" class="btn btn-white active" data-toggle="tab">
                        <i class="icons icon-pulse2 mr-1"></i>
                        Activity
                    </a>
                </li>
                <li class="nav-item pr-1">
                    <a href="#schedule" class="btn btn-white" data-toggle="tab">
                        <i class="icons icon-calendar3 mr-1"></i>
                        Schedule
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Full Name :</span>
                            <div class="ml-auto">{{ $applicantModel->getFullname() }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Address :</span>
                            <div class="ml-auto">{{ $applicantModel->address.', '.$applicantModel->city.', '.$applicantModel->province }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Mobile :</span>
                            <div class="ml-auto">{{ $applicantModel->mobile }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Email :</span>
                            <div class="ml-auto">{{ $applicantModel->email }}</div>
                        </li>
                    </ul>
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-2">Job Detail</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Functional Title :</span>
                            <div class="ml-auto">{{ optional($applicantModel->mrfModel)->title }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Source :</span>
                            <div class="ml-auto">{{ $applicantModel->source ? $applicantModel->getSource() : '-' }}</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Experience :</span>
                            <div class="ml-auto">{{ $applicantModel->experience }} Years</div>
                        </li>
                        <li class="media mt-2">
                            <span class="font-weight-semibold">Expected Salary :</span>
                            <div class="ml-auto">Rs. {{ number_format($applicantModel->expected_salary) }}</div>
                        </li>
                    </ul>
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-2">Skills</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            {!! $applicantModel->skills !!}
                        </li>
                    </ul>
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-2">Document</legend>
                    <ul class="media-list">
                        <li class="media mt-2">
                            <a href="{{ $applicantModel->getResume() }}" class="text-secondary">
                                <i class="icons icon-file-pdf mr-2"></i>Resume.pdf 
                            </a>
                        </li>
                        <li class="media mt-2">
                            <a href="{{ $applicantModel->getCoverLetter() }}" class="text-secondary">
                                <i class="icons icon-file-pdf mr-2"></i>Cover Letter.pdf 
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div id="activity" class="tab-pane fade active show">
                    @include('onboarding::applicant.tabView.activity')
                </div>
                <div id="schedule" class="tab-pane fade">
                    @include('onboarding::applicant.tabView.schedule')
                </div>
            </div>
        </div>
    </div>
@endsection