@extends('admin::layout')
@section('title') Employee Visibility Setup @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Employee Visibility Setup</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('employeevisibilitysetup::employeevisibilitysetup.partial.advance-filter', ['route' => route('assetAllocate.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Employee Visibility Setup</h6>
        </div>
        <div class="mt-1">


        </div>
    </div>
</div>

{{-- @if ($employee->isAttendance())
<p>User has attendance permission.</p>
@endif --}}

<div class="card card-body">

<div class="table-responsive">
    <table class="table table-hover" id="table2excel">
        <thead>
            <tr class="text-light btn-slate">
                <th>S.N</th>
                <th class="text-center">
                    Attendance
                    <br>
                    <input type="checkbox" id="selectAllAttendance"> Select All
                </th>
                <th class="text-center">
                    Leave
                    <br>
                    <input type="checkbox" id="selectAllLeave"> Select All
                </th>
                <th class="text-center">
                    Payroll
                    <br>
                    <input type="checkbox" id="selectAllPayroll"> Select All
                </th>
                <th class="text-center">
                    Approval Flow
                    <br>
                    <input type="checkbox" id="selectAllApprovalFlow"> Select All
                </th>
            </tr>
        </thead>
        <form action="{{route('employeeVisibilitySetup.store')}}" method="post">
            @csrf
            <tbody>
                @foreach ($employees as $employee)

                @php
                    $visibility = App\Modules\EmployeeVisibilitySetup\Entities\EmployeeVisibilitySetup::where('user_id', $employee->id)->first();
                @endphp
                <input type="hidden" name="employee_id[]" value="{{$employee->id}}">

                <tr>
                    <td>{{$employee->full_name}}</td>

                    <td class="text-center">
                        <input type="hidden" name="attendance[{{$employee->id}}]" value="0">
                        <input type="checkbox" value="1" name="attendance[{{$employee->id}}]" class="attendance-checkbox form-control" {{ $visibility && $visibility->attendance == 1 ? 'checked' : '' }}>
                    </td>

                    <td class="text-center">
                        <input type="hidden" name="leave[{{$employee->id}}]" value="0">
                        <input type="checkbox" value="1" name="leave[{{$employee->id}}]" class="leave-checkbox form-control" {{ $visibility && $visibility->leave == 1 ? 'checked' : '' }}>
                    </td>

                    <td class="text-center">
                        <input type="hidden" name="payroll[{{$employee->id}}]" value="0">
                        <input type="checkbox" value="1" name="payroll[{{$employee->id}}]" class="payroll-checkbox form-control" {{ $visibility && $visibility->payroll == 1 ? 'checked' : '' }}>
                    </td>

                    <td class="text-center">
                        <input type="hidden" name="approval_flow[{{$employee->id}}]" value="0">
                        <input type="checkbox" value="1" name="approval_flow[{{$employee->id}}]" class="approval-flow-checkbox form-control" {{ $visibility && $visibility->approval_flow == 1 ? 'checked' : '' }}>
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-right">
                        <input type="submit" class="btn btn-sm btn-primary">
                    </td>
                </tr>
            </tbody>
        </form>
    </table>
</div>

    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
        </span>
    </div>
</div>
<script>
    $('#selectAllAttendance').on('change', function() {
        $('.attendance-checkbox').prop('checked', this.checked);
    });

    $('#selectAllLeave').on('change', function() {
        $('.leave-checkbox').prop('checked', this.checked);
    });

    $('#selectAllPayroll').on('change', function() {
        $('.payroll-checkbox').prop('checked', this.checked);
    });

    $('#selectAllApprovalFlow').on('change', function() {
        $('.approval-flow-checkbox').prop('checked', this.checked);
    });
</script>
@endsection

@section('script')

    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>






@endSection


