@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('css')
<style>
      
</style>

@endsection

@section('content')

<form action="{{ route('payroll.draft', $payrollModel->id) }}" method="POST">
    @csrf

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
                {{-- <a href="{{ route('exportPayroll', request()->all()) }}" class="btn btn-success rounded-pill"><i class="icon-file-excel"></i> Export</a> --}}
                {{-- <button id="button" onclick="htmlTableToExcel('xlsx')">Export HTML Table to EXCEL</button> --}}
                <div class="ml-1">
                    <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="zui-wrapper">
        <div class="table-responsive1 zui-scroller">
        {{-- <div class="table-container"> --}}
            <table class="table table-responsive table-bordered table-striped " id="table2excel">
                <thead>
                    <tr class="text-light btn-slate">
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
                        {{-- <th rowspan="2">Total Days For Payment</th> --}}
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
                        @foreach ($deductions as $deduction)
                            <th>{{ $deduction }}</th>
                        @endforeach
                        <th>Fine & Penalty</th>
                        <th style="padding: 0px 60px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($payrollModel->payrollEmployees) > 0)
                        @foreach ($payrollModel->payrollEmployees as $key => $payrollEmployee)
                            {{-- @if ($payrollEmployee->employee_id == 47) --}}
                            @php
                                $totalDeduction = 0;
                                $totalIncome = 0;
                                $total_days = 0;
                                if ($payrollModel->calendar_type == 'nep') {
                                    $joinDate = optional($payrollEmployee->employee)->nepali_join_date;
                                } else {
                                    $joinDate = optional($payrollEmployee->employee)->join_date;
                                }
                                
                                $joinMonth = date('m', strtotime($joinDate));
                                $joinDay = date('d', strtotime($joinDate));
                                $joinMonth = (int) $joinMonth;
                                $joinDay = (int) $joinDay;
                                $joinYear = date('Y', strtotime($joinDate));
                            @endphp
                            <tr class="myLine">
                                <td width="5%">
                                    #{{ ++$key }}
                                    <input type="hidden" name="payrollEmployeeId" value="{{ $payrollEmployee->id }}"
                                        class="payrollEmployeeId">
                                </td>
                                <td>
                                    <div class="media">
                                        <div class="mr-3">
                                            <img src="{{ optional($payrollEmployee->employee)->getImage() }}"
                                                class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">
                                                {{ optional($payrollEmployee->employee)->getFullName() }}</div>
                                            <span
                                                class="text-muted">{{ optional($payrollEmployee->employee)->official_email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $joinDate }}</td>
                                <td>{{ optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue }}
                                </td>
                                <td>{{ optional(optional($payrollEmployee->employee)->getGender)->dropvalue }}</td>
                                @php
                                    $attendance = $payrollEmployee->calculateAttendance($payrollModel->calendar_type, $payrollModel->year, $payrollModel->month);
                                    $leave = $payrollEmployee->calculateLeave($payrollModel->calendar_type, $payrollModel->year, $payrollModel->month);
                                    $unpaid_full_leave = $leave['unpaid_full_leave'];
                                    $unpaid_half_leave = $leave['unpaid_half_leave'];
                                    $paid_full_leave = $leave['paid_full_leave'];
                                    $paid_half_leave = $leave['paid_half_leave'];
                                    $total_paid_leave = $paid_full_leave + $paid_half_leave / 2;
                                    $total_leave = $unpaid_full_leave + $unpaid_half_leave / 2;
                                    // dd($total_leave);
                                @endphp
                                <td>{{ $attendance['total_days'] }}</td>
                                <td>{{ $attendance['working_days'] }}</td>
                                <td>{{ $attendance['extra_working_days'] }}</td>
                                <td>{{ $total_paid_leave }}</td>
                                <td>{{ $total_leave }}</td>
                                {{-- {{dd($payrollEmployee->incomes)}} --}}
                                @foreach ($payrollEmployee->incomes as $income)
                                    @php
                                        $incomeModel = optional($income->incomeSetup);
                                        if ($incomeModel->daily_basis_status == 11) {
                                            if ($attendance['working_days'] == 0) {
                                                $incomeAmount = 0;
                                            } else {
                                                $incomeAmount = $income->value * $attendance['working_days'];
                                            }
                                        } else {
                                            $incomeAmount = $income->value;
                                        }
                                        $totalIncome = $totalIncome + $incomeAmount;
                                    @endphp
                                    @if ($incomeModel->method == '3')
                                        <td>
                                            <div style="width: 80px;">
                                                <input type="text" name="payroll_income[{{ $income->id }}]"
                                                    value="{{ $incomeAmount }}" class="form-control numeric income"
                                                    placeholder="0.00">
                                            </div>
                                        </td>
                                    @else
                                        <td>{{ $incomeAmount }}</td>
                                    @endif
                                @endforeach
                                @php
                                    $arrear_amount = $payrollEmployee->arrear_amount ?? 0;
                                @endphp
                                <td>{{ $arrear_amount }}</td>
                                <td>
                                    @php
                                        $overTimePay = $payrollEmployee->overtime_pay ?? 0;
                                    @endphp
                                    <div style="width: 80px;">
                                        <input type="text" name="overtime_pay[{{ $payrollEmployee->id }}]"
                                            value="{{ $overTimePay }}" class="overtimePay form-control numeric"
                                            placeholder="0.00">
                                    </div>
                                </td>
                                <td>
                                    {{-- {{dd($payrollModel->year,$joinYear,$payrollModel->month,$joinMonth)}} --}}
                                    @if ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth)
                                        {{-- {{dd(1)}} --}}
                                        @php $totalIncome = $payrollEmployee->total_income ? $payrollEmployee->total_income : ((($totalIncome + $arrear_amount + $overTimePay)/$attendance['total_days']) * ($attendance['total_days'] - $joinDay) ); @endphp
                                    @else
                                        @php $totalIncome = $payrollEmployee->total_income ? $payrollEmployee->total_income : ($totalIncome + $arrear_amount + $overTimePay); @endphp
                                    @endif
                                    <input type="number" name="total_income[{{ $payrollEmployee->id }}]"
                                        value="{{ round($totalIncome, 2) }}" class="form-control totalIncome">
                                </td>
                                @php
                                    $leaveAmount = ($totalIncome / $attendance['total_days']) * $total_leave;
                                    $extra_working_amount = round(($totalIncome / $attendance['total_days']) * $attendance['extra_working_days'], 2);
                                @endphp
                                <td>{{ round($leaveAmount, 2) }}</td>
                                @foreach ($payrollEmployee->deductions as $deduction)
                                    @php
                                        $deductionModel = optional($deduction->deductionSetup);
                                        $deductionAmount = $deduction->value ?? 0;
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
                                        <td>{{ $deductionAmount }}</td>
                                    @endif
                                @endforeach
                                <td>
                                    @php
                                        $fineAndPenalty = $payrollEmployee->fine_penalty ?? 0;
                                    @endphp
                                    <div style="width: 80px;">
                                        <input type="number" name="fine_penalty[{{ $payrollEmployee->id }}]"
                                            value="{{ $fineAndPenalty }}" class="fineAndPenalty form-control numeric"
                                            placeholder="0.00">
                                    </div>
                                </td>
                                <input type="hidden" name="total_days[{{ $payrollEmployee->id }}]"
                                    value="{{ $attendance['total_days'] }}" class="form-control">
                                <input type="hidden" name="extra_working_days[{{ $payrollEmployee->id }}]"
                                    value="{{ $attendance['extra_working_days'] }}" class="form-control">
                                <input type="hidden" name="unpaid_leave_days[{{ $payrollEmployee->id }}]"
                                    value="{{ $total_leave }}" class="form-control">
                                <input type="hidden" name="paid_leave_days[{{ $payrollEmployee->id }}]"
                                    value="{{ $total_paid_leave }}" class="form-control">
                                <input type="hidden" name="leave_amount[{{ $payrollEmployee->id }}]"
                                    value="{{ round($leaveAmount, 2) }}" class="form-control">
                                <td>

                                    @if ($payrollModel->year == $joinYear && $payrollModel->month == $joinMonth)
                                        @php $totalDeduction = $payrollEmployee->total_deduction ? $payrollEmployee->total_deduction : ((($totalDeduction + $leaveAmount + $fineAndPenalty)/$attendance['total_days']) * ($attendance['total_days'] - $joinDay) ); @endphp
                                    @else
                                        @php $totalDeduction = $payrollEmployee->total_deduction ? $payrollEmployee->total_deduction : ($totalDeduction + $leaveAmount + $fineAndPenalty); @endphp
                                    @endif

                                    <input type="number" name="total_deduction[{{ $payrollEmployee->id }}]"
                                        value="{{ round($totalDeduction, 2) }}" class="form-control totalDeduction">
                                    <!-- <input type="number" name="monthly_total_deduction[{{ $payrollEmployee->id }}]" value="{{ round($totalDeduction + $leaveAmount + $fineAndPenalty, 2) }}" class="form-control totalDeduction"> -->
                                </td>
                                <td>
                                    @php
                                        $totalSalary = $totalIncome - $totalDeduction;
                                    @endphp
                                    <div class="totalSalary">{{ round($totalSalary, 2) }}</div>
                                </td>
                                <td>
                                    <div style="width: 80px;">
                                        @php $festivalBonus = $payrollEmployee->festival_bonus ? $payrollEmployee->festival_bonus : 0; @endphp
                                        <input type="number" name="festival_bonus[{{ $payrollEmployee->id }}]"
                                            value="{{ $festivalBonus }}" class="form-control festivalBonus">
                                    </div>
                                </td>
                                <!-- <td>
                                        @php
                                            $totalSalaryWithBonus = $totalSalary + $festivalBonus;
                                        @endphp
                                        <div class="totalSalaryWithBonus">{{ number_format($totalSalaryWithBonus, 2) }}</div>
                                    </td> -->
                                <td>
                                    @php $taxableAmount = round($payrollEmployee->calculateTaxableSalary($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployee->id), 2); @endphp
                                    <div id="taxableAmount">{{ $taxableAmount }}</div>
                                    <input type="hidden" name="yearly_taxable_salary[{{ $payrollEmployee->id }}]"
                                        value="{{ $taxableAmount }}" class="taxableAmount">
                                </td>
                                <td>
                                    @php $sst = isset($payrollEmployee->sst) ? $payrollEmployee->sst : $payrollEmployee->calculateSST($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployee->id,$payrollModel->organization_id); @endphp
                                    <div style="width: 100px;">
                                        <input type="number" name="sst[{{ $payrollEmployee->id }}]"
                                            value="{{ $sst }}" class="form-control sst p-100">
                                    </div>
                                </td>
                                <td>
                                    @php $tds = isset($payrollEmployee->tds) ? $payrollEmployee->tds : $payrollEmployee->calculateTDS($totalIncome, $totalDeduction, $festivalBonus, $payrollEmployee->id); @endphp
                                    <div style="width: 100px;">
                                        <input type="number" name="tds[{{ $payrollEmployee->id }}]"
                                            value="{{ $tds }}" class="form-control tds">
                                    </div>
                                </td>
                                @php
                                    $total_tax = $sst + $tds;
                                    if (optional(optional($payrollEmployee->employee)->getMaritalStatus)->dropvalue == 'Single' && optional(optional($payrollEmployee->employee)->getGender)->dropvalue == 'Female') {
                                        $single_women_tax_credit = round(0.1 * $total_tax, 2);
                                    } else {
                                        $single_women_tax_credit = 0;
                                    }
                                @endphp
                                <td>
                                    <div id="totalTax">{{ round($total_tax, 2) }}</div>
                                </td>
                                <!-- <td>{{ $extra_working_amount }}</td>
                                    <input type="hidden" name="extra_working_days_amount[{{ $payrollEmployee->id }}]"  value="{{ round($leaveAmount, 2) }}" class="form-control"> -->
                                <td>
                                    @php $netSalary = $totalIncome - ($totalDeduction + $sst + $tds) + $extra_working_amount; @endphp
                                    <div id="netSalary">{{ round($netSalary, 2) }}</div>
                                    <input type="hidden" name="net_salary[{{ $payrollEmployee->id }}]"
                                        value="{{ $netSalary }}" class="form-control netSalary">
                                </td>
                                <td>{{ $single_women_tax_credit }}</td>
                                <input type="hidden" name="single_women_tax_credit[{{ $payrollEmployee->id }}]"
                                    value="{{ $single_women_tax_credit }}" class="form-control">
                                <td>
                                    @php $adjustment = isset($payrollEmployee->adjustment) ? $payrollEmployee->adjustment : 0; @endphp
                                    <div class="row" style="width: 200px;">
                                        <div class="col-md-6">
                                            <select class="form-control adjustmentMode">
                                                <option value="sub">SUB</option>
                                                <option value="add">ADD</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6" style="width: 80px;">
                                            <input type="text" name="adjustment[{{ $payrollEmployee->id }}]"
                                                value="{{ $adjustment }}" class="form-control numeric adjustment">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @php $advance = isset($payrollEmployee->advanceAmount) ? $payrollEmployee->advanceAmount : 0; @endphp
                                    {{ round($advance, 2) }}
                                    <input type="hidden" name="advance_amount[{{ $payrollEmployee->id }}]"
                                        value="{{ $advance }}" class="form-control advanceAmount" readonly>
                                </td>
                                <td>
                                    @php $payableSalary = $netSalary + $single_women_tax_credit - $adjustment - $advance; @endphp
                                    <div id="payableSalary">{{ round($payableSalary, 2) }}</div>
                                    <input type="hidden" name="payable_salary[{{ $payrollEmployee->id }}]"
                                        value="{{ $payableSalary }}" class="form-control payableSalary" readonly>
                                </td>
                                <td>
                                    <div style="width: 150px;">
                                        <input type="text" name="remark[{{ $payrollEmployee->id }}]"
                                            value="{{ $payrollEmployee->remarks }}" class="form-control"
                                            placeholder="Remark here..">
                                    </div>
                                </td>
                            </tr>
                            {{-- @endif     --}}
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        </div>
        {{-- </div> --}}
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
        <button type="submit" class="btn btn-success btn-labeled btn-labeled-left"><b><i
                    class="icon-database-insert"></i></b>Save Changes</button>
    </div>
