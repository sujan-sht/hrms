@extends('admin::layout')
@section('title') Payroll Employees @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll </a>
<a class="breadcrumb-item active">Payroll Employee</a>
@stop

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
            <h6 class="media-title font-weight-semibold">Employee Salary History :: </h6>

        </div>
    </div>
</div>
<div class="card card-body">
    <table class="table table-responsive table-striped table-bordered">
        <thead>
            <tr class="text-white">
                <th rowspan="2">S.N</th>
                <th rowspan="2">Employee Name</th>
                <th rowspan="2">Join Date</th>
                <th rowspan="2">Marital Status</th>
                <th rowspan="2">Gender</th>
                <th rowspan="2">Total Days</th>
                <th rowspan="2">Total Working Days</th>
                <th rowspan="2">Extra Working Days</th>
                <th rowspan="2">Total Paid Leave Days</th>
                <th rowspan="2">Total Unpaid Leave Days</th>
                <th colspan="{{ count($incomes) + 3 }}" class="text-center">Income</th>
                <th colspan="{{ count($deductions) + 3 }}" class="text-center">Deduction</th>
                <th rowspan="2">Total Salary</th>
                <th rowspan="2">Festival Bonus</th>
                <!-- <th rowspan="2">Total Salary With Bonus</th> -->
                <th rowspan="2">Taxable Amount (Yearly)</th>
                <th rowspan="2">SST</th>
                <th rowspan="2">TDS</th>
                <th rowspan="2">Total Tax</th>
                <!-- <th rowspan="2">Extra Working Days Amount</th> -->
                <th rowspan="2">Net Salary</th>
                <th rowspan="2">Single Women Tax Credit(10% of Total Tax)</th>
                <th rowspan="2">Adjustment</th>
                <th rowspan="2">Advance</th>
                <th rowspan="2">Payable Salary</th>
                <th rowspan="2">Remarks</th>
            </tr>
            <tr class="text-white">
                @foreach ($incomes as $income)
                    <th>{{ $income }}</th>
                @endforeach
                <th>Arrear Amount</th>
                <th>Over-Time Pay</th>
                <th style="padding: 0px 60px;">Total</th>
                <th>Leave Amount</th>
                {{-- <th>Total</th> --}}
                @foreach ($deductions as $deduction)
                    <th>{{ $deduction }}</th>
                @endforeach
                {{-- <th>Leave Amount</th>
                <th>Total</th> --}}
                <th>Fine & Penalty</th>
                <th style="padding: 0px 60px;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr class="myLine">
                <td>
                    #1
                    <input type="hidden" name="payrollEmployeeId" value="{{ $payrollEmployee->id }}"
                        class="payrollEmployeeId">
                </td>
                <td>
                    <div class="media">
                        <div class="mr-3">
                            <img src="{{ optional($payrollEmployee->employee)->getImage() }}" class="rounded-circle"
                                width="40" height="40" alt="">
                        </div>
                        <div class="media-body">
                            <div class="media-title font-weight-semibold">
                                {{ optional($payrollEmployee->employee)->getFullName() }}</div>
                            <span class="text-muted">{{ optional($payrollEmployee->employee)->official_email }}</span>
                        </div>
                    </div>
                </td>
                @if ($payrollEmployee->payroll->calendar_type == 'nep')
                    <td>{{ optional($payrollEmployee->employee)->nepali_join_date }}</td>
                @else
                    <td>{{ optional($payrollEmployee->employee)->join_date }}</td>
                @endif

                <td>{{ optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue }}</td>
                <td>{{ optional(optional($payrollEmployee->employee)->getGender)->dropvalue }}</td>

                <td>{{ $payrollEmployee->total_days ?? 0 }}</td>
                <td>{{ $payrollEmployee->total_working_days ?? 0 }}</td>
                <td>{{ $payrollEmployee->extra_working_days ?? 0 }}</td>
                <td>{{ $payrollEmployee->paid_leave_days ?? 0 }}</td>
                <td>{{ $payrollEmployee->unpaid_leave_days ?? 0 }}</td>
                @foreach ($payrollEmployee->incomes as $income)
                    {{-- {{dd($income)}} --}}
                    <td>{{ $income->value ? number_format($income->value, 2) : 0 }}</td>
                @endforeach
                @php
                    $arrear_amount = $payrollEmployee->arrear_amount ?? 0;
                @endphp
                <td>{{ number_format($arrear_amount, 2) }}</td>
                <td>
                    @php
                        $overTimePay = $payrollEmployee->overtime_pay ?? 0;
                    @endphp
                    <div style="width: 80px;">
                        {{ number_format($overTimePay, 2) }}
                    </div>
                </td>
                <td>

                    {{ $payrollEmployee->total_income ? number_format($payrollEmployee->total_income, 2) : 0 }}
                </td>

                <td>{{ round($payrollEmployee->leave_amount, 2) }}</td>
                @foreach ($payrollEmployee->deductions as $deduction)
                    <td>{{ $deduction->value ?? 0 }}</td>
                @endforeach
                <td>
                    @php
                        $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
                    @endphp
                    <div style="width: 80px;">
                        {{ number_format($fineAndPenalty, 2) }}
                    </div>
                </td>
                <td>
                    {{ $payrollEmployee->total_deduction ? number_format($payrollEmployee->total_deduction, 2) : 0 }}
                </td>
                <td>
                    @php
                        $totalIncome = $payrollEmployee->total_income ?? 0;
                        $totalDeduction = $payrollEmployee->total_deduction ?? 0;
                        $totalSalary = $totalIncome - $totalDeduction;
                    @endphp
                    <div class="totalSalary">{{ round($totalSalary, 2) }}</div>
                </td>
                <td>
                    <div style="width: 80px;">
                        @php $festivalBonus = $payrollEmployee->festival_bonus ? $payrollEmployee->festival_bonus : 0; @endphp
                        {{ number_format($festivalBonus, 2) }}
                    </div>
                </td>
                <td>
                    <div style="width: 80px;">
                        @php $yearlyTaxableSalary = $payrollEmployee->yearly_taxable_salary ? $payrollEmployee->yearly_taxable_salary : 0; @endphp
                        {{ number_format($yearlyTaxableSalary, 2) }}
                    </div>
                </td>
                <td>
                    @php $sst =  $payrollEmployee->sst ? $payrollEmployee->sst : 0 @endphp
                    <div style="width: 100px;">
                        {{ $sst }}
                    </div>
                </td>
                <td>
                    @php $tds =  $payrollEmployee->tds ? $payrollEmployee->tds : 0 @endphp
                    <div style="width: 100px;">
                        {{ $tds }}
                    </div>
                </td>
                <td>{{ number_format($sst + $tds, 2) }}</td>
                <td>
                    <div id="netSalary">{{ $payrollEmployee->net_salary ? round($payrollEmployee->net_salary, 2) : 0 }}
                    </div>
                </td>
                <td>
                    @php $single_women_tax_credit = isset($payrollEmployee->single_women_tax_credit) ? $payrollEmployee->single_women_tax_credit : 0; @endphp
                    {{ round($single_women_tax_credit, 2) }}
                </td>
                <td>
                    @php $adjustment = isset($payrollEmployee->adjustment) ? $payrollEmployee->adjustment : 0; @endphp
                    <div class="row" style="width: 100px;">
                        <div class="col-md-12">
                            {{ number_format($adjustment, 2) }}
                        </div>
                    </div>
                </td>
                <td>
                    @php $advance = isset($payrollEmployee->advanceAmount) ? $payrollEmployee->advanceAmount : 0; @endphp
                    {{ round($advance, 2) }}
                </td>
                <td>
                    <div id="payableSalary">{{ round($payrollEmployee->payable_salary, 2) }}</div>
                </td>
                <td>
                    <div style="width: 150px;">
                        {{ $payrollEmployee->remarks }}
                    </div>
                </td>
            </tr>

        </tbody>
    </table>
</div>

<div class="text-center">
    <a href="{{ route('payroll.index') }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
</div>


@endsection
