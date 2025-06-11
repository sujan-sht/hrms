@extends('admin::layout')
@section('title')Announcement @stop
@section('breadcrum')
    <a href="{{ route('notice.index') }}" class="breadcrumb-item">Notice</a>
    <a class="breadcrumb-item active">View</a>
    {{-- Announcement / View Announcement --}}
@stop
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop
@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-img-actions">
                    @if ($notice->image)
                        <img class="card-img-top img-fluid" src="{{ asset('uploads/notice/' . $notice->image) }}"
                            alt="" style="width: 100%;height: 60vh;">
                    @endif
                    <div class="card-img-actions-overlay card-img-top">
                        {{-- <a href="../../../assets/images/demo/flat/4.png" class="btn btn-outline-white border-width-2" data-popup="lightbox">
                            Preview
                        </a>
                        <a href="#" class="btn btn-outline-white border-width-2 ms-2">
                            Details
                        </a> --}}
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $notice->title }}</h5>
                    <p class="card-text">{!! $notice->description !!}</p>
                    <a href="{{ $notice->description }}" class="card-text">Link</a>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <span
                        class="text-muted">{{ setting('calendar_type') == 'BS' ? $notice->notice_date_nepali : $notice->notice_date }}
                        {{-- getStandardDateFormat($notice->notice_date) --}}
                        | {{ $notice->notice_time }}</span>
                </div>
            </div>

        </div>
        <div class="col-lg-4">

            <div class="card">
                <div class="card-body">
                    <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                        <ul class="media-list">
                            <li class="">
                                @if (!is_null($notice->file))
                                    <div class="row">
                                        <div class="mr-3">
                                            <i class="icon-file-text me-3"></i>
                                        </div>
                                        <div class="media-body">
                                            <a href="{{ asset('uploads/notice/' . $notice->file) }}" target="_blank">
                                                {{ $notice->file }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @if (count($notice->files) > 0)
                                    @foreach ($notice->files as $item)
                                        <div class="row">
                                            <div class="mr-3">
                                                <i class="icon-file-text me-3"></i>
                                            </div>
                                            <div class="media-body">
                                                <a href="{{ asset('uploads/notice/' . $item->file) }}" target="_blank">
                                                    {{ $item->file }}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

    </div>

    @if (auth()->user()->user_type == 'super_admin' ||
            auth()->user()->user_type == 'admin' ||
            auth()->user()->user_type == 'hr' ||
            auth()->user()->user_type == 'division_hr')
        <div class="row">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">List of Organizations</h5>
                        @foreach (json_decode($notice->organization_id) as $organizationId)
                            <p class="card-text">-
                                {{ \App\Modules\Organization\Entities\Organization::where('id', $organizationId)->first()->name }}
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
            @if (!is_null($notice->branch_id))
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">List of Branch</h5>
                            @foreach (json_decode($notice->branch_id) as $key => $branchId)
                                <p class="card-text">-
                                    {{ \App\Modules\Branch\Entities\Branch::where('id', $branchId)->first()->name }}
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if (!is_null($notice->department_id))
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">List of Sub-Functions</h5>
                            @foreach (json_decode($notice->department_id) as $key => $departmentId)
                                <p class="card-text">-
                                    {{ \App\Modules\Setting\Entities\Department::where('id', $departmentId)->first()->title }}
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if (!is_null($notice->employee_id))
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">List of Employees</h5>
                            @foreach (json_decode($notice->employee_id) as $key => $employeeId)
                                <p class="card-text">-
                                    {{ \App\Modules\Employee\Entities\Employee::where('id', $employeeId)->first()->full_name }}
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    @endif


@endsection
