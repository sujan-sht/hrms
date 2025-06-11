@extends('admin::layout')
@section('title')
    Fiscal Year
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Fiscal Years</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('fiscalyearsetup::fiscalYearSetup.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Fiscal Year</h6>
                All the Fiscal Year Information will be listed below. You can Create and Modify Fiscal Year.
            </div>
            <div class="mt-1">
                <a href="{{ route('fiscalYearSetup.create') }}" class="btn btn-success rounded-pill"><i
                        class="icon-plus2"></i> Add Fiscal Year Setup</a>
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Fiscal Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <!-- <th>Created Date</th> -->
                        <th width="15%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($fiscalYearSetupModels->total() != 0)
                        @foreach ($fiscalYearSetupModels as $key => $fiscalYearSetupModel)
                            <tr>
                                <td width="5%">#{{ $fiscalYearSetupModels->firstItem() + $key }}</td>
                                <td>{{ $fiscalYearSetupModel->fiscal_year }}</td>
                                <td>{{ $fiscalYearSetupModel->start_date }}</td>
                                <td>{{ $fiscalYearSetupModel->end_date }}</td>
                                @php
                                    if ($fiscalYearSetupModel->status == 1) {
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
                                <!-- <td>{{ $fiscalYearSetupModel->created_at ? date('M d, Y', strtotime($fiscalYearSetupModel->created_at)) : '-' }}</td> -->
                                <td class="text-center">
                                    @if ($menuRoles->assignedRoles('leaveType.sync'))
                                        {{-- @if ($fiscalYearSetupModel->start_date_english == date('Y-m-d')) --}}
                                        {{-- && $fiscalYearSetupModel->is_sync ==10 --}}
                                        @if ($fiscalYearSetupModel->status == 1)
                                            <a class="btn btn-outline-info btn-icon mr-1"
                                                href="{{ route('leaveType.sync', $fiscalYearSetupModel->id) }}"
                                                data-popup="tooltip" data-placement="top"
                                                data-original-title="Sync Leave Type">
                                                <i class="icon-spinner9"></i>
                                            </a>
                                        @endif

                                        {{-- @endif --}}
                                    @endif
                                    @if ($menuRoles->assignedRoles('fiscalYearSetup.edit'))
                                        <a class="btn btn-outline-primary btn-icon mr-1"
                                            href="{{ route('fiscalYearSetup.edit', $fiscalYearSetupModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('fiscalYearSetup.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('fiscalYearSetup.delete', $fiscalYearSetupModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Fiscal Year Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $fiscalYearSetupModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection

{{-- @section('script')
    <script src="{{asset('admin/global/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('admin/global/js/plugins/forms/selects/select2.min.js')}}"></script>
@endSection --}}
