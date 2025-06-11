@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('css')
<style>
    thead tr:nth-child(2) th {
        background: #546e7a;
        position: sticky;
        top: 46px;
        /* z-index: 2; */
    }

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

    .hold {
        background-color: yellow !important;
    }

    @keyframes blink {
        50% {
            opacity: 0;
        }
    }

    .viewTaxCalculation {
        animation: blink 1s infinite;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
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
        <div class="mt-1">
            <div class="ml-1">
                <button type="btn" id ="payroll_sync" class="btn btn-primary rounded-pill"><b><i
                            class="icon-sync"></i></b>Sync</button>
                <a href="#" class="btn btn-success rounded-pill" data-toggle="modal"
                    data-target="#modal_default_import">
                    <i class="icon-file-excel text-success"></i> Import</a>
                <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
            </div>
        </div>
    </div>
</div>
@include('payroll::payroll.partial.upload')
<form id="myForm" action="{{ route('payroll.draft', $payrollModel->id) }}" method="POST">
    @csrf

    <div class="card card-body">
        <div id="table-scroll" class="table-scroll">
            <div class="table-wrap">
                <table class="table table-responsive table-bordered" id="table2excel">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th rowspan="2" class="freeze-head freeze1 hd">S.N</th>
                            <th rowspan="2" class="freeze-head freeze2 hd">Employee Name</th>
                            <th rowspan="2" class="freeze-head">Emp Code</th>
                            <th rowspan="2" class="freeze-head">Branch Name</th>
                            <th rowspan="2" class="freeze-head">Designation</th>
                            <th rowspan="2" class="freeze-head">Join Date</th>
                            <th rowspan="2" class="freeze-head">Marital Status</th>
                            <th rowspan="2" class="freeze-head">Gender</th>
                            <th rowspan="2" class="freeze-head">CIT No</th>
                            <th rowspan="2" class="freeze-head">SSF No</th>
                            <th rowspan="2" class="freeze-head">PAN No</th>
                            <th rowspan="2" class="freeze-head">Bank Name</th>
                            <th rowspan="2" class="freeze-head">Bank A/c No.</th>
                            <th rowspan="2" class="freeze-head">Total Days</th>
                            {{-- <th rowspan="2" class="freeze-head">Total Worked Days</th>
                            <th rowspan="2" class="freeze-head">Extra Working Days</th>
                            <th rowspan="2" class="freeze-head">OT Hour</th>
                            <th rowspan="2" class="freeze-head">Total Paid Leave Days</th> --}}
                            <th rowspan="2" class="freeze-head">Total Unpaid Days</th>
                            <th rowspan="2" class="freeze-head">Total Payable Days</th>
                            {{-- <th rowspan="2">Total Days For Payment</th> --}}
                            <th colspan="{{ count($incomes) + ($leaveEncashmentSetupStatus ? 2 : 1) }}"
                                class="text-center freeze-head">Income</th>
                            <th colspan="{{ count($deductions) + 1 }}" class="text-center freeze-head">Deduction</th>
                            <th rowspan="2" class="freeze-head">Total Salary</th>
                            {{-- <th rowspan="2" class="freeze-head">Festival Bonus</th> --}}
                            <!-- <th rowspan="2" class="freeze-head">Total Salary With Bonus</th> -->
                            <th rowspan="2" class="freeze-head">Taxable Amount (Yearly)</th>
                            <th rowspan="2" class="freeze-head">SST</th>
                            <th rowspan="2" class="freeze-head">TDS</th>
                            <th rowspan="2" class="freeze-head">Single Women Tax Credit(10% of SST + TDS )</th>
                            <th rowspan="2" class="freeze-head">Total Tax</th>
                            <!-- <th rowspan="2" class="freeze-head">Extra Working Days Amount</th> -->
                            <th rowspan="2" class="freeze-head">Net Salary</th>
                            @foreach ($taxExcludeValues as $taxExcludeValue)
                                <th rowspan="2" class="freeze-head">{{ $taxExcludeValue }}</th>
                            @endforeach

                            {{-- <th rowspan="2" class="freeze-head">Single Women Tax Credit(10% of Total Tax)</th> --}}
                            <th rowspan="2" class="freeze-head">Adjustment</th>
                            <th rowspan="2" class="freeze-head">Advance</th>
                            <th rowspan="2" class="freeze-head">Payable Salary</th>
                            <th rowspan="2" class="freeze-head">Remarks</th>
                        </tr>
                        <tr class="text-white">
                            {{-- <th>Gross Basic Salary</th>
                                <th>Leave Deduction</th> --}}
                            @foreach ($incomes as $income)
                                <th>{{ $income }}</th>
                            @endforeach
                            @if ($leaveEncashmentSetupStatus)
                                <th>Encashment</th>
                            @endif
                            {{-- <th>Arrear Amount</th>
                            <th>Over-Time Pay</th> --}}
                            <th style="padding: 0px 60px;">Total</th>
                            {{-- <th>Leave Amount</th> --}}
                            @foreach ($deductions as $deduction)
                                <th>{{ $deduction }}</th>
                            @endforeach
                            {{-- <th>Fine & Penalty</th> --}}
                            <th style="padding: 0px 60px;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($payrollModel->payrollEmployees) > 0)
                            @php
                                foreach ($incomes as $id => $title) {
                                    ${'grandIncomeAmount' . $id} = 0;
                                }

                                $grandArrearAmount = 0;
                                $grandOverTimePay = 0;
                                $grandTotalIncome = 0;
                                foreach ($deductions as $id => $title) {
                                    ${'grandDeductionAmount' . $id} = 0;
                                }
                                $grandLeaveAmount = 0;
                                $totalFineAndPenalty = 0;
                                $grandTotalDeduction = 0;
                                $grandTotalSalary = 0;
                                $grandFestivalBonus = 0;
                                $grandYearlyTaxableSalary = 0;
                                $grandSst = 0;
                                $grandTds = 0;
                                $grandTax = 0;
                                $grandNetSalary = 0;
                                $grandSingleWomenTaxCredit = 0;
                                $grandAdjustment = 0;
                                $grandAdvance = 0;
                                $grandPayableSalary = 0;
                                $grandencashmentValue = 0;
                            @endphp
                            @foreach ($payrollModel->payrollEmployees as $key => $payrollEmployee)
                                {{-- @if ($payrollEmployee->employee_id == 33) --}}
                                    @php
                                        $totalDeduction = 0;
                                        $totalIncome = 0;
                                        $totalTaxExcludeAmount = 0;
                                        $total_days = 0;
                                        $encashmentIncomeData = [];
                                        $encashmentValue = 0;
                                        $encashmentArrayDetails = null;
                                        if ($payrollModel->calendar_type == 'nep') {
                                            $joinDate = optional($payrollEmployee->employee)->nepali_join_date;
                                            if (optional($payrollEmployee->employee)->status == 0) {
                                                $terminatedDate = optional($payrollEmployee->employee)
                                                    ->nep_archived_date;
                                            } else {
                                                $terminatedDate = null;
                                            }
                                        } else {
                                            $joinDate = optional($payrollEmployee->employee)->join_date;
                                            if (optional($payrollEmployee->employee)->status == 0) {
                                                $terminatedDate = optional($payrollEmployee->employee)->archived_date;
                                            } else {
                                                $terminatedDate = null;
                                            }
                                        }
                                        // $joinMonth = date('m', strtotime($joinDate));
                                        $explodeJoinDate = explode('-', $joinDate);
                                        $joinMonth = (int) $explodeJoinDate[1];
                                        // $joinDay = explode('-', $joinDate);
                                        $joinDay = (int) $explodeJoinDate[2];
                                        $joinYear = $explodeJoinDate[0];
                                        if ($terminatedDate) {
                                            $explodeTerminatedDate = explode('-', $terminatedDate);
                                            $terminatedMonth = (int) $explodeTerminatedDate[1];
                                            $terminatedDay = (int) $explodeTerminatedDate[2];
                                            $terminatedYear = $explodeTerminatedDate[0];
                                        }
                                        // $joinDay = date('d', strtotime($joinDate));
                                        // $joinYear = date('Y', strtotime($joinDate));
                                    @endphp
                                    <tr class="myLine {{ $payrollEmployee->hold_status ? 'hold' : '' }}">
                                        <td
                                            class="freeze1 table-bg-white {{ $payrollEmployee->hold_status ? 'hold' : '' }}">
                                            #{{ ++$key }}
                                            <input type="hidden" name="payrollEmployeeId"
                                                value="{{ $payrollEmployee->id }}" class="payrollEmployeeId">
                                        </td>
                                        <td
                                            class="freeze2 table-bg-white {{ $payrollEmployee->hold_status ? 'hold' : '' }}">
                                            <div class="media">
                                                <div class="mr-3">
                                                    <img src="{{ optional($payrollEmployee->employee)->getImage() }}"
                                                        class="rounded-circle" width="40" height="40"
                                                        alt="">
                                                </div>
                                                <div class="media-body">
                                                    <div class="media-title font-weight-semibold">
                                                        {{ optional($payrollEmployee->employee)->getFullName() }}</div>
                                                    <span
                                                        class="text-muted email">{{ optional($payrollEmployee->employee)->official_email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ optional($payrollEmployee->employee)->employee_code }}</td>
                                        <td>{{ optional(optional($payrollEmployee->employee)->branchModel)->name }}
                                        </td>
                                        <td>{{ optional(optional($payrollEmployee->employee)->designation)->dropvalue }}
                                        </td>
                                        <td>{{ $joinDate }}</td>
                                        <td>{{ optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue }}
                                        </td>
                                        <td>{{ optional(optional($payrollEmployee->employee)->getGender)->dropvalue }}
                                        </td>
                                        <td>{{ optional($payrollEmployee->employee)->cit_no }} </td>
                                        <td>{{ optional($payrollEmployee->employee)->ssf_no }} </td>
                                        <td>{{ optional($payrollEmployee->employee)->pan_no }} </td>
                                        <td>{{ optional(optional(optional($payrollEmployee->employee)->bankDetail)->bankinfo)->dropvalue }}
                                        </td>
                                        <td>{{ optional(optional($payrollEmployee->employee)->bankDetail)->account_number }}
                                        </td>
                                        @php
                                            $attendance = $payrollEmployee->calculateAttendance(
                                                $payrollModel->calendar_type,
                                                $payrollModel->year,
                                                $payrollModel->month,
                                            );
                                            $leave = $payrollEmployee->calculateLeave(
                                                $payrollModel->calendar_type,
                                                $payrollModel->year,
                                                $payrollModel->month,
                                            );
                                            $total_paid_leave = $leave['paidLeaveTaken'];
                                            $total_leave = $leave['unpaid_days'] + $leave['unpaidLeaveTaken'];
                                            $payable_days = $attendance['total_days'] - $total_leave;
                                        @endphp
                                        @if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth))
                                            <td>{{ $terminatedDay }}</td>
                                        @elseif ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth)
                                            <td>{{ $attendance['total_days'] - $joinDay + 1 }}</td>
                                        @else
                                            <td>{{ $attendance['total_days'] }}</td>
                                        @endif
                                        {{-- <td>{{ $attendance['total_days'] }}</td> --}}
                                        {{-- <td>{{ $attendance['working_days'] }}</td>
                                        <td>{{ $attendance['extra_working_days'] }}</td>
                                        <td>{{ $attendance['total_ot_hour'] }}</td> --}}
                                        {{-- <td>{{ $total_paid_leave }}</td> --}}
                                        <td>{{ $total_leave }}</td>
                                        <td>{{ $payable_days }}</td>
                                        @foreach ($payrollEmployee->incomes as $k => $income)
                                            @if (optional($income->incomeSetup)->monthly_income == 11)
                                                @php
                                                $bsIncomeValue = 0;
                                                $totalDeduction = 0;
                                                    $incomeModel = optional($income->incomeSetup);
                                                    $incomeAmount = $income->value ?? 0;
                                                    // if ($incomeModel->daily_basis_status == 11) {
                                                    //     if ($attendance['working_days'] == 0) {
                                                    //         $incomeAmount = 0;
                                                    //     } else {
                                                    //         $incomeAmount = $income->value * $attendance['working_days'];
                                                    //     }
                                                    // } else {
                                                    //     if($incomeModel->method !=3){
                                                    //         if($total_leave > 0) {
                                                    //             $incomeAmount = round((($income->value ?? 0) / $attendance['total_days']) * ($attendance['total_days'] - $total_leave), 2);
                                                    //         }
                                                    //         else {
                                                    //             $incomeAmount = $income->value ?? 0;
                                                    //         }
                                                    //     }
                                                    //     else{
                                                    //         $incomeAmount = $income->value ?? 0;
                                                    //     }
                                                    // }
                                                    $totalIncome = $totalIncome + $incomeAmount;
                                                    if (in_array($income->income_setup_id, $leaveEncashmentIncomeIds)) {
                                                        $encashmentIncomeData[] = $incomeAmount;
                                                    }

                                                    $incomeSetup = optional($income->incomeSetup);
                                                    $parentSetup = optional($incomeSetup->parent); // Assuming parent() is a relationship

                                                    if ($parentSetup->sort_name === 'BS') {
                                                        $bsIncomeValue = $income->value ?? 0;

                                                        // Use parentheses to ensure correct precedence
                                                        $totalDeduction = (($income->value ?? 0) % $payable_days) * ($attendance['total_days'] ?? 0);
                                                    }

                                                @endphp
                                                @if($k == 0)
                                                    <td>
                                                        {{ $income->value }}
                                                    </td>
                                                    <td>
                                                       {{ $totalDeduction }}
                                                    </td>
                                                    @endif

                                                @if ($incomeModel->method == '1' || $incomeModel->method == '3')

                                                    <td>
                                                        <div style="width: 80px;">
                                                            <input type="text"
                                                                name="payroll_income[{{ $income->id }}]"
                                                                value="{{ $incomeAmount - $totalDeduction }}"
                                                                class="form-control numeric income"
                                                                placeholder="0.00">
                                                        </div>
                                                    </td>
                                                @else
                                                    <input type="hidden" name="payroll_income[{{ $income->id }}]"
                                                        value="{{ $incomeAmount - $totalDeduction }}"
                                                        class="form-control numeric income" placeholder="0.00">
                                                    <td>{{ $incomeAmount - $totalDeduction }}</td>
                                                @endif
                                                @php
                                                    ${'grandIncomeAmount' .
                                                        optional($income->incomeSetup)->id} += $incomeAmount;
                                                @endphp
                                            @endif
                                        @endforeach
                                        @if ($leaveEncashmentSetupStatus)
                                            @php
                                                $encashment = $payrollEmployee->getEncashmentDetails(
                                                    $payrollEmployee,
                                                    $leaveDataDetail,
                                                    $searchData,
                                                    $leaveYearSetupDetail,
                                                    $encashmentIncomeData,
                                                    $payable_days,
                                                );
                                                $encashmentValue = @$encashment['amount'] ?? 0;
                                                $encashmentArrayDetails = json_encode($encashment['leaveArrayDetails']);
                                            @endphp
                                            <td>{{ @$encashmentValue ?? 0 }}</td>
                                        @endif
                                        @php
                                            $arrear_amount = 0;
                                            $overTimePay = 0;
                                            $grandencashmentValue += $encashmentValue;
                                            // $grandArrearAmount += $arrear_amount;
                                        @endphp
                                        {{-- <td>{{ $arrear_amount }}</td> --}}
                                        {{-- <td>
                                        @php
                                            $overTimePay = $payrollEmployee->overtime_pay ?? $attendance['total_ot_amount'];
                                            $grandOverTimePay += $overTimePay;
                                        @endphp
                                        <div style="width: 80px;">
                                            <input type="text" name="overtime_pay[{{ $payrollEmployee->id }}]"
                                                value="{{ $overTimePay }}" class="overtimePay form-control numeric"
                                                placeholder="0.00">
                                        </div>
                                    </td> --}}
                                        <input type="hidden" name="overtime_pay[{{ $payrollEmployee->id }}]"
                                            value="{{ $overTimePay }}" class="overtimePay form-control numeric"
                                            placeholder="0.00">
                                        <input type="hidden"
                                            name="encashmentArrayDetails[{{ $payrollEmployee->employee_id }}]"
                                            value="{{ $encashmentArrayDetails }}" class=" form-control">
                                        <td>
                                            {{-- @if ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth)
                                            @php
                                                $totalIncome = $payrollEmployee->total_income ? $payrollEmployee->total_income : (($totalIncome + $arrear_amount + $overTimePay) / $attendance['total_days']) * ($attendance['total_days'] - $joinDay + 1);
                                                $grandTotalIncome += $totalIncome;
                                            @endphp
                                        @else
                                            @php
                                                $totalIncome = $payrollEmployee->total_income ? $payrollEmployee->total_income : $totalIncome + $arrear_amount + $overTimePay;
                                                $grandTotalIncome += $totalIncome;
                                            @endphp
                                        @endif

                                        @if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth))
                                            @php
                                                $totalIncome = $payrollEmployee->total_income ? $payrollEmployee->total_income : (($totalIncome + $arrear_amount + $overTimePay) / $attendance['total_days']) * $terminatedDay;
                                                $grandTotalIncome += $totalIncome;
                                            @endphp
                                        @endif --}}
                                            @php
                                                $totalIncome = $payrollEmployee->total_income
                                                    ? $payrollEmployee->total_income
                                                    : $totalIncome + $arrear_amount + $overTimePay + $encashmentValue;
                                                $grandTotalIncome += $totalIncome;
                                            @endphp

                                            <input type="number" name="total_income[{{ $payrollEmployee->id }}]"
                                                value="{{ round($totalIncome, 2) }}"
                                                class="form-control totalIncome">
                                        </td>
                                        @php
                                            // $leaveAmount = ($totalIncome / $attendance['total_days']) * $total_leave;
                                            // $leaveAmount = $payrollEmployee->leave_amount ?? $leave['leave_amount'];
                                            $leaveAmount = 0;
                                            $grandLeaveAmount += $leaveAmount;
                                            $extra_working_amount = round(
                                                ($totalIncome / $attendance['total_days']) *
                                                    $attendance['extra_working_days'],
                                                2,
                                            );
                                        @endphp
                                        {{-- <td>
                                        <div style="width: 80px;">
                                            <input type="text"
                                                name="leave_amount[{{ $payrollEmployee->id }}]"
                                                value="{{ $leaveAmount }}"
                                                class="form-control numeric leave_amount" placeholder="0.00">
                                        </div>
                                    </td> --}}
                                        <input type="hidden" name="leave_amount[{{ $payrollEmployee->id }}]"
                                            value="{{ $leaveAmount }}" class="leave_amount">
                                        @foreach ($payrollEmployee->deductions as $deduction)
                                            @php
                                                $deductionModel = optional($deduction->deductionSetup);
                                                $deductionAmount = $deduction->value ?? 0;
                                                // if($deductionModel->method !=3){
                                                //     if($total_leave > 0) {
                                                //         $deductionAmount = round((($deduction->value ?? 0) / $attendance['total_days']) * ($attendance['total_days'] - $total_leave), 2);
                                                //     }
                                                //     else {
                                                //         $deductionAmount = $deduction->value ?? 0;
                                                //     }
                                                // }
                                                // else{
                                                //     $deductionAmount = $deduction->value ?? 0;
                                                // }

                                                $totalDeduction = $totalDeduction + $deductionAmount;
                                            @endphp
                                            @if ($deductionModel->method == '3')
                                                <td>
                                                    <div style="width: 80px;">
                                                        <input type="text"
                                                            name="payroll_deduction[{{ $deduction->payroll_deduction_id }}]"
                                                            value="{{ $deductionAmount }}"
                                                            class="form-control numeric deduction" placeholder="0.00">
                                                    </div>
                                                </td>
                                            @else
                                                <input type="hidden"
                                                    name="payroll_deduction[{{ $deduction->payroll_deduction_id }}]"
                                                    value="{{ $deductionAmount }}"
                                                    class="form-control numeric deduction" placeholder="0.00">
                                                <td>{{ $deductionAmount }}</td>
                                            @endif
                                            @php
                                                ${'grandDeductionAmount' . $deduction->id} += $deductionAmount;
                                            @endphp
                                        @endforeach
                                        {{-- <td>
                                        @php
                                            $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
                                            $totalFineAndPenalty += $fineAndPenalty;
                                        @endphp
                                        <div style="width: 80px;">
                                            <input type="number" name="fine_penalty[{{ $payrollEmployee->id }}]"
                                                value="{{ $fineAndPenalty }}"
                                                class="fineAndPenalty form-control numeric" placeholder="0.00">
                                        </div>
                                    </td> --}}
                                        @php
                                            $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
                                        @endphp
                                        <input type="hidden" name="fine_penalty[{{ $payrollEmployee->id }}]"
                                            value="{{ $fineAndPenalty }}" class="fineAndPenalty form-control numeric"
                                            placeholder="0.00">
                                        <input type="hidden" name="marital_status[{{ $payrollEmployee->id }}]"
                                            value="{{ optional($payrollEmployee->employee)->marital_status }}"
                                            class="form-control">
                                        <input type="hidden" name="total_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $attendance['total_days'] }}" class="form-control total_days">
                                        <input type="hidden" name="total_working_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $attendance['working_days'] }}" class="form-control">
                                        <input type="hidden" name="extra_working_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $attendance['extra_working_days'] }}" class="form-control">
                                        <input type="hidden" name="unpaid_leave_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $total_leave }}" class="form-control unpaid_leave">
                                        <input type="hidden" name="paid_leave_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $total_paid_leave }}" class="form-control">
                                        {{-- <input type="hidden" name="leave_amount[{{ $payrollEmployee->id }}]"
                                        value="{{ round($leaveAmount, 2) }}" class="form-control leave_amount"> --}}
                                        <td>

                                            {{-- @if ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth)
                                            @php
                                                $totalDeduction = $payrollEmployee->total_deduction ? $payrollEmployee->total_deduction : (($totalDeduction + $leaveAmount + $fineAndPenalty) / $attendance['total_days']) * ($attendance['total_days'] - $joinDay + 1);
                                                $grandTotalDeduction += $totalDeduction;
                                            @endphp
                                        @else --}}
                                            @php
                                                $totalDeduction = $payrollEmployee->total_deduction
                                                    ? $payrollEmployee->total_deduction
                                                    : $totalDeduction + $leaveAmount + $fineAndPenalty;
                                                $grandTotalDeduction += $totalDeduction;
                                            @endphp
                                            {{-- @endif --}}

                                            <input type="text" name="total_deduction[{{ $payrollEmployee->id }}]"
                                                value="{{ round($totalDeduction, 2) }}"
                                                class="form-control totalDeduction numeric">
                                            <!-- <input type="number" name="monthly_total_deduction[{{ $payrollEmployee->id }}]" value="{{ round($totalDeduction + $leaveAmount + $fineAndPenalty, 2) }}" class="form-control totalDeduction"> -->
                                        </td>
                                        <td>
                                            @php
                                                $totalSalary = $totalIncome - $totalDeduction;
                                                $festivalBonus = 0;
                                                $grandTotalSalary += $totalSalary;
                                            @endphp
                                            <div class="totalSalary">{{ round($totalSalary, 2) }}</div>
                                        </td>
                                        {{-- <td>
                                        <div style="width: 80px;">
                                            @php
                                                $festivalBonus = $payrollEmployee->festival_bonus ? $payrollEmployee->festival_bonus : 0;
                                                $grandFestivalBonus += $festivalBonus;
                                            @endphp
                                            <input type="text" name="festival_bonus[{{ $payrollEmployee->id }}]"
                                                value="{{ $festivalBonus }}" class="form-control festivalBonus numeric">
                                        </div>
                                    </td> --}}
                                        <input type="hidden" name="festival_bonus[{{ $payrollEmployee->id }}]"
                                            value="{{ $festivalBonus }}" class="form-control festivalBonus numeric">
                                        <!-- <td>
                                        @php
                                            $totalSalaryWithBonus = $totalSalary + $festivalBonus;
                                        @endphp
                                        <div class="totalSalaryWithBonus">{{ number_format($totalSalaryWithBonus, 2) }}</div>
                                    </td> -->
                                        {{-- @dd(round($payrollEmployee->calculateTaxableSalary($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployee->id), 2)) --}}
                                        <td>
                                            @php
                                                $taxableAmount = round(
                                                    $payrollEmployee->calculateTaxableSalary(
                                                        $totalIncome,
                                                        $totalDeduction,
                                                        $festivalBonus,
                                                        $payrollEmployee->id,
                                                    ),
                                                    2,
                                                );
                                                $grandYearlyTaxableSalary += $taxableAmount;
                                            @endphp
                                            {{-- <div id="taxableAmount"><a href = "{{route('payroll.taxCalculation',$payrollEmployee->id)}}" target="_blank">{{ $taxableAmount }}</a></div> --}}
                                            <div id="taxableAmount">{{ $taxableAmount }}</div>
                                            <a href="javascript:;" class="viewTaxCalculation"
                                                data-totalIncome="{{ @$totalIncome }}"
                                                data-totalDeduction="{{ @$totalDeduction }}"
                                                data-festivalBonus="{{ @$festivalBonus }}"
                                                data-payrollEmployee="{{ @$payrollEmployee->id }}">
                                                View
                                            </a>
                                            <input type="hidden"
                                                name="yearly_taxable_salary[{{ $payrollEmployee->id }}]"
                                                value="{{ $taxableAmount }}" class="taxableAmount">
                                        </td>

                                        <td>
                                            @php
                                                $sst = isset($payrollEmployee->sst)
                                                    ? $payrollEmployee->sst
                                                    : $payrollEmployee->calculateSST(
                                                        $totalIncome,
                                                        $totalDeduction,
                                                        $festivalBonus,
                                                        $payrollEmployee->id,
                                                        $payrollModel->organization_id,
                                                    );
                                            @endphp
                                            @php $grandSst += $sst; @endphp
                                            <div style="width: 100px;">
                                                <input type="text" name="sst[{{ $payrollEmployee->id }}]"
                                                    value="{{ $sst }}"
                                                    class="form-control sst p-100 numeric">
                                            </div>
                                        </td>
                                        <input type="hidden" name="sst[{{ $payrollEmployee->id }}]"
                                            value="{{ $sst }}" class="sst">
                                        {{-- @dd(isset($payrollEmployee->tds) ? $payrollEmployee->tds : $payrollEmployee->calculateTDS($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployee->id)) --}}
                                        <td>
                                            @php
                                                $tds = isset($payrollEmployee->tds)
                                                    ? $payrollEmployee->tds
                                                    : $payrollEmployee->calculateTDS(
                                                        $totalIncome,
                                                        $totalDeduction,
                                                        $festivalBonus,
                                                        $payrollEmployee->id,
                                                    );

                                            @endphp
                                            {{-- @if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth))
                                            @php
                                                $tds = $payrollEmployee->tds ? $payrollEmployee->tds : round(($tds / $attendance['total_days']) * $terminatedDay,2);
                                            @endphp
                                        @endif --}}
                                            @php $grandTds += $tds; @endphp
                                            <div style="width: 100px;">
                                                <input type="text" name="tds[{{ $payrollEmployee->id }}]"
                                                    value="{{ $tds }}" class="form-control tds numeric">
                                            </div>
                                        </td>
                                        @php
                                            $total_tax = $sst + $tds;
                                            // if (optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue == 'Single' && optional(optional($payrollEmployee->employee)->getGender)->dropvalue == 'Female') {
                                            //     $single_women_tax_credit = round(0.1 * $total_tax, 2);
                                            //     $gender = 2;
                                            // } else {
                                            //     $gender = 1;
                                            //     $single_women_tax_credit = 0;
                                            // }
                                            if (
                                                optional(optional($payrollEmployee->employee)->getMaritalStatus)
                                                    ->dropvalue == 'Single' &&
                                                optional(optional($payrollEmployee->employee)->getGender)->dropvalue ==
                                                    'Female'
                                            ) {
                                                $single_women_tax_credit = round(0.1 * $total_tax, 2);
                                                $gender = 2;
                                            } else {
                                                $gender = 1;
                                                $single_women_tax_credit = 0;
                                            }
                                            $final_tax = $total_tax - $single_women_tax_credit;
                                            $grandTax += $final_tax;
                                            $grandSingleWomenTaxCredit += $single_women_tax_credit;
                                        @endphp
                                        <td>
                                            <div id="single_women_tax">{{ $single_women_tax_credit }}</div>
                                        </td>
                                        <td>
                                            <div id="totalTax">{{ round($final_tax, 2) }}</div>
                                        </td>
                                        <!-- <td>{{ $extra_working_amount }}</td>
                                    <input type="hidden" name="extra_working_days_amount[{{ $payrollEmployee->id }}]"  value="{{ round($leaveAmount, 2) }}" class="form-control"> -->
                                        <td>
                                            @php
                                                $netSalary =
                                                    $totalIncome -
                                                    ($totalDeduction + $sst + $tds - $single_women_tax_credit);
                                                $grandNetSalary += $netSalary;
                                            @endphp
                                            <div id="netSalary">{{ round($netSalary, 2) }}</div>
                                            <input type="hidden" name="net_salary[{{ $payrollEmployee->id }}]"
                                                value="{{ $netSalary }}" class="form-control netSalary">
                                        </td>
                                        @foreach ($payrollEmployee->taxExcludeValues as $taxExcludeValue)
                                            @php
                                                $taxExcludeAmount = $taxExcludeValue->value ?? 0;
                                                if (optional($taxExcludeValue->taxExcludeSetup)->type == 1) {
                                                    $totalTaxExcludeAmount += $taxExcludeAmount;
                                                } else {
                                                    $totalTaxExcludeAmount -= $taxExcludeAmount;
                                                }
                                            @endphp
                                            <td class="taxExclude">
                                                <div style="width: 80px;">
                                                    <input type="text"
                                                        name="payroll_tax_exclude_value[{{ $taxExcludeValue->id }}]"
                                                        value="{{ $taxExcludeAmount }}"
                                                        class="form-control numeric taxExcludeValue"
                                                        placeholder="0.00"
                                                        data-type="{{ optional($taxExcludeValue->taxExcludeSetup)->type }}">
                                                </div>
                                            </td>
                                        @endforeach
                                        <input type="hidden"
                                            name="single_women_tax_credit[{{ $payrollEmployee->id }}]"
                                            value="{{ $single_women_tax_credit }}"
                                            class="form-control single_women_tax">
                                        <input type="hidden" value="{{ $gender }}" class="form-control"
                                            id ="gender">
                                        <td>
                                            @php $adjustment = isset($payrollEmployee->adjustment) ? $payrollEmployee->adjustment : 0; @endphp
                                            <div class="row" style="width: 200px;">
                                                <div class="col-md-6">
                                                    <select name="adjustment_status[{{ $payrollEmployee->id }}]"
                                                        class="form-control adjustmentMode">
                                                        <option value="sub"
                                                            {{ $payrollEmployee->adjustment_status == 'sub' ? 'selected' : '' }}>
                                                            SUB</option>
                                                        <option value="add"
                                                            {{ $payrollEmployee->adjustment_status == 'add' ? 'selected' : '' }}>
                                                            ADD</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6" style="width: 80px;">
                                                    <input type="text"
                                                        name="adjustment[{{ $payrollEmployee->id }}]"
                                                        value="{{ $adjustment }}"
                                                        class="form-control numeric adjustment">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $advance = isset($payrollEmployee->advanceAmount)
                                                    ? $payrollEmployee->advanceAmount
                                                    : 0;
                                                $grandAdvance += $advance;
                                            @endphp
                                            {{ round($advance, 2) }}
                                            <input type="hidden" name="advance_amount[{{ $payrollEmployee->id }}]"
                                                value="{{ $advance }}" class="form-control advanceAmount"
                                                readonly>
                                        </td>
                                        <td>
                                            @php
                                                $payableSalary = $payrollEmployee->payable_salary
                                                    ? $payrollEmployee->payable_salary
                                                    : $netSalary - $adjustment - $advance + $totalTaxExcludeAmount;
                                                $grandPayableSalary += $payableSalary;
                                            @endphp
                                            <div id="payableSalary">{{ round($payableSalary, 2) }}</div>
                                            <input type="hidden" name="payable_salary[{{ $payrollEmployee->id }}]"
                                                value="{{ $payableSalary }}" class="form-control payableSalary"
                                                readonly>
                                        </td>
                                        <td>
                                            <div style="width: 150px;">
                                                <input type="text" name="remark[{{ $payrollEmployee->id }}]"
                                                    value="{{ $payrollEmployee->remarks }}" class="form-control"
                                                    placeholder="Remark here..">
                                            </div>
                                        </td>
                                    </tr>
                                {{-- @endif --}}
                            @endforeach
                        @endif
                    </tbody>
                    <footer>
                        <td colspan="20" class="text-center">Total</td>
                        @foreach ($payrollEmployee->incomes as $income)
                            @if (optional($income->incomeSetup)->monthly_income == 11)
                                <td><b>{{ number_format(${'grandIncomeAmount' . optional($income->incomeSetup)->id}, 2) }}</b>
                                </td>
                            @endif
                        @endforeach
                        @if ($leaveEncashmentSetupStatus)
                            <td>{{ @$grandencashmentValue ?? 0 }}</td>
                        @endif
                        {{-- <td>{{ round($grandArrearAmount, 2) }}</td> --}}
                        {{-- <td>{{ round($grandOverTimePay, 2) }}</td> --}}
                        <td>{{ round($grandTotalIncome, 2) }}</td>
                        {{-- <td>{{ round($grandLeaveAmount, 2) }}</td> --}}
                        @foreach ($payrollEmployee->deductions as $deduction)
                            <td><b>{{ number_format(${'grandDeductionAmount' . $deduction->id}, 2) }}</b></td>
                        @endforeach
                        {{-- <td>{{ round($totalFineAndPenalty, 2) }}</td> --}}
                        <td>{{ round($grandTotalDeduction, 2) }}</td>
                        <td>{{ round($grandTotalSalary, 2) }}</td>
                        {{-- <td>{{ round($grandFestivalBonus, 2) }}</td> --}}
                        <td>{{ round($grandYearlyTaxableSalary, 2) }}</td>
                        <td>{{ round($grandSst, 2) }}</td>
                        <td>{{ round($grandTds, 2) }}</td>
                        <td>{{ round($grandSingleWomenTaxCredit, 2) }}</td>
                        <td>{{ round($grandTax, 2) }}</td>
                        <td>{{ round($grandNetSalary, 2) }}</td>
                        @foreach ($payrollEmployee->taxExcludeValues as $taxExcludeValue)
                            <td>0</td>
                        @endforeach
                        <td>{{ round($grandAdjustment, 2) }}</td>
                        <td>{{ round($grandAdvance, 2) }}</td>
                        <td>{{ round($grandPayableSalary, 2) }}</td>
                        <td></td>

                    </footer>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Status :</label>
                    </div>
                    <div class="col-md-8">
                        <select id="status" name="status" class="form-control select-search">
                            <option value="1">Draft</option>
                            <option value="2">Final</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                    class="icon-backward2"></i></b>Go Back</a>
        <button type="button" data-toggle="modal" data-target="#modal_theme_warning_status"
            class="btn btn-success btn-labeled btn-labeled-left"><b><i class="icon-database-insert"
                    data-popup="tooltip" data-placement="bottom"></i></b>Save Changes</button>
    </div>
