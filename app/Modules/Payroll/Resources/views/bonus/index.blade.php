@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
{{-- <a class="breadcrumb-item active">Payroll</a> --}}
<a class="breadcrumb-item active">Bonus</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

@include('payroll::bonus.partial.search')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Bonus</h6>
            All the Bonus Information will listed below.
        </div>
        <div class="">
            <a href="{{ route('bonus.create') }}" class="btn btn-danger rounded-pill"><i class="icon-plus3"></i>Create
                New</a>
        </div>
    </div>
</div>

<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>Organization Name</th>
                    <th>Year</th>
                    <th>Month</th>
                    <th width="25%" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($bonusModels->total() != 0)
                    @foreach ($bonusModels as $key => $bonusSetupModel)
                        @php
                            $status = optional(optional($bonusSetupModel->payrollEmployees)->first())->status;
                        @endphp
                        <tr>
                            <td width="5%">#{{ $bonusModels->firstItem() + $key }}</td>
                            <td>
                                <div class="media">
                                    <div class="mr-3">
                                        <img src="{{ optional($bonusSetupModel->organization)->getImage() }}"
                                            class="rounded-circle" width="40" height="40" alt=" ">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ optional($bonusSetupModel->organization)->name }}</div>
                                        <span
                                            class="text-muted">{{ optional($bonusSetupModel->organization)->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $bonusSetupModel->year }}</td>
                            <td>{{ $bonusSetupModel->month_title }}</td>
                            <td class="text-center">

                                <a href="{{ route('bonus.view', $bonusSetupModel->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                    data-popup="tooltip" data-placement="top"
                                    data-original-title="View Payroll Employee ">
                                    <i class="icon-eye"></i>
                                </a>

                                <a href="{{ route('bonus.salary.transfer', $bonusSetupModel->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                    data-popup="tooltip" data-placement="top"
                                    data-original-title="Salary Transfer Letter ">
                                    <i class="icon-envelop3"></i>
                                </a>
                                @if ($menuRoles->assignedRoles('bonus.delete'))
                                    <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete"
                                        link="{{ route('bonus.delete', $bonusSetupModel->id) }}" data-popup="tooltip"
                                        data-placement="top" data-original-title="Delete">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No Record Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $bonusModels->appends(request()->all())->links() }}
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
