@extends('admin::layout')
@section('title')
    Unit
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Units</a>
@endsection

@section('script')
    <script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@endSection

@section('content')


    @include('unit::unit.partial.advance-search')

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Units</h6>
                All the Units Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('unit.create') }}" class="btn btn-success"><i class="icon-plus2"></i> Add</a>
            </div>
            <div class="mt-1">
                <a href="{{ route('unit.export', request()->all()) }}" class="btn btn-primary rounded-pill">Export</a>
            </div>
            <div class="list-icons mt-2">
                <div class="dropdown position-static">
                    <a href="#" class="list-icons-item" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-more2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal_default_import">
                            <i class="icon-file-excel text-success"></i> Import
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('unit::unit.partial.upload')
    <div class="card card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Unit Name</th>
                        <th>Organization</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($unitModels->total() != 0)
                        @foreach ($unitModels as $key => $unitModel)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{{ $unitModel->title }}</td>
                                <td>{{ @$unitModel->organization->name }}</td>
                                <td>{{ @$unitModel->branch->name }}</td>
                                <td>{{ $unitModel->status == '1' ? 'Active' : 'In-Active' }}</td>
                                <td class="d-flex">
                                    <a class="btn btn-outline-primary btn-icon mx-1"href="{{ route('unit.edit', $unitModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                    <a class="btn btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('unit.delete', $unitModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No Units Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $unitModels->appends(request()->all())->links() }}
            </span>
        </div>
    </div>

@endsection
