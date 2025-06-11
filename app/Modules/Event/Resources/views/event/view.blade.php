@extends('admin::layout')
@section('title')Event @stop
@section('breadcrum')
    <a href="{{ route('leaveType.index') }}" class="breadcrumb-item">Event</a>
    <a class="breadcrumb-item active">View</a>
@stop


@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                {{-- <div class="card-img-actions">
                    @if ($notice->image)
                        <img class="card-img-top img-fluid" src="{{ asset('uploads/notice/' . $notice->image) }}"
                            alt="" style="width: 100%;height: 60vh;">
                    @endif
                    <div class="card-img-actions-overlay card-img-top">
                    </div>
                </div> --}}

                <div class="card-body">
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <p class="card-text">{!! $event->description !!}</p>

                    @if ($event->note)
                        <b>Note</b>
                        <p class="card-text">{!! $event->note !!}</p>
                    @endif

                </div>
                @php
                    $eventStartDate = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($event->event_start_date) : getStandardDateFormat($event->event_start_date);
                    $eventEndDate = setting('calendar_type') == "BS" ? date_converter()->eng_to_nep_convert($event->event_end_date) : getStandardDateFormat($event->event_end_date);
                @endphp
                <div class="card-footer d-flex justify-content-between">
                    <span class="text-muted">{{ $eventStartDate }} -
                        {{ $event->event_end_date ? $eventEndDate : ''}} |
                        {{ $event->location }}</span>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <!-- Sidebar content -->

            <!-- Tags -->
            <div class="card">
                <div class="row">
                    <div class="col-xl-12">
                        <p class="fw-semibold p-2">Organizations</p>

                        <div class="d-flex flex-wrap align-content-start p-2">
                            @foreach (json_decode($event->organization_id) as $organizationId)
                                <li class="list-inline">
                                    <a href="#">
                                        <span
                                            class="badge bg-secondary text-white py-2 px-3 border border-white border-opacity-25 border-end-0 border-top-0 rounded-start">{{ \App\Modules\Organization\Entities\Organization::where('id', $organizationId)->first()->name }}</span>
                                    </a>
                                </li>
                            @endforeach

                        </div>
                    @if (!is_null($event->branch_id))

                        <p class="fw-semibold p-2">Branchs</p>

                        <div class="d-flex flex-wrap align-content-start p-2">
                            @foreach (json_decode($event->branch_id) as $key => $branchId)
                                <li class="list-inline">
                                    <a href="#">
                                        <span
                                            class="badge bg-secondary text-white py-2 px-3 border border-white border-opacity-25 border-end-0 border-top-0 rounded-start">{{ \App\Modules\Branch\Entities\Branch::where('id', $branchId)->first()->name }}</span>
                                    </a>
                                </li>
                            @endforeach

                        </div>
                    @endif
                    @if (!is_null($event->department_id))

                        <p class="fw-semibold p-2">Sub-Functions</p>

                        <div class="d-flex flex-wrap align-content-start p-2">
                            @foreach (json_decode($event->department_id) as $key => $departmentId)
                                <li class="list-inline">
                                    <a href="#">
                                        <span
                                            class="badge bg-secondary text-white py-2 px-3 border border-white border-opacity-25 border-end-0 border-top-0 rounded-start">{{ \App\Modules\Setting\Entities\Sub-Function::where('id', $departmentId)->first()->title }}</span>
                                    </a>
                                </li>
                            @endforeach

                        </div>
                    @endif
                        <p class="fw-semibold p-2">Employee Tags</p>

                        <div class="d-flex flex-wrap align-content-start p-2">
                            @foreach ($participantNameLists as $participantName)
                                <li class="list-inline">
                                    <a href="#">
                                        <span
                                            class="badge bg-secondary text-white py-2 px-3 border border-white border-opacity-25 border-end-0 border-top-0 rounded-start">{{ $participantName }}</span>
                                    </a>
                                </li>
                            @endforeach

                        </div>
                    </div>

                </div>

            </div>
            <!-- /tags -->
            <!-- /sidebar content -->


        </div>

    </div>

@stop
