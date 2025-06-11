@extends('admin::layout')
@section('title')
    Leave Year
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Leave Years</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
                style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
    @include('leaveyearsetup::leaveYearSetup.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Leave Year</h6>
                All the Leave Year Information will be listed below. You can Create and Modify Leave Year.
            </div>
            <div class="mt-1">
                <a href="{{ route('leaveYearSetup.create') }}" class="btn btn-success rounded-pill">Create New</a>
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Calendar Type</th>
                        <th>Leave Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($leaveYearSetupModels->total() != 0)
                        @foreach ($leaveYearSetupModels as $key => $leaveYearSetupModel)
                            <tr>
                                <td width="5%">#{{ $leaveYearSetupModels->firstItem() + $key }}</td>
                                <td>{{ $leaveYearSetupModel->calender_type == 'nep' ? 'Nepali' : 'English' }}</td>
                                @if ($leaveYearSetupModel->calender_type == 'nep')
                                    <td>{{ $leaveYearSetupModel->leave_year }}</td>
                                    <td>{{ $leaveYearSetupModel->start_date }}</td>
                                    <td>{{ $leaveYearSetupModel->end_date }}</td>
                                @else
                                    <td>{{ $leaveYearSetupModel->leave_year_english }}</td>
                                    <td>{{ $leaveYearSetupModel->start_date_english }}</td>
                                    <td>{{ $leaveYearSetupModel->end_date_english }}</td>
                                @endif

                                @php
                                    if ($leaveYearSetupModel->status == 1) {
                                        $status = 'Active';
                                        $color = 'success';
                                    } else {
                                        $status = 'In-Active';
                                        $color = 'danger';
                                    }
                                @endphp
                                <td>
                                    <span class="badge badge-{{ $color }}">{{ $status }}</span>
                                </td>
                                <td class="text-center">
                                    {{-- @if ($menuRoles->assignedRoles('leaveType.sync'))

                                        @if ($leaveYearSetupModel->status == 1)
                                            <a class="btn btn-outline-info btn-icon mr-1" href="{{ route('leaveType.sync', $leaveYearSetupModel->id) }}" data-popup="tooltip" data-placement="top" data-original-title="Sync Leave Type">
                                                <i class="icon-spinner9"></i>
                                            </a>
                                        @endif
                                    @endif --}}
                                    @if ($menuRoles->assignedRoles('leaveYearSetup.edit'))
                                        <a class="btn btn-outline-primary btn-icon mr-1"
                                            href="{{ route('leaveYearSetup.edit', $leaveYearSetupModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('leaveYearSetup.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('leaveYearSetup.delete', $leaveYearSetupModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Leave Year Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $leaveYearSetupModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection
