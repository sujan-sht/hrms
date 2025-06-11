@extends('admin::layout')
@section('title')Holiday @stop
@section('breadcrum')
    <a href="{{ route('leaveType.index') }}" class="breadcrumb-item">Holiday</a>
    <a class="breadcrumb-item active">View</a>
@stop


@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        {{-- <div class="mb-3 text-center">
                            <a href="#" class="d-inline-block">
                                <img src="../../../assets/images/demo/cover3.jpg" class="img-fluid rounded" alt="">
                            </a>
                        </div> --}}

                        <h3 class="card-title pt-2 mb-1">
                            <a href="#" class="text-body">{{ $holiday->title }}</a>
                        </h3>

                        <ul class="list-inline list-inline-bullet text-muted mb-3">
                            {{-- <li class="list-inline-item">By <a href="#">{{ $holiday->creator }}</a></li> --}}
                            {{-- <li class="list-inline-item">{{ getStandardDateFormat($holiday->eng_date) }}</li> --}}
                            <li class="list-inline-item float-right" >{{ $holiday->fiscal_year_id }}</li>
                            {{-- <li class="list-inline-item"><a href="#"><i class="ph-heart text-pink me-1"></i> 281</a> --}}
                            </li>
                        </ul>

                        <div class="mb-3">
                            {!! $holiday->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Sidebar content -->
            <div class="sidebar-content">

                <!-- Tags -->
                <div class="card">
                    <div class="sidebar-section-header border-bottom">
                        <span class="fw-semibold">Employee Tags</span>
                    </div>

                    <div class="sidebar-section-body pb-2">
                        <ul class="list-inline mb-0">
                            @foreach ($holidayDetails as $holidayDetail)
                                <li class="list-inline">
                                    <a href="#">
                                        <span
                                            class="badge bg-light border-start border-width-3 text-body border-primary rounded-start-0 mb-2">{{ $participantName }}</span>
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
                <!-- /tags -->
            </div>
            <!-- /sidebar content -->


        </div>

    </div>

@stop
