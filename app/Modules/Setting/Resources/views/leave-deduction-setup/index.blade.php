@extends('admin::layout')
@section('title') Leave Deduction Setup @stop
@section('breadcrum')
    <a class="breadcrumb-item active">Leave Deduction Setup</a>
@endsection

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Leave Deduction Setups</h6>
                All the List of Leave Deduction Setups will be listed below. You can Create and Modify the data.
            </div>
            @if ($menuRoles->assignedRoles('leaveDeductionSetup.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('leaveDeductionSetup.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                            <th>Type</th>
                            <th>Number of Leave Deduction</th>
                            <th>Leave Type</th>
                            <th>Deduction Method</th>
                            <th>Max Late Attendance</th>
                            @if ($menuRoles->assignedRoles('leaveDeductionSetup.edit') || $menuRoles->assignedRoles('leaveDeductionSetup.delete'))
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($leaveDeductionSetupModels->count() > 0)
                            @foreach ($leaveDeductionSetupModels as $key => $leaveDeductionSetupModel)
                                <tr>
                                    <td>#{{ ++$key }}</td>
                                    <td>{{ optional($leaveDeductionSetupModel->organizationModel)->name }}</td>
                                    <td>{{ $leaveDeductionSetupModel->type_title }}</td>
                                    <td>{{ $leaveDeductionSetupModel->deduct_leave_number }}</td>
                                    <td>{{ optional($leaveDeductionSetupModel->leaveTypeModel)->name }}</td>
                                    <td>{{ $leaveDeductionSetupModel->method_title ?? $leaveDeductionSetupModel->method_title }}
                                    </td>
                                    <td>{{ $leaveDeductionSetupModel->max_late_days }}</td>
                                    @if ($menuRoles->assignedRoles('leaveDeductionSetup.edit') || $menuRoles->assignedRoles('leaveDeductionSetup.delete'))
                                        <td>
                                            @if ($menuRoles->assignedRoles('leaveDeductionSetup.edit'))
                                                <a class="btn btn-outline-primary btn-icon mx-1"
                                                    href="{{ route('leaveDeductionSetup.edit', $leaveDeductionSetupModel->id) }}"
                                                    data-popup="tooltip" data-placement="bottom"
                                                    data-original-title="Edit"><i class="icon-pencil7"></i></a>
                                            @endif
                                            @if ($menuRoles->assignedRoles('leaveDeductionSetup.delete'))
                                                <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                    link="{{ route('leaveDeductionSetup.delete', $leaveDeductionSetupModel->id) }}"
                                                    data-placement="bottom" data-popup="tooltip"
                                                    data-original-title="Delete"><i class="icon-trash-alt"></i></a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Device Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{-- <span style="margin: 5px;float: right;">
                    @if ($leaveDeductionSetupModels->count() != 0)
                        {{ $leaveDeductionSetupModels->links() }}
                    @endif
                </span> --}}
            </div>
        </div>
    </div>

@endsection
