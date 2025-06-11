@extends('admin::layout')
@section('title')
    Template Type
@endSection

@section('breadcrum')
    <a href="{{ route('templateType.index') }}" class="breadcrumb-item">Template Type</a>
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
                <h6 class="media-title font-weight-semibold">List of Template Type</h6>
                All the Template Type Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('templateType.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                    Add</a>
            </div>
        </div>
    </div>

    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Title</th>
                        <th>Slug</th>
                        @if ($menuRoles->assignedRoles('templateType.edit'))
                            <th width="12%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($templateTypes->total() != 0)
                        @foreach ($templateTypes as $key => $templateType)
                            <tr>
                                <td width="5%">#{{ ++$key }}</td>
                                <td>{{ $templateType->title }}</td>
                                <td>{{ $templateType->slug }}</td>

                                {{-- @if ($menuRoles->assignedRoles('cheatSheet.edit')) --}}
                                <td>
                                    @if ($menuRoles->assignedRoles('templateType.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('templateType.edit', $templateType->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('templateType.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('templateType.delete', $templateType->id) }}"
                                            data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                                {{-- @endif --}}
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Template Types Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $templateTypes->appends(request()->all())->links() }}
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
