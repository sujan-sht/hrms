@extends('admin::layout')
@section('title') Allocation @endSection
@section('breadcrum')
<a href="{{ route('geoFence.index') }}" class="breadcrumb-item">GeoFence</a>
<a class="breadcrumb-item active">Allocations</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('employeeRepo', '\App\Modules\Employee\Repositories\EmployeeRepository')


@section('content')


<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Allocations</h6>
            All the Allocations Information will be listed below.
        </div>
        @if ($menuRoles->assignedRoles('geoFence.allocateForm'))
            <div class="mt-1 mr-2">
                <a href="{{ route('geoFence.allocateForm', $geofence_id) }}" class="btn btn-success rounded-pill">Create
                    New</a>
            </div>
        @endif
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>GeoFence</th>
                    <th>Organization</th>
                    <th>Branch</th>
                    <th>Sub-Function</th>
                    <th>Employee</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($geofenceAllocations->isNotEmpty())
                    @foreach ($geofenceAllocations as $key => $geofenceAllocation)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td>{{ optional($geofenceAllocation->geofence)->title }}</td>
                            <td>{{ optional($geofenceAllocation->organization)->name }}</td>
                            <td>{{ optional($geofenceAllocation->branch)->name }}</td>
                            <td>{{ optional($geofenceAllocation->department)->title }}</td>
                            <td>
                                @php
                                    $employees = json_decode($geofenceAllocation->employee_id);
                                @endphp

                                @if (isset($employees) && !empty($employees) && !is_null($employees))
                                    <ul>
                                        @foreach ($employees as $employee_id)
                                            @php
                                                $employeeName = '';
                                                $empModel = $employeeRepo->find($employee_id);
                                                if ($empModel) {
                                                    $employeeName = $empModel->full_name;
                                                } else {
                                                    $employeeName = '-';
                                                }
                                            @endphp
                                            <li>{{ $employeeName }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    All Employees
                                @endif
                            </td>
                            <td>
                                @if ($menuRoles->assignedRoles('geoFence.editAllocation'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('geoFence.editAllocation', ['geofence_id' => $geofence_id, 'id' => $geofenceAllocation->id]) }}"
                                        data-popup="tooltip" data-original-title="Edit" data-placement="bottom">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('geoFence.destroyAllocation'))
                                    @if (auth()->user()->user_type == 'admin' ||
                                            auth()->user()->user_type == 'super_admin' ||
                                            auth()->user()->user_type == 'hr')
                                        <a data-toggle="modal" data-target="#modal_theme_warning"
                                            class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                            link="{{ route('geoFence.destroyAllocation', ['geofence_id' => $geofence_id, 'id' => $geofenceAllocation->id]) }}"
                                            data-popup="tooltip" data-original-title="Delete" data-placement="bottom">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No GeoFence Allocation Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@endSection
