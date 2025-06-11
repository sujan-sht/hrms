@extends('admin::layout')
@section('title')
    Competency Library
@endSection

@section('breadcrum')
    <a href="{{ route('competenceLibrary.index') }}" class="breadcrumb-item">Competency Library</a>
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
                <h6 class="media-title font-weight-semibold">List of Competency Library</h6>
                All the Competency Library Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('competenceLibrary.create') }}" class="btn btn-success rounded-pill"><i
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
                        <th>Library Title</th>
                        <th>Competency Library</th>
                        @if ($menuRoles->assignedRoles('competenceLibrary.edit'))
                            <th width="12%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if ($competencyLibraries->count() != 0)
                        @foreach ($competencyLibraries as $key => $competencyLibrary)
                            <tr>
                                <td width="5%">#{{ ++$key }}</td>
                                <td>{{ $competencyLibrary->title }}</td>
                                <td>{{ $competencyLibrary->created_at->format('Y-M-d') }}</td>

                                @if ($menuRoles->assignedRoles('competenceLibrary.edit'))
                                    <td>
                                        @if ($menuRoles->assignedRoles('competenceLibrary.edit'))
                                            <a class="btn btn-outline-primary btn-icon mx-1"
                                                href="{{ route('competenceLibrary.edit', $competencyLibrary->id) }}"
                                                data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                                <i class="icon-pencil7"></i>
                                            </a>
                                        @endif
                                        @if ($menuRoles->assignedRoles('competenceLibrary.delete'))
                                            <a class="btn btn-outline-danger btn-icon confirmDelete"
                                                link="{{ route('competenceLibrary.delete', $competencyLibrary->id) }}"
                                                data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                                <i class="icon-trash-alt"></i>
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Data Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $competencyLibraries->appends(request()->all())->links() }}
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
