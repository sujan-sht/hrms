@extends('admin::layout')
@section('title') Asset @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Assets</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('asset::asset.partial.advance-filter', ['route' => route('asset.index')])

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Assets</h6>
            All the Assets Information will be listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('asset.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                    <th>Description</th>
                    <th>Created Date</th>
                    <th>Created By</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($assetModels->total() != 0)
                    @foreach ($assetModels as $key => $assetModel)
                        <tr>
                            <td width="5%">#{{ ++$key }}</td>
                            <td>{{ $assetModel->title }}</td>
                            <td>{{ Str::limit($assetModel->description, 50) }}</td>
                            <td>{{ getStandardDateFormat($assetModel->created_at) }}</td>
                            <td>{{ optional($assetModel->user)->full_name }}</td>

                            <td class="d-flex">
                                @if ($menuRoles->assignedRoles('asset.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('asset.edit', $assetModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('asset.delete'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                        link="{{ route('asset.delete', $assetModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Asset Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $assetModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
@endSection
