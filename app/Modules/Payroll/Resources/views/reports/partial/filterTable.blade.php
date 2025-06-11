<table class="table table-responsive table-bordered" id="table2excel">
    <thead>
        <tr class="text-light btn-slate">
            <th rowspan="2" class="freeze-head freeze1 hd">S.N</th>
            <th rowspan="2" class="freeze-head freeze2 hd">Employee Name</th>

            @foreach ($staticColumn as $stColumn)
            <th rowspan="2" class="freeze-head">{{ $stColumn }}</th>
            @endforeach
            <th colspan="{{ $incomeCount }}" class="text-center freeze-head">Income</th>
            <th colspan="{{ $deductionCount }}" class="text-center freeze-head">Deduction</th>
        </tr>
        <tr class="text-white">
            @foreach ($incomes as $income)
                <th>{{ $income }}</th>
            @endforeach

            @foreach ($staticIncomes as $stIncome)
                <th>{{ $stIncome }}</th>
            @endforeach

            @foreach ($deductions as $deduction)
                <th>{{ $deduction }}</th>
            @endforeach

            @foreach ($staticDeduction as $stDeduction)
                <th>{{ $stDeduction }}</th>
            @endforeach
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
                @php
                    $attendance = $payrollEmployee->calculateAttendance($calenderType, $payrollYear, $payrollMonth);
                    $leave = $payrollEmployee->calculateLeave($calenderType, $payrollYear, $payrollMonth);
                    $total_paid_leave = $leave['paidLeaveTaken'];
                    $total_leave = $leave['unpaid_days'] + $leave['unpaidLeaveTaken'] ;
                @endphp
                @foreach ($staticColumn as $skey => $stColumn)

                    @if($skey == '001')
                        <td>{{ $joinDate }}</td>
                    @endif

                    @if($skey == '002')
                        <td>{{ $payrollEmployee->marital_status ? optional($payrollEmployee->getMaritalStatus)->dropvalue : optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue }}</td>
                    @endif

                    @if($skey == '003')
                        <td>{{ optional(optional($payrollEmployee->employee)->getGender)->dropvalue }}</td>
                    @endif

                    @if($skey == '004')
                        @if ($terminatedDate && ($payrollYear == $terminatedYear && $payrollMonth == $terminatedMonth))
                            <td>{{ $terminatedDay }}</td>
                        @elseif ($payrollYear == $joinYear && $payrollMonth == $joinMonth)
                            <td>{{ $attendance['total_days'] - $joinDay + 1 }}</td>
                        @else
                            <td>{{ $attendance['total_days'] }}</td>
                        @endif
                    @endif

                    @if($skey == '005')
                        <td>{{ $attendance['working_days'] }}</td>
                    @endif

                    @if($skey == '006')
                        <td>{{ $attendance['extra_working_days'] }}</td>
                    @endif

                    @if($skey == '007')
                        <td>{{ $payrollEmployee->paid_leave_days }}</td>
                    @endif

                    @if($skey == '008')
                        <td>{{ $payrollEmployee->unpaid_leave_days }}</td>
                    @endif

                @endforeach




                @foreach ($payrollEmployee->incomes as $income)

                    @if (optional($income->incomeSetup)->monthly_income == 11)
                        @if (isset($_GET['incomes_id']))
                                @if (array_key_exists($income->income_setup_id, $incomes))
                                    @php
                                        $incomeAmount = $income->value;
                                        $totalIncome = $totalIncome + $incomeAmount;
                                        ${'grandIncomeAmount' . optional($income->incomeSetup)->id} += $incomeAmount;
                                    @endphp
                                    <td>{{ number_format($incomeAmount, 2) }}</td>
                                @endif
                            @else
                                @php
                                    $incomeAmount = $income->value;
                                    $totalIncome = $totalIncome + $incomeAmount;
                                    ${'grandIncomeAmount' . optional($income->incomeSetup)->id} += $incomeAmount;
                                @endphp
                                <td>{{ number_format($incomeAmount, 2) }}</td>
                        @endif
                    @endif
                @endforeach

                @foreach ($staticIncomes as $key => $stIncome)
                    @if($key == '001')
                        @php
                            $arrear_amount = $payrollEmployee->arrear_amount ?? 0;
                            $grandArrearAmount += $arrear_amount;
                        @endphp
                        <td>{{ number_format($arrear_amount, 2) }}</td>
                    @endif
                    @if($key == '002')
                    <td>
                        @php
                            $overTimePay = $payrollEmployee->overtime_pay ?? 0;
                            $grandOverTimePay += $overTimePay;
                        @endphp
                        <div style="width: 80px;">
                            {{ number_format($overTimePay, 2) }}
                        </div>
                    </td>
                    @endif
                @endforeach

                @foreach ($payrollEmployee->deductions as $deduction)
                    @if(isset($_GET['deduction_id']))
                        @if (array_key_exists($deduction->deduction_setup_id, $deductions))
                            @php
                                $total_deduction = $deduction->value;
                                $totalDeduction = $totalDeduction + $total_deduction;
                                ${'grandDeductionAmount' . optional($deduction->deductionSetup)->id} += $total_deduction;
                            @endphp
                            <td>{{ $deduction->value ?? 0 }}</td>
                        @endif
                    @else
                        @php
                            $total_deduction = $deduction->value;
                            $totalDeduction = $totalDeduction + $total_deduction;
                            ${'grandDeductionAmount' . optional($deduction->deductionSetup)->id} += $total_deduction;
                        @endphp
                        <td>{{ $deduction->value ?? 0 }}</td>
                    @endif
                @endforeach
                @foreach ($staticDeduction as $key => $stDeduction)
                    @if($key == '001')
                        <td>
                            @php
                                $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
                                $totalFineAndPenalty += $fineAndPenalty;
                            @endphp
                            <div style="width: 80px;">
                                {{ number_format($fineAndPenalty, 2) }}
                            </div>
                        </td>
                    @endif
                    @if($key == '002')
                        @php
                            $leaveAmount = $payrollEmployee->leave_amount ?? 0;
                            $grandLeaveAmount += $leaveAmount;
                            $extra_working_amount = round(($totalIncome / 23) * 1, 2);
                        @endphp
                        <td>{{ round($leaveAmount, 2) }}</td>
                    @endif
                @endforeach
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
        <td colspan="{{ count($staticColumn) + 2 }}" class="text-center">Total</td>
        @foreach ($payrollEmployee->incomes as $income)
            @if (optional($income->incomeSetup)->monthly_income == 11)
                @if (isset($_GET['incomes_id']))
                    @if (array_key_exists($income->income_setup_id, $incomes))
                        <td><b>{{ number_format(${'grandIncomeAmount' . optional($income->incomeSetup)->id}, 2) }}</b></td>
                    @endif
                @else
                <td><b>{{ number_format(${'grandIncomeAmount' . optional($income->incomeSetup)->id}, 2) }}</b></td>
                @endif

            @endif
        @endforeach
        @foreach ($staticIncomes as $key => $stIncome)
            @if($key == '001')
                <td>{{ round($grandArrearAmount, 2) }}</td>
            @endif
            @if($key == '002')
                <td>{{ round($grandOverTimePay, 2) }}</td>
            @endif
        @endforeach
        @foreach ($payrollEmployee->deductions as $deduction)
            @if (isset($_GET['deduction_id']))
                @if (array_key_exists($deduction->deduction_setup_id, $deductions))
                    <td><b>{{ number_format(${'grandDeductionAmount' . $deduction->id}, 2) }}</b></td>
                @endif
            @else
                <td><b>{{ number_format(${'grandDeductionAmount' . $deduction->id}, 2) }}</b></td>
            @endif
        @endforeach
        @foreach ($staticDeduction as $key => $stDeduction)
            @if($key == '001')
                <td>{{ round($totalFineAndPenalty, 2) }}</td>
            @endif
            @if($key == '002')
                <td>{{ round($grandLeaveAmount, 2) }}</td>
            @endif
        @endforeach
        @endif
    </footer>
</table>
