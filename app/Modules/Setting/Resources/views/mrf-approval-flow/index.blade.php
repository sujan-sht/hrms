@extends('admin::layout')
@section('title') MRF Approval Flow @stop
@section('breadcrum')
    <a class="breadcrumb-item active">MRF Approval Flow</a>
@endsection

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of MRF Approval Flows</h6>
                All the List of MRF Approval Flows will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('mrfApprovalFlow.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('mrfApprovalFlow.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add</a>
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive ">
                <table class="table table-hover">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th>S.N</th>
                            <th>Organization</th>
                            <th>First Approval</th>
                            <th>Second Approval</th>
                            <th>Third Approval</th>
                            <th>Fourth Approval</th>
                            @if ($menuRoles->assignedRoles('mrfApprovalFlow.edit') || $menuRoles->assignedRoles('mrfApprovalFlow.delete'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($mrfApprovalFlowModels->count() > 0)
                            @foreach ($mrfApprovalFlowModels as $key => $mrfApprovalFlowModel)
                                <tr>
                                    <td>#{{ ++$key }}</td>
                                    <td>{{ optional($mrfApprovalFlowModel->organizationModel)->name }}</td>
                                    <td>{{ optional($mrfApprovalFlowModel->firstApprovalEmployeeModel)->full_name }}</td>
                                    <td>{{ optional($mrfApprovalFlowModel->secondApprovalEmployeeModel)->full_name }}</td>
                                    <td>{{ optional($mrfApprovalFlowModel->thirdApprovalEmployeeModel)->full_name }}</td>
                                    <td>{{ optional($mrfApprovalFlowModel->fourthApprovalEmployeeModel)->full_name }}</td>
                                    @if ($menuRoles->assignedRoles('mrfApprovalFlow.edit') || $menuRoles->assignedRoles('mrfApprovalFlow.delete'))
                                        <td>
                                            @if ($menuRoles->assignedRoles('mrfApprovalFlow.edit'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('mrfApprovalFlow.edit', $mrfApprovalFlowModel->id) }}"
                                                    data-popup="tooltip" data-placement="bottom"
                                                    data-original-title="Edit"><i class="icon-pencil7"></i></a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('mrfApprovalFlow.delete'))
                                                <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                    link="{{ route('mrfApprovalFlow.delete', $mrfApprovalFlowModel->id) }}"
                                                    data-placement="bottom" data-popup="tooltip"
                                                    data-original-title="Delete"><i class="icon-trash-alt"></i></a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Record Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <span style="margin: 5px;float: right;">
                    @if ($mrfApprovalFlowModels->count() != 0)
                        {{ $mrfApprovalFlowModels->links() }}
                    @endif
                </span>
            </div>
        </div>
    </div>

@endsection