</form>

<!-- Warning modal -->
<div id="modal_theme_warning_status" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <center>
                    <i class="icon-alert text-danger icon-3x"></i>
                </center>
                <br>
                <center>
                    <p>Are you sure you want to save ? Please ensure you have clicked the 'Sync' button first, or the
                        updated data may not be restored.<br>
                        <button type="button" class="btn btn-success mt-3" id="confirmSave">Yes, Save It!</button>
                        <button type="button" class="btn btn-danger mt-3" data-dismiss="modal">Cancel</button>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- /warning modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tax Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="taxView"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
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
    $(document).on('click', '.viewTaxCalculation', function(e) {
        e.preventDefault();
        var employeeTotalIncome = $(this).attr('data-totalIncome');
        var employeeTotalDeduction = $(this).attr('data-totalDeduction');
        var employeeFestivalBonus = $(this).attr('data-festivalBonus');
        var employeePayrollEmployee = $(this).attr('data-payrollEmployee');
        var payrollId = "{{ @$payrollModel->id }}";
        var token = "{{ @csrf_token() }}";

        $.ajax({
            url: "{{ route('getTaxCalculation') }}",
            type: "post",
            data: {
                employeeTotalIncome: employeeTotalIncome,
                employeeTotalDeduction: employeeTotalDeduction,
                employeeFestivalBonus: employeeFestivalBonus,
                employeePayrollEmployee: employeePayrollEmployee,
                payrollId: payrollId,
                _token: token
            },
            success: function(response) {
                if (response.error) {
                    alert('Something Went Wrong !!');
                    $('#exampleModal').modal('hide');
                    return false;
                }
                // console.log('Hello Sumit',response);
                $('#taxView').replaceWith(response.view);
                $('#exampleModal').modal('show');
            }
        });
    });
    $(document).ready(function() {

        $('#confirmSave').on('click', function() {
            $('#myForm').submit();

        });
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                var clone = $(table).clone(); // Clone the table for exporting

                // Remove hidden input elements from the clone
                clone.find('input[type="hidden"]').remove();
                // Optionally remove specific elements
                clone.find('td span.email').remove();

                clone.find('td').each(function() {
                    var mediaTitle = $(this).find('.media-title').text().trim();
                    var adjustmentInput = $(this).find('input.adjustment').val();
                    if (mediaTitle) {
                        $(this).text(
                        mediaTitle); // Replace <td> content with only the employee's name
                    } else if (adjustmentInput) {
                        $(this).text(
                        adjustmentInput); // Replace <td> content with only the adjustment value
                    } else {
                        var divContent = $(this).find('div').text().trim();
                        if (divContent) {
                            $(this).text(
                            divContent); // Use the text inside the <div> if available
                        } else {
                            var inputValue = $(this).find('input').val();
                            if (inputValue) {
                                $(this).text(
                                inputValue); // If <input> is present, use its value
                            }
                        }
                    }
                });

                clone.find('tr').each(function() {
                    $(this).find('.noExl').remove();
                });

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
    });
