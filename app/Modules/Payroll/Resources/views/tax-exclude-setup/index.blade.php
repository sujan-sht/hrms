@extends('admin::layout')
@section('title') Tax Exclude Setup @endSection
@section('breadcrum')
<a class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">Tax Exclude Setup</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

@include('payroll::tax-exclude-setup.partial.advance-filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Tax Exclude Setup</h6>
            All the Tax Exclude Setup Information will listed below. You can Create and Modify the data.
        </div>
        <div class="mt-1">
            <a href="{{ route('taxExcludeSetup.create') }}" class="btn btn-success rounded-pill">Create New</a>
        </div>
    </div>
</div>

<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>S.N</th>
                    <th>Short Name</th>
                    <th>Organization Name</th>
                    <th>Title</th>
                    <th>Order</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($taxExcludeSetupModels->total() != 0)
                    @foreach ($taxExcludeSetupModels as $key => $taxExcludeSetupModel)
                        <tr>
                            <td width="5%">#{{ $taxExcludeSetupModels->firstItem() + $key }}</td>
                            <td>{{ $taxExcludeSetupModel->short_name }}</td>
                            <td>{{ optional($taxExcludeSetupModel->organization)->name }}</td>
                            <td>{{ $taxExcludeSetupModel->title }}</td>
                            {{-- <td>{{ $taxExcludeSetupModel->getMethod()['method'] }}</td> --}}
                            <td>{{ $taxExcludeSetupModel->order }}</td>
                            <td>{{ $taxExcludeSetupModel->getType()['type'] }}</td>
                            <td><span class="badge badge-{{ $taxExcludeSetupModel->getStatusWithColor()['color'] }}">{{ $taxExcludeSetupModel->getStatusWithColor()['status'] }}</span>
                            <td>
                                @if ($menuRoles->assignedRoles('taxExcludeSetup.edit'))
                                    <a class="btn btn-outline-primary btn-icon mx-1"
                                        href="{{ route('taxExcludeSetup.edit', $taxExcludeSetupModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Edit">
                                        <i class="icon-pencil7"></i>
                                    </a>
                                @endif

                                @if ($menuRoles->assignedRoles('taxExcludeSetup.destroy'))
                                    <a class="btn btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('taxExcludeSetup.destroy', $taxExcludeSetupModel->id) }}"
                                        data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Tax Exclude Setup Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $taxExcludeSetupModels->appends(request()->all())->links() }}
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
