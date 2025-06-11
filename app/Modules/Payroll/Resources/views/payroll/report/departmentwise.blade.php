@extends('admin::layout')
@section('title') Payroll Report @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">Report</a>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>
@include('payroll::payroll.report.partial.advance-filter')
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
    <table id="table2excel" class="table table-responsive table-striped table-bordered">
        <thead>
            <tr class="text-white">
                <th>S.N</th>
                <th>Sub-Function</th>
                <th>Total Days</th>
                <th>Total Working Days</th>
                <th>Total Unpaid Leave Days</th>
                <th>Total Income</th>
                <th>Total Deduction</th>
                <th>Total Salary</th>
                <th>SST</th>
                <th>TDS</th>
                <th>Net Salary</th>
                <th>Adjustment</th>
                <th>Advance</th>
                <th>Payable Salary</th>
            </tr>
        </thead>
        <tbody>
            @if (count($payrollEmployeeDetails) > 0)
                @php $count = 1; @endphp
                @foreach ($payrollEmployeeDetails as $departmentName => $payrollEmployeeModels)
                    @php
                        $totalDays = 0;
                        $totalWorkingDays = 0;
                        $totalUnpaidLeaveDays = 0;
                        $totalIncome = 0;
                        $totalDeduction = 0;
                        $sst = 0;
                        $tds = 0;
                        $netSalary = 0;
                        $adjustment = 0;
                        $advanceAmount = 0;
                        $payableSalary = 0;

                        foreach ($payrollEmployeeModels as $payrollEmployeeModel) {
                            $totalDays += $payrollEmployeeModel->total_days;
                            $totalWorkingDays += $payrollEmployeeModel->total_working_days;
                            $totalUnpaidLeaveDays += $payrollEmployeeModel->unpaid_leave_days;
                            $totalIncome += $payrollEmployeeModel->total_income;
                            $totalDeduction += $payrollEmployeeModel->total_deduction;
                            $sst += $payrollEmployeeModel->sst;
                            $tds += $payrollEmployeeModel->tds;
                            $netSalary += $payrollEmployeeModel->net_salary;
                            $adjustment += $payrollEmployeeModel->adjustment;
                            $advanceAmount += $payrollEmployeeModel->advance_amount;
                            $payableSalary += $payrollEmployeeModel->payable_salary;
                        }
                    @endphp
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $departmentName }}</td>
                        <td>{{ $totalDays }}</td>
                        <td>{{ $totalWorkingDays }}</td>
                        <td>{{ $totalUnpaidLeaveDays }}</td>
                        <td>{{ number_format($totalIncome, 2) }}</td>
                        <td>{{ number_format($totalDeduction, 2) }}</td>
                        <td>{{ number_format($totalIncome - $totalDeduction, 2) }}</td>
                        <td>{{ number_format($sst, 2) }}</td>
                        <td>{{ number_format($tds, 2) }}</td>
                        <td>{{ number_format($netSalary, 2) }}</td>
                        <td>{{ number_format($adjustment, 2) }}</td>
                        <td>{{ number_format($advanceAmount, 2) }}</td>
                        <td>{{ number_format($payableSalary, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr colspan="14">
                    <td>No record found.</td>
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
                    name: "Sub-Function Wise Report",
                    filename: "department_wise_report_" + new Date().toISOString().replace(
                        /[\-\:\.]/g, "") + ".xls",
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
