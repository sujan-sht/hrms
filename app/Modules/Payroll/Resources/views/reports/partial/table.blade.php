<table class="table table-responsive table-bordered" id="table2excel">
    <thead>
        <tr class="text-light btn-slate">
            <th rowspan="2" class="freeze-head freeze1 hd">S.N</th>
            <th rowspan="2" class="freeze-head freeze2 hd">Employee Name</th>
            <th rowspan="2" class="freeze-head">Join Date</th>
            <th rowspan="2" class="freeze-head">Marital Status</th>
            <th rowspan="2" class="freeze-head">Gender</th>
            <th rowspan="2" class="freeze-head">Total Days</th>
            <th rowspan="2" class="freeze-head">Total Worked Days</th>
            <th rowspan="2" class="freeze-head">Extra Working Days</th>
            <th rowspan="2" class="freeze-head">Total Paid Leave Days</th>
            <th rowspan="2" class="freeze-head">Total Unpaid Leave Days</th>
            {{-- <th rowspan="2">Total Days For Payment</th> --}}
            {{-- @if($request->has('incomes_id')) --}}
            <th colspan="{{ count($incomes) + 3 }}" class="text-center freeze-head">Income</th>
            <th colspan="{{ count($deductions) + 3 }}" class="text-center freeze-head">Deduction</th>
            <th rowspan="2" class="freeze-head">Total Salary</th>
            <th rowspan="2" class="freeze-head">Festival Bonus</th>
            <!-- <th rowspan="2" class="freeze-head">Total Salary With Bonus</th> -->
            <th rowspan="2" class="freeze-head">Taxable Amount (Yearly)</th>
            <th rowspan="2" class="freeze-head">SST</th>
            <th rowspan="2" class="freeze-head">TDS</th>
            <th rowspan="2" class="freeze-head">Single Women Tax Credit(10% of SST + TDS )</th>
            <th rowspan="2" class="freeze-head">Total Tax</th>
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
        {{-- @dd($incomes); --}}
        <tr class="text-white">
            @foreach ($incomes as $income)
                <th>{{ $income }}</th>
            @endforeach

            <th>Arrear Amount</th>
            <th>Over-Time Pay</th>

            <th style="padding: 0px 60px;">Total</th>
            <th>Leave Amount</th>
            @foreach ($deductions as $deduction)
                <th>{{ $deduction }}</th>
            @endforeach
            <th>Fine & Penalty</th>
            <th style="padding: 0px 60px;">Total</th>
        </tr>
    </thead>
    <tbody>
        @if(!is_null($payrollEmployees))

        @php
            foreach ($incomes as $id => $title) {
                ${'grandIncomeAmount' . $id} = 0;
            }
            $grandArrearAmount = 0;
            $grandOverTimePay = 0;
            $grandTotalIncome = 0;
            foreach ($deductions as $id => $title) {
                ${'grandDeductionAmount'. $id} = 0;
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
        @endphp
        @foreach ($payrollEmployees as $key => $payrollEmployee)
            @php
            $totalDeduction = 0;
            $totalIncome = 0;
            $total_days = 0;
            if ($calenderType == 'nep') {
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
            <tr class="myLine">
                <td class="freeze1 table-bg-white">
                    #{{ ++$key }}
                    <input type="hidden" name="payrollEmployeeId" value="{{ $payrollEmployee->id }}"
                        class="payrollEmployeeId">
                </td>
                <td class="freeze2 table-bg-white">
                    <div class="media">
                        <div class="mr-3">
                            <img src="{{ optional($payrollEmployee->employee)->getImage() }}"
                                class="rounded-circle" width="40" height="40" alt="">
                        </div>
                        <div class="media-body">
                            <div class="media-title font-weight-semibold">
                                {{ optional($payrollEmployee->employee)->getFullName() }}</div>
                            <span
                                class="email text-muted">{{ optional($payrollEmployee->employee)->official_email }}</span>
                        </div>
                    </div>
                </td>
                <td>{{ $joinDate }}</td>
                <td>{{ $payrollEmployee->marital_status ? optional($payrollEmployee->getMaritalStatus)->dropvalue : optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue }}
                </td>
                <td>{{ optional(optional($payrollEmployee->employee)->getGender)->dropvalue }}</td>
                @php
                    $attendance = $payrollEmployee->calculateAttendance($calenderType, $payrollYear, $payrollMonth);
                    $leave = $payrollEmployee->calculateLeave($calenderType, $payrollYear, $payrollMonth);
                    $total_paid_leave = $leave['paidLeaveTaken'];
                    $total_leave = $leave['unpaid_days'] + $leave['unpaidLeaveTaken'] ;
                @endphp
                @if ($terminatedDate && ($payrollYear == $terminatedYear && $payrollMonth == $terminatedMonth))
                    <td>{{ $terminatedDay }}</td>
                @elseif ($payrollYear == $joinYear && $payrollMonth == $joinMonth)
                    <td>{{ $attendance['total_days'] - $joinDay + 1 }}</td>
                @else
                    <td>{{ $attendance['total_days'] }}</td>
                @endif
                <td>{{ $attendance['working_days'] }}</td>
                <td>{{ $attendance['extra_working_days'] }}</td>
                <td>{{ $payrollEmployee->paid_leave_days }}</td>
                <td>{{ $payrollEmployee->unpaid_leave_days }}</td>


                @foreach ($payrollEmployee->incomes as $income)
                    @if (optional($income->incomeSetup)->monthly_income == 11)
                        @php
                            $incomeAmount = $income->value;
                            $totalIncome = $totalIncome + $incomeAmount;
                            ${'grandIncomeAmount' . optional($income->incomeSetup)->id} += $incomeAmount;
                        @endphp
                        <td>{{ number_format($incomeAmount, 2) }}</td>
                    @endif
                @endforeach

                @php
                    $arrear_amount = $payrollEmployee->arrear_amount ?? 0;
                    $grandArrearAmount += $arrear_amount;
                @endphp
                <td>{{ number_format($arrear_amount, 2) }}</td>

                <td>
                    @php
                        $overTimePay = $payrollEmployee->overtime_pay ?? 0;
                        $grandOverTimePay += $overTimePay;
                    @endphp
                    <div style="width: 80px;">
                        {{ number_format($overTimePay, 2) }}
                    </div>
                </td>
                <td>
                    @php
                        $totalIncome = isset($payrollEmployee->total_income) ? $payrollEmployee->total_income : $totalIncome + $arrear_amount + $overTimePay;
                        $grandTotalIncome += $totalIncome;
                    @endphp
                    {{ number_format($totalIncome, 2) }}
                </td>
                @php
                    $leaveAmount = $payrollEmployee->leave_amount ?? 0;
                    $grandLeaveAmount += $leaveAmount;
                    $extra_working_amount = round(($totalIncome / 23) * 1, 2);
                @endphp
                <td>{{ round($leaveAmount, 2) }}</td>


                @foreach ($payrollEmployee->deductions as $deduction)
                    @php
                        $total_deduction = $deduction->value;
                        $totalDeduction = $totalDeduction + $total_deduction;
                        ${'grandDeductionAmount' . optional($deduction->deductionSetup)->id} += $total_deduction;
                    @endphp
                    <td>{{ $deduction->value ?? 0 }}</td>
                @endforeach
                <td>
                    @php
                        $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
                        $totalFineAndPenalty += $fineAndPenalty;
                    @endphp
                    <div style="width: 80px;">
                        {{ number_format($fineAndPenalty, 2) }}
                    </div>
                </td>

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
                <td>
                    <div style="width: 80px;">
                        @php
                            $festivalBonus = $payrollEmployee->festival_bonus ? $payrollEmployee->festival_bonus : 0;
                            $grandFestivalBonus += $festivalBonus;
                        @endphp
                        {{ number_format($festivalBonus, 2) }}
                    </div>
                </td>
                <td>
                    @php
                        $taxableAmount = $payrollEmployee->yearly_taxable_salary ? number_format($payrollEmployee->yearly_taxable_salary, 2) : 0;
                        $grandYearlyTaxableSalary += (float) $taxableAmount;
                    @endphp
                    <div id="taxableAmount">{{ $taxableAmount }}</div>
                    <input type="hidden" name="yearly_taxable_salary[{{ $payrollEmployee->id }}]"
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
                        $single_women_tax_credit = isset($payrollEmployee->single_women_tax_credit) ? $payrollEmployee->single_women_tax_credit : 0;
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
                <td>
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
                        $advance = isset($payrollEmployee->advance_amount) ? $payrollEmployee->advance_amount : 0;
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
            @endforeach
        @else
            <tr class="myLine">
                <td class="freeze1 table-bg-white" colspan="24">
                    No Payroll Record Found!!!
                </td>
            </tr>
        @endif

    </tbody>
    <footer>
        @if(!is_null($payrollEmployees))
        <td colspan="10" class="text-center">Total</td>
        @foreach ($payrollEmployee->incomes as $income)
            @if (optional($income->incomeSetup)->monthly_income == 11)
                <td><b>{{ number_format(${'grandIncomeAmount' . optional($income->incomeSetup)->id}, 2) }}</b></td>
            @endif
        @endforeach
        <td>{{ round($grandArrearAmount, 2) }}</td>
        <td>{{ round($grandOverTimePay, 2) }}</td>
        <td>{{ round($grandTotalIncome, 2) }}</td>
        <td>{{ round($grandLeaveAmount, 2) }}</td>
        @foreach ($payrollEmployee->deductions as $deduction)
                <td><b>{{ number_format(${'grandDeductionAmount' . $deduction->id}, 2) }}</b></td>
        @endforeach
        <td>{{ round($totalFineAndPenalty, 2) }}</td>
        <td>{{ round($grandTotalDeduction, 2) }}</td>
        <td>{{ round($grandTotalSalary, 2) }}</td>
        <td>{{ round($grandFestivalBonus, 2) }}</td>
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
        @endif
    </footer>
</table>
