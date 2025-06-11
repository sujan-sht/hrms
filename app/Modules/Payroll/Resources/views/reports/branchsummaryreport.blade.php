@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('reports.citReport') }}" class="breadcrumb-item">Branch Wise Report</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('css')
<style>
    .table-scroll {
        position: relative;
        max-width: 100%;
        margin: auto;
        overflow: hidden;
        /* border: 0.5px; */
    }

    .table-wrap {
        width: 100%;
        overflow: auto;
    }

    .table-scroll table {
        width: 100%;
        margin: auto;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* .table-scroll th, */
    .table-scroll td {
        /* background: #fff; */
        /* white-space: nowrap; */
        vertical-align: top;
        text-align: center;
    }

    .table-scroll th {
        background: #fff;
        /* white-space: nowrap; */
        /* vertical-align: top; */
        text-align: center;
    }

    .table-bg-white {
        background: #fff;
    }

    .table-scroll thead,
    .table-scroll tfoot {
        background: #f9f9f9;
    }

    .freeze1 {
        position: sticky;
        left: 0;
    }

    .freeze2 {
        position: sticky;
        left: 60px;
    }

    .freeze3 {
        position: sticky;
        left: 396px;
    }

    .freeze4 {
        position: sticky;
        left: 428px;
    }

    .freeze5 {
        position: sticky;
        left: 528px;
    }


    .freeze-head {
        background: #546e7a;
        position: sticky;
        top: 32px;
    }

    .hd {
        z-index: 2 !important;
    }
</style>
@endsection

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

@include('payroll::reports.partial.branch-summary-filter')

@if($payrollModel)

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
    <div id="table-scroll" class="table-scroll">
        <div class="table-wrap">
            <table class="table table-responsive table-bordered" id="table2excel">
                {{-- <table id="table2excel" class="table table-responsive table-striped table-bordered"> --}}
                <thead>
                    <tr class="text-white">
                        <th rowspan="2" class="freeze-head freeze1 hd">S.N</th>
                        <th rowspan="2" class="freeze-head">Branch Name</th>
                        <th colspan="{{ count($incomes) + 1 }}" class="text-center freeze-head">Income</th>
                        <th colspan="{{ count($deductions) + 2 }}" class="text-center freeze-head">Deduction</th>
                        <th rowspan="2" class="freeze-head">Total Salary</th>
                        <th rowspan="2" class="freeze-head">SST</th>
                        <th rowspan="2" class="freeze-head">TDS</th>
                        {{-- <th rowspan="2" class="freeze-head">Single Women Tax Credit(10% of Total Tax)</th>
                        <th rowspan="2" class="freeze-head">Total Tax</th> --}}
                        <th rowspan="2" class="freeze-head">Net Salary</th>
                        {{-- @foreach ($taxExcludeValues as $taxExcludeValue)
                            <th rowspan="2" class="freeze-head">{{ $taxExcludeValue }}</th>
                        @endforeach --}}
                        <th rowspan="2" class="freeze-head">Adjustment</th>
                        <th rowspan="2" class="freeze-head">Advance</th>
                        <th rowspan="2" class="freeze-head">Payable Salary</th>
                        <th rowspan="2" class="freeze-head">Remarks</th>
                    </tr>
                    <tr class="text-white">
                        {{-- {{dd($incomes)}} --}}
                        @foreach ($incomes as $income)
                            <th>{{ $income }}</th>
                        @endforeach
                        <th style="padding: 0px 60px;">Total</th>
                        <th>Leave Amount</th>
                        @foreach ($deductions as $deduction)
                            <th>{{ $deduction }}</th>
                        @endforeach
                        <th style="padding: 0px 60px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($payrollEmployeeDetails) > 0)
                        @php $count = 1; @endphp
                        @foreach($payrollEmployeeDetails as $branchName => $payrollEmployeeModels)
                        {{-- @dd($branchWiseIncomeReport[$branchName]); --}}
                            @php
                                $totalIncome = 0;
                                $totalDeduction = 0;
                                $sst = 0;
                                $tds = 0;
                                $leaveAmount = 0;
                                $netSalary = 0;
                                $adjustment = 0;
                                $advanceAmount = 0;
                                $payableSalary = 0;
    
                                foreach($payrollEmployeeModels as $payrollEmployeeModel) {
                                    // dd($payrollEmployeeModel);
                                    $totalIncome += $payrollEmployeeModel->total_income;
                                    $totalDeduction += $payrollEmployeeModel->total_deduction;
                                    $sst += $payrollEmployeeModel->sst;
                                    $tds += $payrollEmployeeModel->tds;
                                    $leaveAmount += $payrollEmployeeModel->leave_amount;
                                    $netSalary += $payrollEmployeeModel->net_salary;
                                    $adjustment += $payrollEmployeeModel->adjustment;
                                    $advanceAmount += $payrollEmployeeModel->advance_amount;
                                    $payableSalary += $payrollEmployeeModel->payable_salary;
                                }
                            @endphp
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $branchName }}</td>
                                @foreach ($branchWiseIncomeReport[$branchName] as $income)
                                    <th>{{ $income }}</th>
                                @endforeach
                                <td>{{ number_format($totalIncome, 2) }}</td>
                                <td>{{ number_format($leaveAmount, 2) }}</td>

                                @foreach ($branchWiseDeductionReport[$branchName] as $deduction)
                                    <th>{{ $deduction }}</th>
                                @endforeach
                                <td>{{ number_format($totalDeduction, 2) }}</td>
                                <td>{{ number_format(($totalIncome - $totalDeduction), 2) }}</td>
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
    </div>