</script>
<script>
    $(document).ready(function() {

        $('#status').on('change', function() {
            var type = $(this).val();
            if (type == '2') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: "Payroll will be lock now. You can't modify the data of the payroll."
                });
            }
        });

        $('#payroll_sync').on('click', function() {
            var lines = $('.myLine');
            var promises = [];
            // $('#cover-spin').show();
            $('#loading').show();

            lines.each(function() {
                var $this = $(this);
                calculationTotalIncomeAll($this);
                calculationTotalDeductionAll($this);
                promises.push(reCalculateAll($this));
                calculation($this);
            });

            // Wait for all promises to resolve before continuing
            Promise.all(promises).then(function() {
                // $('#cover-spin').hide();
                $('#loading').hide();
                $('#myForm').submit();

                // Code to execute after all AJAX operations are complete
            });
        });

        $('.income').on('keydown', function(event) {
            var amount = $(this).val();
            if (event.key == 'Tab') {
                calculationTotalIncome($(this), amount);
            }
        });

        $('.overtimePay').on('keydown', function(event) {
            if (event.key == 'Tab') {
                calculationTotalIncome($(this));
            }
        });

        $('.deduction').on('keydown', function(event) {
            var amount = $(this).val();
            if (event.key == 'Tab') {
                calculationTotalDeduction($(this), amount);
            }
        });

        $('.fineAndPenalty, .leave_amount').on('keydown', function(event) {
            if (event.key == 'Tab') {
                calculationTotalDeduction($(this));
            }
        });
        $('.taxExcludeValue').on('keyup', function(event) {
            calculation($(this));
        });

        $('.totalIncome, .totalDeduction, .festivalBonus').on('keydown', function(event) {
            if (event.key == 'Tab') {
                reCalculate($(this));
            }
        });

        $('.sst, .tds, .adjustment').on('keyup', function(event) {
            calculation($(this));
        });

        $('.adjustmentMode').on('change', function(event) {
            calculation($(this));
        });

        // reCalculate(context);

        function calculationTotalIncome(context, amount = 0) {
            // var totalIncome = parseFloat(context.closest('.myLine').find('.totalIncome').val());
            var totalIncome = 0;
            var overtimePay = parseFloat(context.closest('.myLine').find('.overtimePay').val());
            // var unpaid_leave = parseInt(context.closest('.myLine').find('.unpaid_leave').val());
            // var total_days = parseInt(context.closest('.myLine').find('.total_days').val());
            if (overtimePay) {
                totalIncome += overtimePay;
            }
            context.closest('.myLine').find('.income').each(function() {
                var income = parseFloat($(this).val());
                totalIncome += income;
            });
            context.closest('.myLine').find('.totalIncome').val(totalIncome.toFixed(2));
            // var leave_amount = (totalIncome / total_days) * unpaid_leave;
            // context.closest('.myLine').find('.leave_amount').val(leave_amount.toFixed(2));
            reCalculate(context);
        }

        function calculationTotalIncomeAll(context, amount = 0) {
            // var totalIncome = parseFloat(context.closest('.myLine').find('.totalIncome').val());
            var totalIncome = 0;
            var overtimePay = parseFloat(context.closest('.myLine').find('.overtimePay').val());
            // var unpaid_leave = parseInt(context.closest('.myLine').find('.unpaid_leave').val());
            // var total_days = parseInt(context.closest('.myLine').find('.total_days').val());
            if (overtimePay) {
                totalIncome += overtimePay;
            }
            context.closest('.myLine').find('.income').each(function() {
                var income = parseFloat($(this).val());
                totalIncome += income;
            });
            context.closest('.myLine').find('.totalIncome').val(totalIncome.toFixed(2));
            // var leave_amount = (totalIncome / total_days) * unpaid_leave;
            // context.closest('.myLine').find('.leave_amount').val(leave_amount.toFixed(2));
            // reCalculateAll(context);
        }

        function calculationTotalDeduction(context, amount = 0) {
            // var totalDeduction = parseFloat(context.closest('.myLine').find('.totalDeduction').val());
            var totalDeduction = 0;
            var fineAndPenalty = parseFloat(context.closest('.myLine').find('.fineAndPenalty').val());
            var leaveAmount = parseFloat(context.closest('.myLine').find('.leave_amount').val());
            if (fineAndPenalty) {
                totalDeduction += fineAndPenalty;
            }
            if (leaveAmount) {
                totalDeduction += leaveAmount;
            }
            context.closest('.myLine').find('.deduction').each(function() {
                var deduction = parseFloat($(this).val());
                totalDeduction += deduction;
            });
            // totalDeduction += parseFloat(amount);
            context.closest('.myLine').find('.totalDeduction').val(totalDeduction.toFixed(2));
            reCalculate(context);
        }

        function calculationTotalDeductionAll(context, amount = 0) {
            // var totalDeduction = parseFloat(context.closest('.myLine').find('.totalDeduction').val());
            var totalDeduction = 0;
            var fineAndPenalty = parseFloat(context.closest('.myLine').find('.fineAndPenalty').val());
            var leaveAmount = parseFloat(context.closest('.myLine').find('.leave_amount').val());
            if (fineAndPenalty) {
                totalDeduction += fineAndPenalty;
            }
            if (leaveAmount) {
                totalDeduction += leaveAmount;
            }
            context.closest('.myLine').find('.deduction').each(function() {
                var deduction = parseFloat($(this).val());
                totalDeduction += deduction;
            });
            // totalDeduction += parseFloat(amount);
            context.closest('.myLine').find('.totalDeduction').val(totalDeduction.toFixed(2));
            // reCalculateAll(context);
        }

        function reCalculate(context) {
            var payrollEmployeeId = parseFloat(context.closest('.myLine').find('.payrollEmployeeId').val());
            var totalIncome = parseFloat(context.closest('.myLine').find('.totalIncome').val());
            var totalDeduction = parseFloat(context.closest('.myLine').find('.totalDeduction').val());
            var totalSalary = totalIncome - totalDeduction;
            var totalSalary = totalSalary.toFixed(2);
            // var formatedTotalSalary = totalSalary.toLocaleString('hi-IN');
            context.closest('.myLine').find('.totalSalary').html(totalSalary);
            var festivalBonus = parseFloat(context.closest('.myLine').find('.festivalBonus').val());

            $('#cover-spin').show();

            $.ajax({
                type: 'POST',
                url: '/admin/payroll/recalculate',
                data: {
                    _token: '<?php echo csrf_token(); ?>',
                    payrollEmployeeId: payrollEmployeeId,
                    totalIncome: totalIncome,
                    totalDeduction: totalDeduction,
                    festivalBonus: festivalBonus
                },
                success: function(data) {
                    context.closest('.myLine').find('#taxableAmount').html(data.taxableSalary);
                    context.closest('.myLine').find('.taxableAmount').val(data.taxableSalary);
                    context.closest('.myLine').find('.sst').val(data.sst);
                    context.closest('.myLine').find('.tds').val(data.tds);
                    context.closest('.myLine').find('.single_women_tax').val(data.single_women_tax);
                    context.closest('.myLine').find('#single_women_tax').html(data
                    .single_women_tax);
                    if (data.single_women_tax) {
                        context.closest('.myLine').find('#gender').val(2);
                    } else {
                        context.closest('.myLine').find('#gender').val(1);
                    }
                    var netSalary = totalIncome - (totalDeduction + data.sst + data.tds);
                    netSalary = netSalary.toFixed(2);
                    context.closest('.myLine').find('.netSalary').val(netSalary);
                    context.closest('.myLine').find('#netSalary').html(netSalary);

                    calculation(context);
                    // callback();

                    $('#cover-spin').hide();
                }
            });
        }

        function reCalculateAll(context) {
            return new Promise(function(resolve, reject) {
                var payrollEmployeeId = parseFloat(context.closest('.myLine').find('.payrollEmployeeId')
                    .val());
                var totalIncome = parseFloat(context.closest('.myLine').find('.totalIncome').val());
                var totalDeduction = parseFloat(context.closest('.myLine').find('.totalDeduction')
                .val());
                var festivalBonus = parseFloat(context.closest('.myLine').find('.festivalBonus').val());

                // $('#cover-spin').show();

                $.ajax({
                    type: 'POST',
                    url: '/admin/payroll/recalculate',
                    data: {
                        _token: '<?php echo csrf_token(); ?>',
                        payrollEmployeeId: payrollEmployeeId,
                        totalIncome: totalIncome,
                        totalDeduction: totalDeduction,
                        festivalBonus: festivalBonus
                    },
                    success: function(data) {
                        context.closest('.myLine').find('#taxableAmount').html(data
                            .taxableSalary);
                        context.closest('.myLine').find('.taxableAmount').val(data
                            .taxableSalary);
                        context.closest('.myLine').find('.sst').val(data.sst);
                        context.closest('.myLine').find('.tds').val(data.tds);
                        var netSalary = totalIncome - (totalDeduction + data.sst + data
                        .tds);
                        netSalary = netSalary.toFixed(2);
                        context.closest('.myLine').find('.netSalary').val(netSalary);
                        context.closest('.myLine').find('#netSalary').html(netSalary);

                        calculation(context);

                        // $('#cover-spin').hide();

                        resolve(); // Resolve the promise when AJAX operation is complete
                    },
                    error: function(xhr, status, error) {
                        reject(error); // Reject the promise if there's an error
                    }
                });
            });
        }

        function calculation(context) {
            var totalTaxExcludeAmount = 0;
            var totalIncome = parseFloat(context.closest('.myLine').find('.totalIncome').val());
            var totalDeduction = parseFloat(context.closest('.myLine').find('.totalDeduction').val());
            var festivalBonus = parseFloat(context.closest('.myLine').find('.festivalBonus').val());
            var sst = parseFloat(context.closest('.myLine').find('.sst').val());
            var tds = parseFloat(context.closest('.myLine').find('.tds').val());
            var gender = parseInt(context.closest('.myLine').find('#gender').val());
            if (gender == 2) {
                var singleWomen = 0.1 * (sst + tds);
            } else {
                var singleWomen = 0;
            }
            singleWomen = singleWomen.toFixed(2);
            context.closest('.myLine').find('.single_women_tax').val(singleWomen);
            context.closest('.myLine').find('#single_women_tax').html(singleWomen);
            var singleWomen = parseFloat(context.closest('.myLine').find('.single_women_tax').val());
            context.closest('.myLine').find('.taxExcludeValue').each(function() {
                var taxExcludeValue = parseFloat($(this).val());
                var type = $(this).closest('.taxExclude').find('.taxExcludeValue').attr('data-type');
                if (type == 1) {
                    totalTaxExcludeAmount += taxExcludeValue;
                } else {
                    totalTaxExcludeAmount -= taxExcludeValue;
                }
            });
            var totalTax = sst + tds - singleWomen;
            totalTax = totalTax.toFixed(2);
            context.closest('.myLine').find('#totalTax').html(totalTax);
            var netSalary = totalIncome + festivalBonus - (totalDeduction + sst + tds - singleWomen);
            netSalary = netSalary.toFixed(2);
            context.closest('.myLine').find('.netSalary').val(netSalary);
            context.closest('.myLine').find('#netSalary').html(netSalary);
            var netSalary = parseFloat(context.closest('.myLine').find('.netSalary').val());
            var adjustmentMode = context.closest('.myLine').find('.adjustmentMode').val();
            var adjustment = parseFloat(context.closest('.myLine').find('.adjustment').val());
            var advanceAmount = parseFloat(context.closest('.myLine').find('.advanceAmount').val());
            var payableSalary = netSalary - advanceAmount + totalTaxExcludeAmount;
            if (adjustmentMode == 'add') {
                payableSalary = payableSalary + adjustment;
            } else {
                payableSalary = payableSalary - adjustment;
            }
            payableSalary = payableSalary.toFixed(2);
            // var formatedPayableSalary = payableSalary.toLocaleString('hi-IN');
            context.closest('.myLine').find('.payableSalary').val(payableSalary);
            context.closest('.myLine').find('#payableSalary').html(payableSalary);
        }
    });
</script>
@endSection
