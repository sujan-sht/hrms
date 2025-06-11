@extends('admin::layout')

@section('title')
    Division Role Setup
@endsection

@section('breadcrum')
    <a href="{{ route('attendanceRequest.index') }}" class="breadcrumb-item">Attendance</a>
    <a class="breadcrumb-item active">Division Role Setup </a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@inject('employeeRepo', '\App\Modules\Employee\Repositories\EmployeeRepository')
@inject('divisionAtdRoleSetup', '\App\Modules\Attendance\Entities\DivisionAttendanceRoleSetup')



@section('content')


    {{-- @include('attendance::attendance-request.partial.filter') --}}
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Division Role Setup</h6>
                All the Division Role Setup Information will be listed below. You can Create and Modify the data.
            </div>
        </div>
    </div>
    {!! Form::open(['route'=>'siteAttendance.storeRoleSetup','method'=>'POST','class'=>'form-horizontal','id'=>'attendanceFormSubmit','role'=>'form','files' => true]) !!}

        <div class="card card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>S.N</th>
                            <th>Organization</th>
                            <th>Attendance Reviewer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 0; @endphp
                        @foreach ($organizationList as $organization)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $organization->name }}</td>
                                {!! Form::hidden($organization->id.'[organization_id]', $organization->id, []) !!}
                                @php
                                    $employeeList = $employeeRepo->getListOrganizationwise($organization->id);
                                    $selectedEmployee = $divisionAtdRoleSetup->where('organization_id', $organization->id)->first();
                                @endphp
                                <td>
                                    {!! Form::select($organization->id.'[reviewer_emp_id]', $employeeList, $selectedEmployee ? $selectedEmployee->reviewer_emp_id : null, [
                                        'class' => 'form-control select-search',
                                        'placeholder' => 'Select Employee'
                                    ]) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
            class="icon-database-insert"></i></b>Save Record</button>
        </div>
    {!! Form::close() !!}


@endsection
