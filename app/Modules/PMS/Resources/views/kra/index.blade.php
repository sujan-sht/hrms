@extends('admin::layout')
@section('title')
    KRA
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">KRAs</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('pms::kra.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of KRA</h6>
                All the KRAs Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('kra.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>

                <a href="{{ route('kra.downloadSheet', request()->all()) }}" class="text-light btn bg-indigo mr-1"
                    target="_blank" data-popup="tooltip" data-original-title="Download KRA" data-placement="top"><i
                        class="icon-file-excel"></i></a>
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
                        <th>Sub-Function</th>
                        <th>Organization</th>
                        <th>Created Date</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($kraModels->total() != 0)
                        @foreach ($kraModels as $key => $kraModel)
                            <tr>
                                <td width="5%">#{{ $kraModels->firstItem() + $key }}</td>
                                <td>{{ $kraModel->title }}</td>
                                <td>{{ optional($kraModel->department)->title }}</td>
                                <td>{{ optional($kraModel->organization)->name }}</td>
                                <td>{{ getStandardDateFormat($kraModel->created_at) }}</td>

                                <td>
                                    @if ($menuRoles->assignedRoles('kra.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('kra.edit', $kraModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('kra.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete"
                                            link="{{ route('kra.delete', $kraModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No KRA Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $kraModels->appends(request()->all())->links() }}
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
