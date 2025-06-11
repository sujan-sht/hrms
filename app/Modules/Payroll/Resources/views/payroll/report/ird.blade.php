@extends('admin::layout')
@section('title')
    IRD Report
@endSection
@section('breadcrum')
    <a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
    <a class="breadcrumb-item active">IRD Report</a>
@endsection

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
            </a>
            <div class="media-body text-center text-md-left">
                <h6 class="media-title font-weight-semibold">Payroll for the month of {{ $payrollModel->month_title }},
                    {{ $payrollModel->year }}</h6>
                <b>Organization :</b> {{ optional($payrollModel->organization)->name }}
            </div>
            <div class="ml-1">
                <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <table id="table2excel" class="table table-striped table-bordered">
            <thead>
                <tr class="text-white">
                    <th>S.N</th>
                    <th>Employee Name</th>
                    <th>PAN Number</th>
                    <th>Payment Date</th>
                    <th>Tax</th>
                    <th>Tax amount</th>
                    <th>Code</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $num = 0;
                    // $sst = 0;
                @endphp
                @if (count($payrollEmployeeDetails) > 0)
                    @foreach ($payrollEmployeeDetails as $key => $payrollEmployeeModel)
                        <tr>
                            <td>{{ ++$num }}</td>
                            <td>{{ optional($payrollEmployeeModel->employee)->full_name }}</td>
                            <td>{{ optional($payrollEmployeeModel->employee)->pan_no }}</td>
                            <td>{{ date('Y.m.d', strtotime($payrollEmployeeModel->updated_at)) }}</td>
                            @if (optional(optional($payrollEmployeeModel->employee)->getMaritalStatus)->dropvalue == 'Single')
                                @if ($payrollEmployeeModel->yearly_taxable_salary > 500000)
                                    <td>500000</td>
                                @else
                                    <td>{{ $payrollEmployeeModel->yearly_taxable_salary }}</td>
                                @endif
                            @else
                                @if ($payrollEmployeeModel->yearly_taxable_salary > 600000)
                                    <td>600000</td>
                                @else
                                    <td>{{ $payrollEmployeeModel->yearly_taxable_salary }}</td>
                                @endif
                            @endif
                            {{-- @php
                                $sst = $payrollEmployeeModel->sst ?? 0;
                            @endphp
                            @if (optional(optional($payrollEmployeeModel->employee)->getMaritalStatus)->dropvalue == 'Single' &&
                                    optional(optional($payrollEmployeeModel->employee)->getGender)->dropvalue == 'Female')
                                <td>Rs. {{ number_format(($sst - 0.1 * $sst),2) }}</td>
                            @else
                                <td>Rs. {{ number_format($sst,2) }}</td>
                            @endif --}}
                            <td>Rs. {{ number_format(($payrollEmployeeModel->sst),2) }}</td>
                            <td>33</td>
                            <th>SST</th>
                        </tr>
                        <tr>
                            <td>{{ ++$num }}</td>
                            <td>{{ optional($payrollEmployeeModel->employee)->full_name }}</td>
                            <td>{{ optional($payrollEmployeeModel->employee)->pan_no }}</td>
                            <td>{{ date('Y.m.d', strtotime($payrollEmployeeModel->updated_at)) }}</td>
                            @if (optional(optional($payrollEmployeeModel->employee)->getMaritalStatus)->dropvalue == 'Single')
                                @if ($payrollEmployeeModel->yearly_taxable_salary > 500000)
                                    <td>{{ $payrollEmployeeModel->yearly_taxable_salary - 500000 }}</td>
                                @else
                                    <td>0</td>
                                @endif
                            @else
                                @if ($payrollEmployeeModel->yearly_taxable_salary > 600000)
                                    <td>{{ $payrollEmployeeModel->yearly_taxable_salary - 600000 }}</td>
                                @else
                                    <td>0</td>
                                @endif
                            @endif
                            {{-- @php
                                $tds = $payrollEmployeeModel->tds ?? 0;
                            @endphp
                            @if (optional(optional($payrollEmployeeModel->employee)->getMaritalStatus)->dropvalue == 'Single' &&
                                    optional(optional($payrollEmployeeModel->employee)->getGender)->dropvalue == 'Female')
                                <td>Rs. {{ round($tds - 0.1 * $tds, 2) }}</td>
                            @else
                                <td>Rs. {{ number_format($tds,2) }}</td>
                            @endif --}}
                            <td>Rs. {{ number_format(($payrollEmployeeModel->tds),2) }}</td>
                            <td>22</td>
                            <th>TDS</th>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No record found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

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
                        name: "IRD Report",
                        filename: "ird_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
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
