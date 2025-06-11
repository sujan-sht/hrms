@extends('admin::layout')
@section('title') Leave Overview @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Leave Overview</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

@include('leave::leave-overview.partial.advance-filter', ['route' => route('leave.leaveOverview')])
<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Leave Overview</h6>
            All the Leaves Overview Information will be listed below.
        </div>
        <div class="list-icons mt-2">
            <div class="dropdown position-static">
                <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-more2"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#previous_leave_detail_import">
                        <i class="icon-file-excel text-success"></i> Import
                    </a>
                    <a href="{{ route('leave.exportLeaveOverview', request()->all()) }}#" class="dropdown-item">
                        <i class="icon-file-excel text-success"></i> Export
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@include('leave::leave-overview.partial.upload')


<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Employee</th>
                    <th>Previous Remaining Leave</th>
                    <th>Current Leave Opening</th>
                    <th>Current Leave Taken</th>
                    <th>Current Leave Balance</th>
                </tr>
            </thead>
            <tbody>
                @if (!empty($empLeaveOverviewReports))
                    @foreach ($empLeaveOverviewReports as $key => $empLeaveOverviewReport)
                        <tr>
                            <td width="5%">#{{ $empLeaveOverviewReports->firstItem() + $key }}</td>
                           <td>{{ $empLeaveOverviewReport->full_name }}</td>
                           <td>{{ $empLeaveOverviewReport->previousLeaveRemaining }}</td>
                           <td>{{ $empLeaveOverviewReport->currentLeaveYearLeaveOpening }}</td>
                           <td>{{ $empLeaveOverviewReport->currentLeaveYearLeaveTaken }}</td>
                           <td>{{ $empLeaveOverviewReport->currentLeaveYearLeaveBalance }}</td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Employee Leave Data Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    @if(!empty($empLeaveOverviewReports))
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $empLeaveOverviewReports->appends(request()->all())->links() }}
            </span>
        </div>
    @endif
</div>

@endsection

@section('script')

<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ asset('admin/validation/validation.js') }}"></script>

@endSection
