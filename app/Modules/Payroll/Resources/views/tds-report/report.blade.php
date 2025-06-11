@extends('admin::layout')
@section('title') TDS Report @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">TDS Report</a>
@stop

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
@include('payroll::tds-report.partial.advance_filter')

@if (request()->get('organization_id'))
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            {{-- <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                    <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
                </a> --}}
            <div class="media-body text-center text-md-left">
                {{-- <h6 class="media-title font-weight-semibold">Payroll for the  {{ $payrollModel->year }}</h6>
                    <b>Organization :</b> {{ optional($payrollModel->organization)->name }} --}}
            </div>
            <div class="ml-1">
                <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
            </div>
        </div>
    </div>
    <div class="card card-body">
        <table id="table2excel" class="table table-striped">
            <thead>
                <tr class="text-white">
                    <th>S.N</th>
                    <th>Employee Name</th>
                    <th>Employee Code</th>
                    <th>SST</th>
                    <th>TDS</th>
                </tr>

            </thead>
            <tbody>
                @if (isset($payrollModel))
                    @foreach ($payrollModel->payrollEmployees as $key => $payrollEmployee)
                        <tr>
                            <td>{{ '#' . ++$key }}</td>

                            <td>
                                <div class="media">
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ optional($payrollEmployee->employee)->getFullName() }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ optional($payrollEmployee->employee)->employee_code }}</td>
                            <td>{{ $payrollEmployee->sst }}</td>
                            {{-- @php
                                $sst = $payrollEmployee->sst ?? 0;
                            @endphp
                            @if (optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue == 'Single' &&
                                    optional(optional($payrollEmployee->employee)->getGender)->dropvalue == 'Female')
                                <td>Rs. {{ round($sst - 0.1 * $sst, 2) }}</td>
                            @else
                                <td>Rs. {{ round($sst,2) }}</td>
                            @endif --}}
                            <td>{{ $payrollEmployee->tds }}</td>
                            {{-- @php
                                $tds = $payrollEmployee->tds ?? 0;
                            @endphp
                            @if (optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue == 'Single' &&
                                    optional(optional($payrollEmployee->employee)->getGender)->dropvalue == 'Female')
                                <td>Rs. {{ round($tds - 0.1 * $tds, 2) }}</td>
                            @else
                                <td>Rs. {{ round($tds,2) }}</td>
                            @endif --}}
                            


                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>

    </div>
@endif


@endsection
@section('script')
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                // var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "TDS Report",
                    filename: "tds_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
                        "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                    // preserveColors: preserveColors
                });
            }
        });
    });
</script>
@endsection
