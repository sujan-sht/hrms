@extends('admin::layout')
@section('title') Monthly Leave Report @stop
@section('breadcrum')
    <a href="{{ route('leave.index') }}" class="breadcrumb-item">Leave</a>
    <a class="breadcrumb-item active">Monthly Report</a>
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
    @include('leave::report.partial.monthly-report-filter')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Employee Leave Report Month Wise</h6>
                All the Employee Leave Report Information will be listed below.
            </div>
            <div class="mt-1">
                <a href="{{ route('leave.exportMonthlyLeaveReport', request()->all()) }}" class="btn btn-success rounded-pill"><i
                        class="icon-file-excel"></i> Export</a>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="zui-wrapper">

            <div class="table-responsive1 zui-scroller">
                <table class="table table-striped zui-table">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th class="zui-sticky-col" style="padding: 10px;">S.N</th>
                            <th class="zui-sticky-col2" style="padding: 10px;">Employee Name</th>
                            @if (!empty($monthLists))
                                @foreach ($monthLists as $monthList)
                                    <th>{{ $monthList }}</th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @if (count($employeeLeaveMonths) > 0)
                            @php $loopCount = 1; @endphp
                            @foreach ($employeeLeaveMonths as $employeeId => $subModels)
                                <tr>
                                    <td width="5%" class="zui-sticky-col" style="padding: 1.19rem;">#{{ $loopCount++ }}
                                    </td>
                                    <td class="zui-sticky-col2" style="padding: 1.19rem;">
                                        {{-- @php $employeeModel = App\Modules\Employee\Entities\Employee::getDetail($employeeId); @endphp --}}
                                        <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ $subModels->getImage() }}" class="rounded-circle"
                                                        width="40" height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ $subModels->getFullName() }}</div>
                                                <span class="text-muted">{{ $subModels->official_email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach ($subModels['month_leave'] as $id => $month_leave)
                                        <td>
                                            <h5 class="text-secondary mt-2">{{ $month_leave }}</h5>
                                        </td>
                                    @endforeach

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                {{-- <td colspan="{{ count($leaveTypeList) + 3 }}">No Record Found !!!</td> --}}
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <span class="float-right pagination align-self-end mt-3">
                    {{ $employeeLeaveMonths->appends(request()->all())->links() }}
                </span>
            </div>
        </div>
        {{-- @endif --}}

    @endsection
