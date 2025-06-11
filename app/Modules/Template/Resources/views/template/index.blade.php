@extends('admin::layout')
@section('title')
    Template
@endSection

@section('breadcrum')
    <a href="{{ route('template.index') }}" class="breadcrumb-item">Templates</a>
    <a class="breadcrumb-item active">List</a>
@endsection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('template::template.partial.advance-filter')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Templates</h6>
                All the Templates Information will be listed below. You can Create and Modify the data.
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
                        <th>Template Slug</th>
                        <th>Template Text</th>
                        @if ($menuRoles->assignedRoles('template.edit'))
                            <th width="20%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($templateTypes->total() != 0)
                        @foreach ($templateTypes as $key => $templateType)
                            <tr>
                                {{-- <td width="5%">#{{ $templateTypes->firstItem() +$key }}</td> --}}
                                <td width="5%">#{{ ++$key }}</td>
                                <td>{{ $templateType->title }}</td>
                                <td>{{ $templateType->slug }}</td>
                                <td>{!! Str::limit(strip_tags(optional($templateType->template)->text), 100) ??
                                    '<span class="text-danger">Not Created</span>' !!}</td>

                                {{-- @if ($menuRoles->assignedRoles('cheatSheet.edit')) --}}
                                <td class="d-flex">
                                    @if ($menuRoles->assignedRoles('template.create') && !in_array($templateType->id, $existingTemplateTypes))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('template.create', $templateType->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Create">
                                            <i class="icon-plus-circle2"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('template.edit') && in_array($templateType->id, $existingTemplateTypes))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('template.edit', $templateType->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('template.show') && in_array($templateType->id, $existingTemplateTypes))
                                        <a class="btn btn-outline-secondary btn-icon mx-1" target="_blank"
                                            href="{{ route('template.show', $templateType->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="View">
                                            <i class="icon-eye"></i>
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
