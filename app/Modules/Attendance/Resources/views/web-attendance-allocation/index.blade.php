@extends('admin::layout')
@section('title') Restriction @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Restrictions</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('employeeRepo', '\App\Modules\Employee\Repositories\EmployeeRepository')


@section('content')


{{-- @include('survey::survey.partial.advance-filter', ['route' => route('survey.index')]) --}}

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Web Attendance Restrictions</h6>
            All the Web Attendance Restrictions Information will be listed below.
        </div>
        @if ($menuRoles->assignedRoles('webAttendance.allocateForm'))
            <div class="mt-1 mr-2">
                <a href="{{ route('webAttendance.allocateForm') }}" class="btn btn-success rounded-pill"><i
                        class="icon-plus2"></i> Add Web Attendance</a>
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
                    <th>Organization</th>
                    <th>Unit</th>
                    <th>Sub-Function</th>
                    <th>Employee</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if (count($webAtdAllocations) != 0)
                    @foreach ($webAtdAllocations as $key => $webAtdAllocation)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td>{{ optional($webAtdAllocation->organization)->name }}</td>
                            <td>{{ optional($webAtdAllocation->branch)->name }}</td>
                            <td>{{ optional($webAtdAllocation->department)->title }}</td>
                            <td>
                                @php
                                    $employees = json_decode($webAtdAllocation->employee_id);
                                @endphp

                                @if (isset($employees) && !empty($employees))
                                    <ul>
                                        @foreach ($employees as $employee_id)
                                            @php
                                                $empModel = $employeeRepo->find($employee_id);
                                            @endphp
                                            <li>{{ $empModel->full_name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No Employee
                                @endif
                            </td>
                            <td>
                                @if ($menuRoles->assignedRoles('webAttendance.editAllocation'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('webAttendance.editAllocation', ['id' => $webAtdAllocation->id]) }}"
                                        data-popup="tooltip" data-original-title="Edit" data-placement="bottom">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('webAttendance.destroyAllocation'))
                                    @if (auth()->user()->user_type == 'admin' ||
                                            auth()->user()->user_type == 'super_admin' ||
                                            auth()->user()->user_type == 'hr')
                                        <a data-toggle="modal" data-target="#modal_theme_warning"
                                            class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                            link="{{ route('webAttendance.destroyAllocation', ['id' => $webAtdAllocation->id]) }}"
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
                        <td colspan="7">No Web Attendance Restriction Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{-- <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $surveyModels->appends(request()->all())->links() }}
        </span>
    </div> --}}
</div>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@endSection
