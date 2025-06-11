@extends('admin::layout')
@section('title') Leave Type @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Leave Types</a>
@stop

@section('script')
<script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

@endSection

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

@include('leave::leave-type.partial.advance-filter', ['route' => route('leaveType.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Leave Types</h6>
            All the Leave Types Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('leaveType.create') }}" class="btn btn-success rounded-pill">Create New</a>
        </div>
    </div>
</div>

@if (request('leave_year_id'))
    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Organization</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Total Days</th>
                        <th>Leave Type</th>
                        <th>Gender</th>
                        <th>Marital Status</th>
                        <th>Status</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($leaveTypeModels->total() != 0)
                        @foreach ($leaveTypeModels as $key => $leaveTypeModel)
                            <tr>
                                <td width="5%">#{{ $leaveTypeModels->firstItem() + $key }}</td>
                                <td>{{ optional($leaveTypeModel->organization)->name }}</td>
                                <td>
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">{{ $leaveTypeModel->name }}
                                            </div>
                                            <span class="text-muted">Code : {{ $leaveTypeModel->code }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $leaveTypeModel->description }}</td>
                                <td>{{ $leaveTypeModel->number_of_days }}</td>
                                <td>{{ $leaveTypeModel->getLeaveType() }}</td>
                                <td>{{ optional($leaveTypeModel->getGenderInfo)->dropvalue ?? 'All' }}</td>
                                <td>{{ optional($leaveTypeModel->getMaritalStatusInfo)->dropvalue ?? 'All' }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $leaveTypeModel->statusDetail['color'] }}">{{ $leaveTypeModel->statusDetail['title'] }}</span>
                                </td>
                                <td class="d-flex">
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('leaveType.edit', $leaveTypeModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('leaveType.delete', $leaveTypeModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Leave Type Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $leaveTypeModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endif


@endsection
