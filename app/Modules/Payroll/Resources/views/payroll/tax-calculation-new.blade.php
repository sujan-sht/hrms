
<div id="taxView">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <legend class="text-uppercase font-size-sm font-weight-bold">Basic Detail</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td width="30%">Employee Name : &nbsp;&nbsp;
                                            {{ optional($payrollEmployee->employee)->getFullName() }}</td>
                                        <td>Payroll Year : &nbsp;&nbsp; {{ $payrollEmployee->payroll->year }} </td>
                                        <td>Payroll Month : &nbsp;&nbsp;
                                            {{ $payrollEmployee->payroll->calendar_type == 'eng' ? date_converter()->_get_english_month($payrollEmployee->payroll->month) : date_converter()->_get_nepali_month($payrollEmployee->payroll->month) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Tax Calculation : &nbsp;&nbsp;
                                            {{ @$payrollEmployee->employee->tax_calculation }}</td>
                                        <td>Salary Paid Month : &nbsp;&nbsp; {{ @$salaryPaidMonth }} </td>
                                        @if ($payrollEmployee->employee->tax_calculation == 'actual')
                                            <td>Taxable Month : &nbsp;&nbsp; {{ @$salaryPaidMonth + 1 }}</td>
                                        @else
                                            <td>Taxable Month : &nbsp;&nbsp; {{ @$taxableMonth }}</td>
                                        @endif

                                    </tr>
                                    <tr>
                                        <td>Gross Salary : &nbsp;&nbsp;
                                            {{ optional(optional($payrollEmployee->employee)->employeeGrossSalarySetup)->gross_salary ?? 0 }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Calculation Of Tax</legend>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-white text-center">
                                        <th rowspan="2">Income</th>
                                        <th rowspan="2">Deduction</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incomes as $key => $income)
                                        <tr class="">
                                            @if(@$income['amount'] !=0)
                                                <td>{{ @$income['title'] }} = <span
                                                        class="text-right">{{ @$income['amount'] }}</span>
                                                </td>
                                            @endif
                                            @if(@$deductions[$key]['amount'] !=0)
                                                <td>
                                                    @isset($deductions[$key])
                                                        {{ @$deductions[$key]['title'] }} = <span
                                                            class="text-right">{{ @$deductions[$key]['amount'] }}</span>
                                                    @endisset
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr class="text-left text-white" style="background-color: #546e7a">
                                        <td>Total = <span class="text-white">{{ @$monthlyIncome }}</span></td>
                                        <td>Total = <span class="text-white">{{ @$monthlyDeduction }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Income
                                    </legend>
                                    <p>Previous Total Monthly Income: {{ @$previousTotalIncome+@$employeeModel->total_previous_income }}</p>
                                    <p>This Month Total Income: {{ @$monthlyIncome }}</p>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Deduction
                                    </legend>
                                    <p>Previous Total Monthly Deduction: {{ @$previousTotalDeduction + @$employeeModel->total_previous_deduction }}</p>
                                    <p>This Month Total Deduction: {{ @$monthlyDeduction }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Previous Payroll Detail</legend>
                                    <p>Total Previous Income: {{ @$employeeModel->total_previous_income ?? 0}}</p>
                                    <p>Total Previous Deduction: {{ @$employeeModel->total_previous_deduction ?? 0}}</p>
                                    <p>Total SST Paid: {{ @$employeeModel->total_sst_paid ?? 0}}</p>
                                    <p>Total TDS Paid: {{ @$employeeModel->total_tds_paid ?? 0}}</p>
                                </div>

                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Projected
                                        For Remaining Month</legend>
                                    <p>Income: {{ @$projectedTotalIncome }} * {{ @$remainingMonth }}
                                        ={{ @$projectedTotalIncome * @$remainingMonth }}</p>
                                    <p>Deduction: {{ @$projectedTotalDeduction }} * {{ @$remainingMonth }}
                                        ={{ @$projectedTotalDeduction * @$remainingMonth }}</p>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Total
                                        Annual Projected Income
                                        {{ $payrollEmployee->employee->tax_calculation == 'actual' ? '(Till this month)' : '' }}
                                    </legend>
                                    <p>Yearly Income:
                                        {{ @$previousTotalIncome + @$monthlyIncome + @$projectedTotalIncome * @$remainingMonth + @$employeeincomeYearly }}
                                    </p>
                                    <p>Yearly Deduction:
                                        {{ @$previousTotalDeduction + @$monthlyDeduction + @$projectedTotalDeduction * @$remainingMonth + @$employeedeductionYearly }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">
                                        Calculation</legend>
                                    @if ($payrollEmployee->bulk_upload == '2')
                                        <p colspan = "3">Total Income For the Year : &nbsp;&nbsp;
                                            {{ @$calculationString }} = <span
                                                class="text-danger">{{ number_format(round($payrollEmployee->yearly_taxable_salary, 2), 2) }}</span>
                                        </p>
                                    @else
                                        <p colspan = "3">Total Income For the Year : &nbsp;&nbsp;
                                            {{ @$calculationString }} = <span
                                                class="text-danger">{{ number_format(round($taxableAmount, 2), 2) }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-white text-center">
                                        <th rowspan="2" colspan ="3">Details</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php
                                        $sst = 0;
                                        $tds = 0;
                                        $total_tax = 0;
                                    @endphp
                                    @foreach ($taxDetail as $key => $tax)
                                        @php
                                            if ($key == 1) {
                                                $sst += $tax['tds'];
                                            } else {
                                                $tds += $tax['tds'];
                                            }
                                            $total_tax += $tax['tds'];
                                        @endphp
                                        <tr class="text-right">
                                            <td>{{ $tax['amount'] }}</td>
                                            <td>{{ $tax['rate'] }}</td>
                                            <td>{{ $tax['tds'] }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="text-right">
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    <tr class="text-right">
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    <tr class="text-right">
                                        <td class="text-bold">Total Yearly SST</td>
                                        <td></td>
                                        <td>{{ round($sst, 2) }}</td>
                                    </tr>
                                    <tr class="text-right">
                                        <td class="text-bold">Total SST For This Month</td>
                                        <td>Total Yearly SST / Taxable Month </td>
                                        <td>{{ round($sst / $taxableMonth, 2) }}</td>
                                    </tr>
                                    <tr class="text-right">
                                        <td class="text-bold">Total Yearly TDS</td>
                                        <td></td>
                                        <td>{{ round($tds, 2) }}</td>
                                    </tr>
                                    <tr class="text-right">
                                        <td class="text-bold">Total TDS For This Month</td>
                                        <td>Total Yearly TDS / Taxable Month </td>
                                        <td>{{ round($tds / $taxableMonth, 2) }}</td>
                                    </tr>
                                    <tr class="text-right">
                                        <td class="text-bold">Total Tax</td>
                                        <td>SST + TDS</td>
                                        <td>{{ round(($sst + $tds) / $taxableMonth, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Notes
                                    </legend>
                                    <label id="basic-error" class="validation-valid-label" for="basic">1 % slab range
                                        Tax is shown on SST column and other Tax is shown on TDS column.</label>
                                    <label id="basic-error" class="validation-valid-label" for="basic">If Employee
                                        has SSF then 1 % slab range i.e SST will be 0.</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
