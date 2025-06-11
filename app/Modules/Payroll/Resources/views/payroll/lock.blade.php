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
        padding: 10px 30px;
        /* border: 1px solid #ddd; */
        /* background: #fff; */
        /* white-space: nowrap; */
        vertical-align: top;
        text-align: center;
    }

    .table-scroll th {
        /* padding: 5px 5px; */
        /* border: 1px solid #ddd; */
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
        left: 79px;
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
                        <th rowspan="2" class="freeze-head">Total Worked Days</th>
                        <th rowspan="2" class="freeze-head">Extra Working Days</th>
                        <th rowspan="2" class="freeze-head">Total Paid Days</th>
                        <th rowspan="2" class="freeze-head">Total Unpaid Leave Days</th>
                        <th rowspan="2" class="freeze-head">Total Payable Days</th>
                        {{-- <th rowspan="2" class="freeze-head">Total Days For Payment</th> --}}
                        <th colspan="{{ count($incomes) + ($leaveEncashmentSetupStatus ? 2 : 1) }}"
                            class="text-center freeze-head">Income</th>
                        <th colspan="{{ count($deductions) + 1 }}" class="text-center freeze-head">Deduction</th>
                        <th rowspan="2" class="freeze-head">Total Salary</th>
                        {{-- <th rowspan="2" class="freeze-head">Festival Bonus</th> --}}
                        <!-- <th rowspan="2" class="freeze-head">Total Salary With Bonus</th> -->
                        <th rowspan="2" class="freeze-head">Taxable Amount (Yearly)</th>
                        <th rowspan="2" class="freeze-head">SST</th>
                        <th rowspan="2" class="freeze-head">TDS</th>
                        <th rowspan="2" class="freeze-head">Single Women Tax Credit(10% of Total Tax)</th>
                        <th rowspan="2" class="freeze-head">Total Tax</th>
                        <th rowspan="2" class="freeze-head">Net Salary</th>
                        @foreach ($taxExcludeValues as $taxExcludeValue)
                            <th rowspan="2" class="freeze-head">{{ $taxExcludeValue }}</th>
                        @endforeach
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
                                // dd($incomes);
                                // if ($id != 0) {
                                ${'grandIncomeAmount' . $id} = 0;
                                // }
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
                            $num = 0;
                            $grandencashmentValue = 0;
                        @endphp
                        @foreach ($payrollModel->payrollEmployees as $key => $payrollEmployee)
                            {{-- @if ($payrollEmployee->employee_id == 33) --}}
                                @php
                                    $totalDeduction = 0;
                                    $totalIncome = 0;
                                    $total_days = 0;
                                    $encashmentValue = 0;
                                    if ($payrollModel->calendar_type == 'nep') {
                                        $joinDate = optional($payrollEmployee->employee)->nepali_join_date;
                                        $terminatedDate = optional($payrollEmployee->employee)->nep_archived_date;
                                    } else {
                                        $joinDate = optional($payrollEmployee->employee)->join_date;
                                        $terminatedDate = optional($payrollEmployee->employee)->archived_date;
                                    }
                                    $explodeJoinDate = explode('-', $joinDate);
                                    $joinMonth = (int) $explodeJoinDate[1];
                                    $joinDay = (int) $explodeJoinDate[2];
                                    $joinYear = $explodeJoinDate[0];
                                    if ($terminatedDate) {
                                        $explodeTerminatedDate = explode('-', $terminatedDate);
                                        $terminatedMonth = (int) $explodeTerminatedDate[1];
                                        $terminatedDay = (int) $explodeTerminatedDate[2];
                                        $terminatedYear = $explodeTerminatedDate[0];
                                    }
                                @endphp
                                @php
                                    $totalDeduction = 0;
                                    $totalIncome = 0;
                                    $total_days = 0;
                                @endphp
                                @if (
                                    $terminatedDate == null ||
                                        ($terminatedDate && ($payrollModel->year != $terminatedYear || $payrollModel->month != $terminatedMonth)))
                                    <tr class="myLine {{ $payrollEmployee->hold_status ? 'hold' : '' }}">
                                        <td
                                            class="freeze1 table-bg-white {{ $payrollEmployee->hold_status ? 'hold' : '' }}">
                                            #{{ ++$num }}
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
                                                        class="email text-muted">{{ optional($payrollEmployee->employee)->official_email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ optional($payrollEmployee->employee)->employee_code }}</td>
                                        <td>{{ optional(optional($payrollEmployee->employee)->branchModel)->name }}
                                        </td>
                                        <td>{{ optional(optional($payrollEmployee->employee)->designation)->dropvalue }}
                                        </td>
                                        <td>{{ $joinDate }}</td>
                                        <td>{{ $payrollEmployee->marital_status ? optional($payrollEmployee->getMaritalStatus)->dropvalue : optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue }}
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
                                            $payable_days =
                                                $attendance['total_days'] - $payrollEmployee->unpaid_leave_days;
                                            // dd($total_leave);
                                        @endphp
                                        @if ($terminatedDate && ($payrollModel->year == $terminatedYear && $payrollModel->month == $terminatedMonth))
                                            <td>{{ $terminatedDay }}</td>
                                        @elseif ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth)
                                            <td>{{ $attendance['total_days'] - $joinDay + 1 }}</td>
                                        @else
                                            <td>{{ $attendance['total_days'] }}</td>
                                        @endif
                                        <td>{{ $attendance['working_days'] }}</td>
                                        <td>{{ $attendance['extra_working_days'] }}</td>
                                        <td>{{ $payrollEmployee->paid_leave_days }}</td>
                                        <td>{{ $payrollEmployee->unpaid_leave_days }}</td>
                                        <td>{{ $payable_days }}</td>
                                        @foreach ($payrollEmployee->incomes as $income)
                                            @if (optional($income->incomeSetup)->monthly_income == 11)
                                                {{-- @dd(optional($payrollEmployee->incomes[3]->incomeSetup)->id); --}}
                                                @php
                                                    // if (optional($income->incomeSetup)->daily_basis_status == 11) {
                                                    //     if ($attendance['working_days'] == 0) {
                                                    //         $incomeAmount = 0;
                                                    //     } else {
                                                    //         $incomeAmount = $income->value * $attendance['working_days'];
                                                    //     }
                                                    // } else {
                                                    //     $incomeAmount = $income->value;
                                                    // }
                                                    $incomeAmount = $income->value;
                                                    $totalIncome = $totalIncome + $incomeAmount;
                                                    ${'grandIncomeAmount' .
                                                        optional($income->incomeSetup)->id} += $incomeAmount;
                                                @endphp
                                                <td>{{ number_format($incomeAmount, 2) }}</td>
                                            @endif
                                        @endforeach
                                        @if ($leaveEncashmentSetupStatus)
                                            @php
                                                $encashmentValue = $payrollEmployee->getEncashmentLog($payrollEmployee);
                                            @endphp
                                            <td>{{ @$encashmentValue ?? 0 }}</td>
                                        @endif
                                        @php
                                            $arrear_amount = $payrollEmployee->arrear_amount ?? 0;
                                            $grandArrearAmount += $arrear_amount;
                                            $grandencashmentValue += $encashmentValue;
                                        @endphp
                                        {{-- <td>{{ number_format($arrear_amount, 2) }}</td> --}}
                                        {{-- <td>
                                        @php
                                            $overTimePay = $payrollEmployee->overtime_pay ?? 0;
                                            $grandOverTimePay += $overTimePay;
                                        @endphp
                                        <div style="width: 80px;">
                                            {{ number_format($overTimePay, 2) }}
                                        </div>
                                    </td> --}}
                                        <td>
                                            @php
                                                $totalIncome = $payrollEmployee->total_income ?? 0;
                                                $grandTotalIncome += $totalIncome;
                                            @endphp
                                            {{ number_format($totalIncome, 2) }}
                                        </td>
                                        @php
                                            $leaveAmount = $payrollEmployee->leave_amount ?? 0;
                                            $grandLeaveAmount += $leaveAmount;
                                            $extra_working_amount = round(
                                                ($totalIncome / $attendance['total_days']) *
                                                    $attendance['extra_working_days'],
                                                2,
                                            );
                                        @endphp
                                        {{-- <td>{{ round($leaveAmount, 2) }}</td> --}}
                                        @foreach ($payrollEmployee->deductions as $deduction)
                                            @php
                                                $total_deduction = $deduction->value ?? 0;
                                                $totalDeduction = $totalDeduction + $total_deduction;
                                                ${'grandDeductionAmount' . $deduction->id} += $deduction->value ?? 0;
                                            @endphp
                                            <td>{{ $deduction->value ?? 0 }}</td>
                                        @endforeach
                                        {{-- <td>
                                        @php
                                            $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
                                            $totalFineAndPenalty += $fineAndPenalty;
                                        @endphp
                                        <div style="width: 80px;">
                                            {{ number_format($fineAndPenalty, 2) }}
                                        </div>
                                    </td> --}}
                                        <input type="hidden" name="total_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $attendance['total_days'] }}" class="form-control">
                                        <input type="hidden" name="extra_working_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $attendance['extra_working_days'] }}" class="form-control">
                                        <input type="hidden" name="unpaid_leave_days[{{ $payrollEmployee->id }}]"
                                            value="{{ $total_leave }}" class="form-control">
                                        <input type="hidden" name="leave_amount[{{ $payrollEmployee->id }}]"
                                            value="{{ round($leaveAmount, 2) }}" class="form-control">
                                        <td>
                                            @php
                                                $totalDeduction = $payrollEmployee->total_deduction ?? 0;
                                                $grandTotalDeduction += $totalDeduction;
                                            @endphp
                                            {{ number_format($totalDeduction, 2) }}
                                        </td>
                                        <td>
                                            @php
                                                $totalSalary = $totalIncome - $totalDeduction;
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
                                            {{ number_format($festivalBonus, 2) }}
                                        </div>
                                    </td> --}}
                                        <td>
                                            @php
                                                $taxableAmount = $payrollEmployee->yearly_taxable_salary
                                                    ? $payrollEmployee->yearly_taxable_salary
                                                    : 0;
                                                $grandYearlyTaxableSalary += $taxableAmount;
                                            @endphp
                                            {{-- <div id="taxableAmount"><a href = "{{route('payroll.taxCalculation',$payrollEmployee->id)}}" target="_blank">{{  number_format($taxableAmount,2)}}</a></div> --}}
                                            <div id="taxableAmount">{{ number_format($taxableAmount, 2) }}</div>
                                            <a href="javascript:;" class="viewTaxCalculation"
                                                data-totalIncome="{{ @$totalIncome }}"
                                                data-totalDeduction="{{ @$totalDeduction }}"
                                                data-festivalBonus="{{ @$festivalBonus }}"
                                                data-payrollEmployee="{{ @$payrollEmployee->id }}">
                                                View
                                            </a>
                                            <input type="hidden"
                                                name="yearly_taxable_salary[{{ $payrollEmployee->id }}]"
                                                value="{{ $taxableAmount }}" class="form-control">
                                        </td>
                                        <td>
                                            @php
                                                $sst = isset($payrollEmployee->sst) ? $payrollEmployee->sst : 0;
                                                $grandSst += $sst;
                                            @endphp
                                            <div style="width: 100px;">
                                                {{ number_format($sst, 2) }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $tds = isset($payrollEmployee->tds) ? $payrollEmployee->tds : 0;
                                                $grandTds += $tds;
                                            @endphp
                                            <div style="width: 100px;">
                                                {{ number_format($tds, 2) }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $single_women_tax_credit = isset(
                                                    $payrollEmployee->single_women_tax_credit,
                                                )
                                                    ? $payrollEmployee->single_women_tax_credit
                                                    : 0;
                                                $grandSingleWomenTaxCredit += $single_women_tax_credit;
                                            @endphp
                                            {{ round($single_women_tax_credit, 2) }}
                                        </td>
                                        @php
                                            $total_tax = $sst + $tds;
                                            $final_tax = $total_tax - $single_women_tax_credit;
                                            $grandTax += $final_tax;
                                        @endphp
                                        <td>
                                            <div id="totalTax">{{ round($final_tax, 2) }}</div>
                                        </td>
                                        {{-- <td>{{ $sst + $tds }}</td> --}}
                                        <td>
                                            {{-- @php $netSalary = $totalIncome - ($totalDeduction + $sst + $tds) + $extra_working_amount; @endphp --}}
                                            @php
                                                $netSalary = $payrollEmployee->net_salary ?? 0;
                                                $grandNetSalary += $netSalary;
                                            @endphp
                                            <div id="netSalary">{{ round($netSalary, 2) }}</div>
                                        </td>
                                        @foreach ($payrollEmployee->taxExcludeValues as $taxExcludeValue)
                                            <td>{{ $taxExcludeValue->value ?? 0 }}</td>
                                        @endforeach
                                        <td>
                                            @php $adjustment = isset($payrollEmployee->adjustment) ? $payrollEmployee->adjustment : 0; @endphp
                                            <div class="row" style="width: 100px;">
                                                <div class="col-md-12">
                                                    {{ number_format($adjustment, 2) }}
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
                                        </td>
                                        <td>
                                            @php
                                                $payableSalary = $payrollEmployee->payable_salary;
                                                $grandPayableSalary += $payableSalary;
                                            @endphp
                                            <div id="payableSalary">{{ round($payableSalary, 2) }}</div>
                                        </td>
                                        <td>
                                            <div style="width: 150px;">
                                                {{ $payrollEmployee->remarks }}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            {{-- @endif --}}
                        @endforeach
                    @endif
                </tbody>
                <footer>
                    <td colspan="19" class="text-center">Total</td>
                    @foreach ($payrollEmployee->incomes as $income)
                        @if (optional($income->incomeSetup)->monthly_income == 11)
                            <td><b>{{ number_format(${'grandIncomeAmount' . optional($income->incomeSetup)->id}, 2) }}</b>
                            </td>
                        @endif
                    @endforeach
                    @if ($leaveEncashmentSetupStatus)
                        <td>{{ @$grandencashmentValue ?? 0 }}</td>
                    @endif
                    {{-- <td>{{ round($grandArrearAmount, 2) }}</td>
                    <td>{{ round($grandOverTimePay, 2) }}</td> --}}
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
</div>

<div class="text-center">
    <a href="{{ route('payroll.index') }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
</div>

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
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
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
                $('#taxView').replaceWith(response.view);
                $('#exampleModal').modal('show');
            }
        });
    });
    $(document).ready(function() {
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                var clone = $(table).clone(); // Create a clone of the table for exporting
                // Remove hidden input elements from the clone
                clone.find('input[type="hidden"]').remove();
                clone.find('td span.email').remove();
                clone.find('td, th').each(function() {
                    var cleanHtml = $(this).html().replace(/<[^>]*>/g, ''); // Remove HTML tags
                    $(this).html(cleanHtml.trim()); // Trim any extra spaces
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
@endsection