</div>
@endif



@endsection

@section('script')
<!-- select2 js -->
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
    // jQuery(document).ready(function() {
    //     jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
    // });
    $(document).ready(function() {
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                // var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                // $(table).table2excel({
                //     exclude: ".noExl",
                //     name: "Payroll Report",
                //     filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
                //         "") + ".xls",
                //     fileext: ".xls",
                //     exclude_img: true,
                //     exclude_links: true,
                //     exclude_inputs: true
                // });
                var clone = $(table).clone(); // Create a clone of the table for exporting
                // Remove hidden input elements from the clone
                clone.find('input[type="hidden"]').remove();
                clone.find('td span.email').remove();
                clone.table2excel({
                    exclude: ".noExl",
                    name: "Payroll Report",
                    filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
                        "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });
            }
        });


        $('#organization_id').change(function() {
            var organizationId = $(this).val();
            if (organizationId) {
                $.ajax({
                    url: '{{ route("payroll.getOrganizationYearMonth") }}',
                    type: 'GET',
                    data: { organization_id: organizationId },
                    success: function(response) {
                        // Update year dropdown
                        var yearSelect = $('#year_id');
                        yearSelect.empty();
                        yearSelect.append('<option value="">Select Year</option>');
                        $.each(response.years, function(key, value) {
                            yearSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Update month dropdown
                        var monthSelect = $('#month_id');
                        monthSelect.empty();
                        monthSelect.append('<option value="">Select Month</option>');
                        $.each(response.months, function(key, value) {
                            monthSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Update employee dropdown
                        var EmployeeSelect = $('#employee_id');
                        EmployeeSelect.empty();
                        EmployeeSelect.append('<option value="">Select Employee</option>');
                        $.each(response.employee, function(key, value) {
                            EmployeeSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Reinitialize select2
                        yearSelect.select2();
                        monthSelect.select2();
                        EmployeeSelect.select2();
                    }
                });
            }
        });

        $('#year_id').change(function() {
            var organizationId = $('#organization_id').val();
            var year = $(this).val();
            if (organizationId && year) {
                $.ajax({
                    url: '{{ route("payroll.getOrganizationMonth") }}',
                    type: 'GET',
                    data: {
                        organization_id: organizationId,
                        year: year
                    },
                    success: function(response) {
                        var monthSelect = $('#month_id');
                        monthSelect.empty();
                        monthSelect.append('<option value="">Select Month</option>');
                        $.each(response.months, function(key, value) {
                            monthSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        monthSelect.select2();
                    }
                });
            }
        });

    });
</script>
<script>
</script>
@endSection

