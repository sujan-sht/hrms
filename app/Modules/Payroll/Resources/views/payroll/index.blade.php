@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Payroll</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>

@include('payroll::payroll.partial.advance_filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Payroll</h6>
            All the payroll Information will listed below.
        </div>
        <div class="">
            <a href="{{ route('payroll.create') }}" class="btn btn-danger rounded-pill"><i class="icon-plus3"></i>Create
                New</a>
        </div>
        {{-- <div class="ml-2">
                <a href="{{ route('payroll.log.report') }}" class="btn btn-primary rounded-pill"><i class="icon-eye"></i>View Log</a>
            </div> --}}
        <div class="ml-2">
            <a href="{{ route('payroll.ssf.report') }}" class="btn btn-primary rounded-pill"><i
                    class="icon-eye"></i>View Benefit Report</a>
        </div>
        <div class="ml-1">
            <a href="{{ route('payroll.tds.report') }}" class="btn btn-success rounded-pill"><i
                    class="icon-eye"></i>View TDS Report</a>
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
                @if ($payrollModels->total() != 0)
                    @foreach ($payrollModels as $key => $payrollModel)
                        @php
                            $status = optional(optional($payrollModel->payrollEmployees)->first())->status;
                            // dd($status);
                        @endphp
                        <tr>
                            <td width="5%">#{{ $payrollModels->firstItem() + $key }}</td>
                            <td>
                                <div class="media">
                                    <div class="mr-3">
                                        <img src="{{ optional($payrollModel->organization)->getImage() }}"
                                            class="rounded-circle" width="40" height="40" alt=" ">
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ optional($payrollModel->organization)->name }}</div>
                                        <span
                                            class="text-muted">{{ optional($payrollModel->organization)->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $payrollModel->year }}</td>
                            <td>{{ $payrollModel->month_title }}</td>
                            <td class="text-center">
                                <a href="{{ route('payroll.view', $payrollModel->id) }}"
                                    class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                    data-popup="tooltip" data-placement="top" data-original-title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                @if ($status == 2)
                                    <a href="{{ route('payroll.viewResignedEmployee', $payrollModel->id) }}"
                                        class="btn btn-sm btn-outline-danger btn-icon updateStatus mr-1"
                                        data-popup="tooltip" data-placement="top"
                                        data-original-title="View Resigned Employee">
                                        <i class="icon-eye"></i>
                                    </a>

                                    <a href="{{ route('payroll.view.employee', $payrollModel->id) }}"
                                        class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                        data-popup="tooltip" data-placement="top"
                                        data-original-title="View Payroll Employee ">
                                        <i class="icon-history"></i>
                                    </a>


                                    {{-- <a href="{{ route('payroll.salary.transfer', $payrollModel->id) }}" class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1" data-popup="tooltip" data-placement="top" data-original-title="Download Salary Deposit Excel ">
                                            <i class="icon-download"></i>
                                        </a> --}}

                                    <a href="{{ route('payroll.salary.transfer', $payrollModel->id) }}"
                                        class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                        data-popup="tooltip" data-placement="top"
                                        data-original-title="Salary Transfer Letter ">
                                        <i class="icon-envelop3"></i>
                                    </a>
                                    <a href="{{ route('payroll.hold.payment', $payrollModel->id) }}"
                                        class="btn btn-sm btn-outline-secondary btn-icon updateStatus mr-1"
                                        data-popup="tooltip" data-placement="top" data-original-title="Hold Payment">
                                        <i class="icon-lock2"></i>
                                    </a>

                                    @if ($menuRoles->assignedRoles('payroll.departmentwiseReport'))
                                        <a href="{{ route('payroll.departmentwiseReport', $payrollModel->id) }}"
                                            class="btn btn-sm btn-outline-info btn-icon mr-1" data-popup="tooltip"
                                            data-placement="top" data-original-title="Sub-Functionwise Report">
                                            <i class="icon-file-text"></i>
                                        </a>
                                    @endif

                                    @if ($menuRoles->assignedRoles('payroll.irdReport'))
                                        <a href="{{ route('payroll.irdReport', $payrollModel->id) }}"
                                            class="btn btn-sm btn-outline-primary btn-icon mr-1" data-popup="tooltip"
                                            data-placement="top" data-original-title="IRD Report">
                                            <i class="icon-file-text"></i>
                                        </a>
                                    @endif
                                @endif
                                @if ($menuRoles->assignedRoles('payroll.delete'))
                                    <a data-toggle="modal" data-target="#modal_theme_warning"
                                        class="btn btn-outline-danger btn-icon mx-1 confirmDelete"
                                        link="{{ route('payroll.delete', $payrollModel->id) }}" data-popup="tooltip"
                                        data-original-title="Delete" data-placement="bottom">
                                        <i class="icon-trash-alt"></i>
                                    </a>
                                    {{-- <a class="btn btn-sm btn-outline-danger btn-icon confirmDelete" link="{{route('payroll.delete',$payrollModel->id)}}" data-popup="tooltip" data-placement="top" data-original-title="Delete">
                                            <i class="icon-trash-alt"></i>
                                        </a> --}}
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
            {{ $payrollModels->appends(request()->all())->links() }}
        </span>
    </div>
</div>
{{--
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            Are you sure you want to delete this item? This action cannot be undone.
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <a href="#" id="confirmDeleteButton" class="btn btn-danger">Yes, delete it!</a>
            </div>
        </div>
        </div>
    </div> --}}


@endsection

@section('script')
<script src="{{ asset('admin/global/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_inputs.js') }}"></script>
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>

{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        let deleteUrl = "";
        document.querySelectorAll(".confirmDelete").forEach(function (button) {
            button.addEventListener("click", async function (event) {
                event.preventDefault();
                deleteUrl = this.getAttribute("link");
                new bootstrap.Modal(document.getElementById('deleteModal')).show();
            });
        });

        document.getElementById("confirmDeleteButton").addEventListener("click", function () {
            window.location.href = deleteUrl;
        });
    });

</script> --}}

@endSection
