@extends('admin::layout')
@section('title') Clearances @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Clearances</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')


@include('offboarding::clearance.partial.advance_filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Clearances</h6>
            All the OffBoard Clearance Information will be listed below. You can Create and Modify the data.
        </div>
        @if ($menuRoles->assignedRoles('clearance.create'))
            <div class="mt-1">
                <a href="{{ route('clearance.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
                    Add</a>
            </div>
        @endif
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
                    <th width="12%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($clearanceModels->total() != 0)
                    @foreach ($clearanceModels as $key => $clearanceModel)
                        <tr>
                            <td width="5%">#{{ $clearanceModels->firstItem() + $key }}</td>
                            <td>{{ $clearanceModel->title }}</td>
                            <td>{{ $clearanceModel->description }}</td>

                            <td>
                                <a class="btn btn-sm btn-outline-secondary btn-icon"
                                    href="{{ route('clearance.view', $clearanceModel->id) }}" data-popup="tooltip"
                                    data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if ($menuRoles->assignedRoles('clearance.edit'))
                                    <a class="btn btn-sm btn-outline-primary btn-icon mx-1"
                                        href="{{ route('clearance.edit', $clearanceModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif
                                @if ($menuRoles->assignedRoles('clearance.delete'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('clearance.delete', $clearanceModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{-- {{ $clearanceModels->appends(request()->all())->links() }} --}}
        </span>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
@endSection
