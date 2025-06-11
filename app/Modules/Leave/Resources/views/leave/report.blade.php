@extends('admin::layout')
@section('title') Leave Report @stop
@section('breadcrum')
    <a href="{{ route('leave.index') }}" class="breadcrumb-item">Leave</a>
    <a class="breadcrumb-item active">Report</a>
@stop
@section('script')
    <script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

@endsection

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

    @include('leave::leave.partial.report-advance-filter')

    {{-- @if (request()->get('organization_id')) --}}

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Employee Leave Report</h6>
                All the Employee Leave Report Information will be listed below.
            </div>
            <div class="mt-1">
                <a href="{{ route('leave.exportLeaveReport', request()->all()) }}" class="btn btn-success rounded-pill"><i
                        class="icon-file-excel"></i> Export</a>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        @if (!empty($leaveTypeList))
                            @foreach ($leaveTypeList as $leaveType)
                                <th>{{ $leaveType }}</th>
                            @endforeach
                        @endif
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                    @if (count($models) > 0)
                        @php $loopCount = 1; @endphp
                        @foreach ($models as $employeeId => $subModels)
                            <tr>
                                <td width="5%">#{{ $loopCount++ }}</td>
                                @php $employeeModel = App\Modules\Employee\Entities\Employee::getDetail($employeeId); @endphp
                                <td> {{ $employeeModel->employee_code }}</td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <a href="#">
                                                <img src="{{ $employeeModel->getImage() }}" class="rounded-circle"
                                                    width="40" height="40" alt="">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ $employeeModel->getFullName() }}</div>
                                            <span class="text-muted">{{ $employeeModel->official_email }}</span>
                                        </div>
                                    </div>
                                </td>
                                @php $totalLeave = 0; @endphp
                                @foreach ($leaveTypeList as $id => $leaveType)
                                    @php
                                        $count = 0;
                                        $rejectedModalDays = 0;
                                    @endphp
                                    @foreach ($subModels as $leaveTypeId => $model)
                                        @php
                                            $rejectedDays = 0;
                                            if ($leaveTypeId == $id) {
                                                foreach ($model as $k => $v) {
                                                    if ($v['status'] != 4) {
                                                        $count += $v['day'];
                                                    }
                                                }
                                                $totalLeave += $count;
                                            }
                                        @endphp
                                    @endforeach
                                    <td>
                                        <h5 class="text-secondary mt-2">{{ $count }} </h5>
                                    </td>
                                @endforeach
                                <td>
                                    <h4 class="mt-2">{{ $totalLeave }}</h4>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="{{ count($leaveTypeList) + 3 }}">No Record Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    {{-- @endif --}}

@endsection
