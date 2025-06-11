@extends('admin::layout')
@section('title') Log Report @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">Log Report</a>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>
@include('payroll::payroll.log-report.partial.advance_filter')


@if (request()->get('organization_id'))
    {{-- <div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <div class="media-body text-center text-md-left">
        </div>
        <div class="ml-1">
            <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
        </div>
    </div>
</div> --}}
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <div class="media-body text-center">
                <h6 class="media-title font-weight-semibold">{{ $organizationModel->name }}</h6>
                <b></b> {{ $organizationModel->address }}
            </div>
        </div>
        <hr>
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <div class="media-body text-center">
                <h6 class="media-title font-weight-semibold">Employee Wise TDS Calculation Detail for the Fiscal Year
                    {{ request()->get('year') ?? request()->get('eng_year') }}</h6>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-bold">Employees Name :</span>
                        <div class="ml-5">{{ $employeeModel->getFullName() }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">Office :</span>
                        <div class="ml-5">{{ optional($employeeModel->organizationModel)->address }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">Last TDS Calculation Month :</span>
                        @php
                            $lastPayrollName = request()->get('year')
                                ? date_converter()->_get_nepali_month(optional($lastMonthPayroll->payroll)->month) .
                                    ',' .
                                    optional($lastMonthPayroll->payroll)->year
                                : date_converter()->_get_english_month(optional($lastMonthPayroll->payroll)->month) .
                                    ',' .
                                    optional($lastMonthPayroll->payroll)->year;
                        @endphp
                        <div class="ml-5">{{ $lastPayrollName }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">PAN No. :</span>
                        <div class="ml-5">{{ $employeeModel->pan_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">PF No. :</span>
                        <div class="ml-5">{{ $employeeModel->pf_no }}</div>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-bold">Appointment Type :</span>
                        <div class="ml-5"></div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">Employment Type :</span>
                        <div class="ml-5"></div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">SSF No. :</span>
                        <div class="ml-5">{{ $employeeModel->ssf_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">CIT No. :</span>
                        <div class="ml-5">{{ $employeeModel->cit_no }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">Bank Name :</span>
                        <div class="ml-5">{{ optional($employeeModel->bankDetail)->bank_name }}</div>
                    </li>
                </ul>
            </div>
            <div class="col-md-4">
                <ul class="media-list">
                    <li class="media mt-2">
                        <span class="font-weight-bold">Designation :</span>
                        <div class="ml-5">{{ optional($employeeModel->designation)->dropvalue }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">Sub-Function :</span>
                        <div class="ml-5">{{ optional($employeeModel->department)->dropvalue }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">Business Unit :</span>
                        <div class="ml-5">{{ optional($employeeModel->organizationModel)->name }}</div>
                    </li>
                    <li class="media mt-2">
                        <span class="font-weight-bold">Account Number :</span>
                        <div class="ml-5">{{ optional($employeeModel->bankDetail)->account_number }}</div>
                    </li>
                </ul>
            </div>
        </div>
        <table id="table2excel" class="table table-bordered mt-5">
            <thead>
                <tr class="text-white">
                    <th rowspan="2">S.N</th>
                    <th rowspan="2">Salary Title</th>
                    @foreach ($months as $key => $value)
                        <th rowspan="2"> {{ $value }}</th>
                    @endforeach
                    <th rowspan="2">Total</th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td colspan="14">Description:Income</td>
                </tr>
                @php
                    $grand_income = 0;
                @endphp
                @foreach ($incomeSetups as $key => $value)
                    @if ($value->show_status)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->title }}</td>
                            @php
                                $total_income = 0;
                            @endphp
                            @foreach ($value->income as $in => $inc)
                                @php
                                    $total_income += $inc;
                                    $grand_income += $inc;
                                @endphp
                                <td>{{ $inc == 0 ? '-' : $inc }}</td>
                            @endforeach
                            <td>{{ $total_income }}</td>

                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th colspan="14">Total:</th>
                    <td> {{ $grand_income }}</td>
                </tr>
                <tr>
                    <td colspan="14">Description:Deduction</td>
                </tr>
                @php
                    $grand_deduction = 0;
                @endphp
                @foreach ($deductionSetups as $key => $value)
                    @if ($value->show_status)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $value->title }}</td>
                            @php
                                $total_deduction = 0;
                            @endphp
                            @foreach ($value->deduction as $de => $ded)
                                @php
                                    $total_deduction += $ded;
                                    $grand_deduction += $ded;
                                @endphp
                                <td>{{ $ded == 0 ? '-' : $ded }}</td>
                            @endforeach
                            <td>{{ $total_deduction }}</td>

                        </tr>
                    @endif
                @endforeach
                <tr>
                    <th colspan="14">Total:</th>
                    <td> {{ $grand_deduction }}</td>
                </tr>
                <tr>
                    <td colspan="14">Description:TDS Paid</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>TDS Paid 1 Percent</td>
                    @php
                        $total_sst = 0;
                    @endphp
                    @foreach ($tax['sst'] as $sst)
                        @php
                            $total_sst += $sst;
                        @endphp
                        <td>{{ $sst }}</td>
                    @endforeach
                    <td>{{ $total_sst }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>TDS Paid 1 Other</td>
                    @php
                        $total_tds = 0;
                    @endphp
                    @foreach ($tax['tds'] as $tds)
                        @php
                            $total_tds += $tds;
                        @endphp
                        <td>{{ $tds }}</td>
                    @endforeach
                    <td>{{ $total_tds }}</td>
                </tr>
                <tr>
                    <th colspan="14">Total:</th>
                    <th> {{ $total_sst + $total_tds }}</th>
                </tr>



                {{-- @php
            $totalIncome = 0;
            $totalDeduction = 0;
            $totalSst = 0;
            $totalTds = 0;
            $totalNetSalary = 0;
            $totalAdjustment = 0;
            $totalAdvance = 0;
            $totalPayableSalary = 0;
            @endphp --}}
                {{-- @if (count($payrollEmployeeModels) > 0)
                @foreach ($payrollEmployeeModels as $key => $payrollEmployeeModel)
                @php
                    $totalIncome = $totalIncome + $payrollEmployeeModel->total_income;
                    $totalDeduction = $totalDeduction + $payrollEmployeeModel->total_deduction;
                    $totalSst = $totalSst + $payrollEmployeeModel->sst;
                    $totalTds = $totalTds + $payrollEmployeeModel->tds;
                    $totalNetSalary = $totalNetSalary + $payrollEmployeeModel->net_salary;
                    $totalAdjustment = $totalAdjustment + $payrollEmployeeModel->adjustment;
                    $totalAdvance = $totalAdvance + $payrollEmployeeModel->advance_amount;
                    $totalPayableSalary = $totalPayableSalary + $payrollEmployeeModel->payable_salary;
                @endphp
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{optional($payrollEmployeeModel->employee)->getFullName()}}</td>
                        <td>{{optional($payrollEmployeeModel->employee)->nepali_join_date}}</td>
                        <td>{{ optional(optional($payrollEmployeeModel->employee)->getMaritalStatus)->dropvalue }}</td>
                        <td>{{date_converter()->_get_nepali_month(optional($payrollEmployeeModel->payroll)->month)}}</td>
                        <td>{{optional($payrollEmployeeModel->payroll)->year}}</td>
                        <td>{{$payrollEmployeeModel->total_income}}</td>
                        <td>{{$payrollEmployeeModel->total_deduction}}</td>
                        <td>{{$payrollEmployeeModel->festival_bonus}}</td>
                        <td>{{$payrollEmployeeModel->sst}}</td>
                        <td>{{$payrollEmployeeModel->tds}}</td>
                        <td>{{$payrollEmployeeModel->net_salary}}</td>
                        <td>{{$payrollEmployeeModel->adjustment}}</td>
                        <td>{{$payrollEmployeeModel->advance_amount}}</td>
                        <td>{{$payrollEmployeeModel->payable_salary}}</td>
                    <tr>
                @endforeach
            @else
                <tr>
                    <td class="5">No record found.</td>
                </tr>
            @endif --}}

            </tbody>
            {{-- <footer>
            <tr>
                <td colspan="6" class="text-center">Total</td>
                <td>{{$totalIncome}}</td>
                <td>{{$totalDeduction}}</td>
                <td></td>
                <td>{{$totalSst}}</td>
                <td>{{$totalTds}}</td>
                <td>{{$totalNetSalary}}</td>
                <td>{{$totalAdjustment}}</td>
                <td>{{$totalAdvance}}</td>
                <td>{{$totalPayableSalary}}</td>
            </tr>
        </footer> --}}
        </table>
        <div class="row mt-5">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-white text-center">
                            <th rowspan="2" colspan ="2">Total Income And Deductable Amount</th>
                        </tr>

                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>Total Income</td>
                            <td>{{ $grand_income }}</td>
                        </tr>
                        <tr class="text-center">
                            <td>Taxable Amount</td>
                            <td>{{ $lastMonthPayroll->yearly_taxable_salary }}</td>
                        </tr>
                        <tr class="text-center">
                            <td>Marital status</td>
                            <td>{{ optional($employeeModel->getMaritalStatus)->dropvalue }}</td>
                        </tr>
                        <tr class="text-center">
                            <td>Gender</td>
                            <td>{{ optional($employeeModel->getGender)->dropvalue }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <thead>
                        <tr class="text-white text-center">
                            <th rowspan="2" colspan ="3">Tax Calculation</th>
                        </tr>

                    </thead>
                    <tbody>
                        @php
                            $total_tax = 0;
                        @endphp
                        @foreach ($taxDetail as $tax)
                            @php
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
                            <td class="text-bold">Total Tax</td>
                            <td></td>
                            <td>{{ $total_tax }}</td>
                        </tr>
                        <tr class="text-right">
                            <td class="text-bold">Total Paid TDS</td>
                            <td></td>
                            <td>{{ $total_sst + $total_tds }}</td>
                        </tr>
                        <tr class="text-right">
                            <td class="text-bold">Tds For Current Calculating Month</td>
                            <td></td>
                            <td>{{ $lastMonthPayroll->sst + $lastMonthPayroll->tds }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <table id="table2excel" class="table table-responsive table-striped table-bordered">
        <thead>
            <tr class="text-white">
                <th rowspan="2">S.N</th>
                <th rowspan="2">Employee Name</th>
                <th rowspan="2">Join Date</th>
                <th rowspan="2">Marital Status</th>
                <th rowspan="2">Month</th>
                <th rowspan="2">Year</th>
                <th rowspan="2">Total Income</th>
                <th rowspan="2">Total Deduction</th>
                <th rowspan="2">Festival Bonus</th>
                <th rowspan="2">SST</th>
                <th rowspan="2">TDS</th>
                <th rowspan="2">Net Salary</th>
                <th rowspan="2">Adjustment</th>
                <th rowspan="2">Advance</th>
                <th rowspan="2">Payable Salary</th>
            </tr>

        </thead>
        <tbody>
            @php
            $totalIncome = 0;
            $totalDeduction = 0;
            $totalSst = 0;
            $totalTds = 0;
            $totalNetSalary = 0;
            $totalAdjustment = 0;
            $totalAdvance = 0;
            $totalPayableSalary = 0;
            @endphp
            @if (count($payrollEmployeeModels) > 0)
                @foreach ($payrollEmployeeModels as $key => $payrollEmployeeModel)
                @php
                    $totalIncome = $totalIncome + $payrollEmployeeModel->total_income;
                    $totalDeduction = $totalDeduction + $payrollEmployeeModel->total_deduction;
                    $totalSst = $totalSst + $payrollEmployeeModel->sst;
                    $totalTds = $totalTds + $payrollEmployeeModel->tds;
                    $totalNetSalary = $totalNetSalary + $payrollEmployeeModel->net_salary;
                    $totalAdjustment = $totalAdjustment + $payrollEmployeeModel->adjustment;
                    $totalAdvance = $totalAdvance + $payrollEmployeeModel->advance_amount;
                    $totalPayableSalary = $totalPayableSalary + $payrollEmployeeModel->payable_salary;
                @endphp
                    <tr>
                        <td>{{++$key}}</td>
                        <td>{{optional($payrollEmployeeModel->employee)->getFullName()}}</td>
                        <td>{{optional($payrollEmployeeModel->employee)->nepali_join_date}}</td>
                        <td>{{ optional(optional($payrollEmployeeModel->employee)->getMaritalStatus)->dropvalue }}</td>
                        <td>{{date_converter()->_get_nepali_month(optional($payrollEmployeeModel->payroll)->month)}}</td>
                        <td>{{optional($payrollEmployeeModel->payroll)->year}}</td>
                        <td>{{$payrollEmployeeModel->total_income}}</td>
                        <td>{{$payrollEmployeeModel->total_deduction}}</td>
                        <td>{{$payrollEmployeeModel->festival_bonus}}</td>
                        <td>{{$payrollEmployeeModel->sst}}</td>
                        <td>{{$payrollEmployeeModel->tds}}</td>
                        <td>{{$payrollEmployeeModel->net_salary}}</td>
                        <td>{{$payrollEmployeeModel->adjustment}}</td>
                        <td>{{$payrollEmployeeModel->advance_amount}}</td>
                        <td>{{$payrollEmployeeModel->payable_salary}}</td>
                    <tr>
                @endforeach
            @else
                <tr>
                    <td class="5">No record found.</td>
                </tr>
            @endif

        </tbody>
        <footer>
            <tr>
                <td colspan="6" class="text-center">Total</td>
                <td>{{$totalIncome}}</td>
                <td>{{$totalDeduction}}</td>
                <td></td>
                <td>{{$totalSst}}</td>
                <td>{{$totalTds}}</td>
                <td>{{$totalNetSalary}}</td>
                <td>{{$totalAdjustment}}</td>
                <td>{{$totalAdvance}}</td>
                <td>{{$totalPayableSalary}}</td>
            </tr>
        </footer>
    </table> --}}
        <div class="row">
            <div class="col-12">
                <ul class="pagination pagination-rounded justify-content-end mb-3">
                    {{-- {{ $payrollEmployeeModels->appends(request()->all())->links() }} --}}
                </ul>
            </div>
        </div>

    </div>
@endif
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
                    name: "Log Report",
                    filename: "log_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
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