</form>

@endsection

@section('script')
<!-- select2 js -->
<script src="{{ asset('admin/global/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('admin/global/js/demo_pages/form_select2.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                // var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                $(table).table2excel({
                    exclude: ".noExl",
                    name: "IRD Report",
                    filename: "ird_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
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
{{-- <script>
function htmlTableToExcel(type){
    // alert('hhh');
    var data = document.getElementById('tblToExcl');
    var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
    XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
    XLSX.writeFile(excelFile, 'Payroll' + '.' + type);
   }
</script> --}}
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

        $('.fineAndPenalty').on('keydown', function(event) {
            if (event.key == 'Tab') {
                calculationTotalDeduction($(this));
            }
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

        function calculationTotalIncome(context, amount = 0) {
            var totalIncome = parseInt(context.closest('.myLine').find('.totalIncome').val());
            var overtimePay = parseInt(context.closest('.myLine').find('.overtimePay').val());
            if (overtimePay) {
                totalIncome += overtimePay;
            }
            totalIncome += parseInt(amount);
            context.closest('.myLine').find('.totalIncome').val(totalIncome);
            reCalculate(context);
        }

        function calculationTotalDeduction(context, amount = 0) {
            var totalDeduction = parseInt(context.closest('.myLine').find('.totalDeduction').val());
            var fineAndPenalty = parseInt(context.closest('.myLine').find('.fineAndPenalty').val());
            if (fineAndPenalty) {
                totalDeduction += fineAndPenalty;
            }
            totalDeduction += parseInt(amount);
            context.closest('.myLine').find('.totalDeduction').val(totalDeduction);
            reCalculate(context);
        }

        function reCalculate(context) {
            var payrollEmployeeId = parseInt(context.closest('.myLine').find('.payrollEmployeeId').val());
            var totalIncome = parseInt(context.closest('.myLine').find('.totalIncome').val());
            var totalDeduction = parseInt(context.closest('.myLine').find('.totalDeduction').val());
            var totalSalary = totalIncome - totalDeduction;
            // var formatedTotalSalary = totalSalary.toLocaleString('hi-IN');
            context.closest('.myLine').find('.totalSalary').html(totalSalary);
            var festivalBonus = parseInt(context.closest('.myLine').find('.festivalBonus').val());

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
                    var netSalary = totalIncome - (totalDeduction + data.sst + data.tds);
                    netSalary = netSalary.toFixed(2);
                    context.closest('.myLine').find('.netSalary').val(netSalary);
                    context.closest('.myLine').find('#netSalary').html(netSalary);

                    calculation(context);

                    $('#cover-spin').hide();
                }
            });
        }

        function calculation(context) {
            var totalIncome = parseInt(context.closest('.myLine').find('.totalIncome').val());
            var totalDeduction = parseInt(context.closest('.myLine').find('.totalDeduction').val());
            var sst = parseInt(context.closest('.myLine').find('.sst').val());
            var tds = parseInt(context.closest('.myLine').find('.tds').val());
            var totalTax = sst + tds;
            totalTax = totalTax.toFixed(2);
            context.closest('.myLine').find('#totalTax').html(totalTax);
            var netSalary = totalIncome - (totalDeduction + sst + tds);
            netSalary = netSalary.toFixed(2);
            context.closest('.myLine').find('.netSalary').val(netSalary);
            context.closest('.myLine').find('#netSalary').html(netSalary);
            var netSalary = parseInt(context.closest('.myLine').find('.netSalary').val());
            var adjustmentMode = context.closest('.myLine').find('.adjustmentMode').val();
            var adjustment = parseInt(context.closest('.myLine').find('.adjustment').val());
            var advanceAmount = parseInt(context.closest('.myLine').find('.advanceAmount').val());
            var payableSalary = netSalary - advanceAmount;
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
