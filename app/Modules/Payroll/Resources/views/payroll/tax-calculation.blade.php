@extends('admin::layout')
@section('title') Tax Calculation @endSection
@section('breadcrum')
<a href="{{route('payroll.index')}}" class="breadcrumb-item">Payroll </a>
<a class="breadcrumb-item active">Tax Calculation</a>
@stop

@section('content')
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
                                    <td width="30%">Employee Name : &nbsp;&nbsp; {{optional($payrollEmployee->employee)->getFullName()}}</td>
                                    <td>Payroll Year : &nbsp;&nbsp; {{$payrollModel->year}} </td>
                                    <td>Payroll Month :  &nbsp;&nbsp; {{$payrollModel->calendar_type == 'eng' ? date_converter()->_get_english_month($payrollModel->month) : date_converter()->_get_nepali_month($payrollModel->month) }}</td>
                                </tr>
                                <tr>
                                    <td>Salary Paid Month : &nbsp;&nbsp; {{$salaryPaidMonth}} </td>
                                    <td>Taxable Month : &nbsp;&nbsp; {{$taxableMonth}}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>


                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Income Detail</legend>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="30%">Total Income(Till this month) : &nbsp;&nbsp; {{$previousTotalIncome}}</td>
                                    <td>Current Month Income : &nbsp;&nbsp; {{$payrollEmployee->total_income}} </td>
                                    <td>Gross Salary :  &nbsp;&nbsp; {{ optional(optional($payrollEmployee->employee)->employeeGrossSalarySetup)->gross_salary ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Annual Income : &nbsp;&nbsp; {{$payrollEmployee->annual_income}} </td>
                                    <td>Festival Bonus : &nbsp;&nbsp; {{$payrollEmployee->festival_bonus}}</td>
                                </tr>
                                <tr>
                                    <td colspan = "3">Total Income For the Year : &nbsp;&nbsp; Total previous Income + Current Month Income + (Gross Salary * (Taxable Month - 1 - Salary Paid Month))
                                        + Previous Total Bonus + Annual Income + (In case of not Terminate (Monthly Income * Remaining Month)) - Total Deduction - Annual Deduction + Festival Bonus + Total Bonus = {{round($taxableAmount, 2)}} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>






                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Deduction Detail</legend>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="30%">Total Deduction(Till this month) : &nbsp;&nbsp; {{$previousTotalDeduction}}</td>
                                    <td>Current Month Deduction : &nbsp;&nbsp; {{$payrollEmployee->total_deduction}} </td>
                                    <td>Remaining Month Deduction :  &nbsp;&nbsp; {{$monthlyDeduction}}</td>
                                </tr>
                                <tr>
                                    <td>Annual Deduction : &nbsp;&nbsp; {{$payrollEmployee->annual_deduction}} </td>
                                </tr>
                                <tr>
                                    <td colspan = "3">Total Deduction For the Year : &nbsp;&nbsp; Total Deduction(Till this month) + Leave Amount +  fine And Penalty= {{round($totalDeduction, 2)}} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>



                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Calculation Of Tax</legend>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-white text-center">
                                    <th rowspan="2" colspan ="3">Tax Calculation</th>
                                </tr>

                            </thead>
                            <tbody>
                                @php
                                    $sst = 0;
                                    $tds = 0;
                                    $total_tax = 0;
                                @endphp
                                @foreach($taxDetail as $key =>$tax)
                                @php
                                    if($key == 1){
                                        $sst += $tax['tds'];
                                    }
                                    else{
                                        $tds += $tax['tds'];
                                    }
                                    $total_tax += $tax['tds'];
                                @endphp
                                    <tr class="text-right">
                                        <td>{{$tax['amount']}}</td>
                                        <td>{{$tax['rate']}}</td>
                                        <td>{{$tax['tds']}}</td>
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
                                    <td>{{round($sst,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total SST For This Month</td>
                                    <td>Total Yearly SST / Taxable Month </td>
                                    <td>{{round($sst / $taxableMonth,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total Yearly TDS</td>
                                    <td></td>
                                    <td>{{round($tds,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total TDS For This Month</td>
                                    <td>Total Yearly TDS / Taxable Month </td>
                                    <td>{{round($tds / $taxableMonth,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total Tax</td>
                                    <td>SST + TDS</td>
                                    <td>{{round((($sst + $tds) / $taxableMonth ),2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Notes</legend>
                                <label id="basic-error" class="validation-valid-label" for="basic">1 % slab range Tax is shown on SST column and other Tax is shown on TDS column.</label>
                                <label id="basic-error" class="validation-valid-label" for="basic">If Employee has SSF then 1 % slab range i.e SST will be 0.</label>
                            </div>
                        </div>
                    </div>
                </div>













{{--
                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Income Detail</legend>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="30%">Total Income(Till this month) : &nbsp;&nbsp; {{$previousTotalIncome}}</td>
                                    <td>Current Month Income : &nbsp;&nbsp; {{$payrollEmployee->total_income}} </td>
                                    <td>Gross Salary :  &nbsp;&nbsp; {{ optional(optional($payrollEmployee->employee)->employeeGrossSalarySetup)->gross_salary ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td>Annual Income : &nbsp;&nbsp; {{$payrollEmployee->annual_income}} </td>
                                    <td>Festival Bonus : &nbsp;&nbsp; {{$payrollEmployee->festival_bonus}}</td>
                                </tr>
                                <tr>
                                    <td colspan = "3">Total Income For the Year : &nbsp;&nbsp; Total Income(Till this month) + Current Month Income + (Gross Salary * (Taxable Month - 1 - Salary Paid Month)) = {{$totalIncome}} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Deduction Detail</legend>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="30%">Total Deduction(Till this month) : &nbsp;&nbsp; {{$previousTotalDeduction}}</td>
                                    <td>Current Month Deduction : &nbsp;&nbsp; {{$payrollEmployee->total_deduction}} </td>
                                    <td>Remaining Month Deduction :  &nbsp;&nbsp; {{$monthlyDeduction}}</td>
                                </tr>
                                <tr>
                                    <td>Annual Deduction : &nbsp;&nbsp; {{$payrollEmployee->annual_deduction}} </td>
                                </tr>
                                <tr>
                                    <td colspan = "3">Total Deduction For the Year : &nbsp;&nbsp; Total Deduction(Till this month) + Current Month Deduction + (Remaining Month Deduction * (Taxable Month - 1 - Salary Paid Month)) OR (1/3 Of Total Income) OR Limit Of SSF PF And CIT whichever is lower = {{$totalDeduction}} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Calculation Of Taxable Salary</legend>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan = "3">Total Taxable Salary : &nbsp;&nbsp; Total Income For the Year + Annual Income + Festival Bonus - (Total Deduction For the Year + Annual Deduction) = {{round($taxableAmount,2)}} </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <legend class="text-uppercase font-size-sm font-weight-bold mt-3">Calculation Of Tax</legend>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-white text-center">
                                    <th rowspan="2" colspan ="3">Tax Calculation</th>
                                </tr>

                            </thead>
                            <tbody>
                                @php
                                    $sst = 0;
                                    $tds = 0;
                                    $total_tax = 0;
                                @endphp
                                @foreach($taxDetail as $key =>$tax)
                                @php
                                    if($key == 1){
                                        $sst += $tax['tds'];
                                    }
                                    else{
                                        $tds += $tax['tds'];
                                    }
                                    $total_tax += $tax['tds'];
                                @endphp
                                    <tr class="text-right">
                                        <td>{{$tax['amount']}}</td>
                                        <td>{{$tax['rate']}}</td>
                                        <td>{{$tax['tds']}}</td>
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
                                    <td>{{round($sst,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total SST For This Month</td>
                                    <td>Total Yearly SST / Taxable Month </td>
                                    <td>{{round($sst / $taxableMonth,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total Yearly TDS</td>
                                    <td></td>
                                    <td>{{round($tds,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total TDS For This Month</td>
                                    <td>Total Yearly TDS / Taxable Month </td>
                                    <td>{{round($tds / $taxableMonth,2)}}</td>
                                </tr>
                                <tr class="text-right">
                                    <td class="text-bold">Total Tax</td>
                                    <td>SST + TDS</td>
                                    <td>{{round((($sst + $tds) / $taxableMonth ),2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <legend class="text-uppercase font-size-sm font-weight-bold border-bottom">Notes</legend>
                                <label id="basic-error" class="validation-valid-label" for="basic">1 % slab range Tax is shown on SST column and other Tax is shown on TDS column.</label>
                                <label id="basic-error" class="validation-valid-label" for="basic">If Employee has SSF then 1 % slab range i.e SST will be 0.</label>
                            </div>
                        </div>
                    </div>
                </div> --}}


            </div>
        </div>
    </div>

</div>
@endsection
