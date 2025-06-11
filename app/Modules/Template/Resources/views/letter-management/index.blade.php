@extends('admin::layout')
@section('title')
    Letter Management
@endSection

@section('breadcrum')
    <a href="{{ route('templateType.index') }}" class="breadcrumb-item">Letter Management</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Letter Management</h6>
                All the Letter Management Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('letterManagement.create') }}" class="btn btn-success rounded-pill"><i
                        class="icon-plus2"></i> Add</a>
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee</th>
                        <th>Letter Type</th>
                        @if (
                            $menuRoles->assignedRoles('letterManagement.edit') ||
                                $menuRoles->assignedRoles('letterManagement.show') ||
                                $menuRoles->assignedRoles('letterManagement.destroy'))
                            <th width="12%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($letters->total() != 0)
                        @foreach ($letters as $key => $letter)
                            <tr>
                                <td width="5%">#{{ ++$key }}</td>
                                <td>{{ $letter->employee->full_name }}</td>
                                <td>{{ $letter->type }}</td>
                                <td>
                                    @if ($menuRoles->assignedRoles('letterManagement.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('letterManagement.edit', $letter->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('letterManagement.show'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('letterManagement.show', $letter->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Show {{ $letter->type }}">
                                            <i class="icon-eye"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('letterManagement.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('letterManagement.delete', $letter->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Letter Managements Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $letters->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

@endsection

@section('script')
    {{-- <script src="{{ asset('admin/global/js/plugins/tables/datatables/datatables.min.js') }}"></script> --}}

    <script src="{{ asset('admin/validation/letter-management.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
    <script src="{{ asset('admin/global/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
    <script src="{{ asset('admin/global/js/demo_pages/form_multiselect.js') }}"></script>
@endSection
