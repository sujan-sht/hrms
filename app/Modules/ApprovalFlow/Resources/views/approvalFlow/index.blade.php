@extends('admin::layout')
@section('title')
    Approval Flow
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Approval Flows</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('approvalflow::approvalFlow.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Approval Flow</h6>
                All the Approval Flow Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('approvalFlow.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                    Add Approval Flow</a>
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Sub-Function</th>
                        <th>First Approval User</th>
                        <th>Last Approval User</th>
                        <th>Created Date</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($approvalFlowModels->total() != 0)
                        @foreach ($approvalFlowModels as $key => $approvalFlowModel)
                            <tr>
                                <td width="5%">#{{ $approvalFlowModels->firstItem() + $key }}</td>
                                <td>{{ optional($approvalFlowModel->dropdownInfo)->title }}</td>
                                <td>{{ optional($approvalFlowModel->firstApprovalInfo)->first_name . ' ' }}
                                    {{ optional($approvalFlowModel->firstApprovalInfo)->middle_name . ' ' }}
                                    {{ optional($approvalFlowModel->firstApprovalInfo)->last_name }}</td>
                                <td>{{ optional($approvalFlowModel->lastApprovalInfo)->first_name . ' ' }}{{ optional($approvalFlowModel->lastApprovalInfo)->middle_name . ' ' }}{{ optional($approvalFlowModel->lastApprovalInfo)->last_name }}
                                </td>
                                <td>{{ $approvalFlowModel->created_at ? date('M d, Y', strtotime($approvalFlowModel->created_at)) : '-' }}
                                </td>

                                <td>
                                    @if ($menuRoles->assignedRoles('approvalFlow.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('approvalFlow.edit', $approvalFlowModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('approvalFlow.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('approvalFlow.delete', $approvalFlowModel->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Approval Flow Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $approvalFlowModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endSection
