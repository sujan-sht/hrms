@extends('admin::layout')
@section('title')Request Type @stop

@section('breadcrum')
    <a href="{{ route('employeerequest.index') }}" class="breadcrumb-item">Request Type </a>
    <a class="breadcrumb-item active"> List </a>
@endsection
@section('script')
    <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@stop

@section('content')

    @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Requests</h6>

            </div>
            @if ($menuRoles->assignedRoles('employeeRequestType.create'))
                <div class="mt-1 mr-2">
                    <a href="{{ route('employeeRequestType.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                        Add</a>
                </div>
            @endif

        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">List of Request Type</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>#</th>
                        <th>Title</th>
                        <th>Status</th>
                        @if ($menuRoles->assignedRoles('employeeRequestType.edit') || $menuRoles->assignedRoles('employeeRequestType.delete'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($requestTypes->total() > 0)
                        @foreach ($requestTypes as $type)
                            <tr>
                                <td>{{ ++$loop->index }}</td>
                                <td>{{ $type->title }}</td>
                                <td class="text-teal">
                                    <span data-popup="tooltip"
                                        data-original-title="{{ $type->status ? 'Active' : 'In-Active' }}"
                                        class="btn btn-outline btn-icon {{ $type->status ? 'bg-success text-success border-success' : 'bg-danger text-danger border-danger' }} border-2 rounded-round">
                                        <i
                                            class="text-white {{ $type->status ? 'icon-checkmark-circle2' : 'icon-cross2' }}"></i>
                                    </span>
                                </td>
                                @if ($menuRoles->assignedRoles('employeeRequestType.edit') || $menuRoles->assignedRoles('employeeRequestType.delete'))
                                    <td>
                                        @if ($menuRoles->assignedRoles('employeeRequestType.edit'))
                                            <a class="btn btn-outline-secondary btn-icon mx-1"
                                                href="{{ route('employeeRequestType.edit', $type->id) }}"
                                                data-popup="tooltip" data-placement="bottom" data-original-title="Edit"><i
                                                    class="icon-pencil7"></i></a>
                                        @endif
                                        @if ($menuRoles->assignedRoles('employeeRequestType.delete'))
                                            <a class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                                link="{{ route('employeeRequestType.delete', $type->id) }}"
                                                data-placement="bottom" data-popup="tooltip" data-original-title="Delete"><i
                                                    class="icon-trash-alt"></i></a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>No Data Found!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <span style="margin: 5px;float: right;">
                @if ($requestTypes->total() != 0)
                    {{ $requestTypes->links() }}
                @endif
            </span>
        </div>
    </div>

@endsection
