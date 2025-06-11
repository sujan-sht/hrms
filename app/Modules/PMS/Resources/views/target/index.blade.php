@extends('admin::layout')
@section('title')
    Target
@endSection
@section('breadcrum')
    <a class="breadcrumb-item active">Targets</a>
@endSection

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')

    @include('pms::target.partial.search')
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">List of Target</h6>
                All the Targets Information will be listed below. You can Create and Modify the data.
            </div>
            <div class="mt-1">
                <a href="{{ route('target.create') }}" class="btn btn-success"><i class="icon-plus2"></i>
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
                        <th>KRA</th>
                        <th>KPI</th>
                        <th>Fiscal Year</th>
                        <th>Title</th>
                        <th>Frequency</th>
                        <th>Category</th>
                        <th>Weightage (in %)</th>
                        <th>No. of Quarter</th>
                        <th>Created Date</th>
                        <th width="12%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($targetModels->total() != 0)
                        @foreach ($targetModels as $key => $targetModel)
                            <tr>
                                <td width="5%">#{{ $targetModels->firstItem() + $key }}</td>
                                <td>{{ optional($targetModel->kraInfo)->title }}</td>
                                <td>{{ optional($targetModel->kpiInfo)->title }}</td>
                                <td>{{ optional($targetModel->fiscalYearInfo)->fiscal_year }}</td>
                                <td>{{ $targetModel->title }}</td>
                                <td>{{ $targetModel->frequency }}</td>
                                <td>{{ $targetModel->category }}</td>
                                <td>{{ $targetModel->weightage }}</td>
                                <td>{{ $targetModel->no_of_quarter }}</td>
                                <td>{{ getStandardDateFormat($targetModel->created_at) }}</td>
                                <td class="d-flex">
                                    @if ($menuRoles->assignedRoles('target.setTarget'))
                                        <a class="btn btn-outline-indigo btn-icon mx-1"
                                            href="{{ route('target.setTarget', $targetModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Set Target Value">
                                            <i class="icon-target2"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('target.edit'))
                                        <a class="btn btn-outline-primary btn-icon mx-1"
                                            href="{{ route('target.edit', $targetModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Edit">
                                            <i class="icon-pencil7"></i>
                                        </a>
                                    @endif
                                    @if ($menuRoles->assignedRoles('target.delete'))
                                        <a class="btn btn-outline-danger btn-icon confirmDelete mx-1"
                                            link="{{ route('target.delete', $targetModel->id) }}" data-popup="tooltip"
                                            data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a>
                                    @endif

                                    {{-- @if ($menuRoles->assignedRoles('target.setTargetQuarter'))
                                        <a class="btn btn-outline-indigo btn-icon" href="{{ route('target.setTargetQuarter', $targetModel->id) }}" data-popup="tooltip" data-placement="top" data-original-title="Set Target Quarter">
                                            <i class="icon-target2"></i>
                                        </a>
                                    @endif --}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Target Found !!!</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <span class="float-right pagination align-self-end mt-3">
                {{ $targetModels->appends(request()->all())->links() }}
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
