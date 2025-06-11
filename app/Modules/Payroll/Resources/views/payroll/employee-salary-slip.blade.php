<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salary Slip</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/colors.min.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <link href="{{ asset('admin/global/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <link href="{{ asset('admin/css/additional.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('admin/css/colors.css') }}">

    <!-- Core JS files -->
    <script src="{{ asset('admin/global/js/main/jquery.min.js') }}"></script>
    <script src="/bootstrap.min.js"></script>
    <script src="{{ asset('admin/global/js/main/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

    <!-- Theme JS files -->
    <script src="{{ asset('admin/assets/js/plugins/prism.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/plugins/sticky.min.js') }}"></script>

    <script src="{{ asset('admin/assets/js/main/app.js') }}"></script>
    <script src="{{ asset('admin/assets/js/pages/components_scrollspy.js') }}"></script>

</head>

<body>
    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0 50px 0 50px;
            /* this affects the margin in the printer settings */
        }
    </style>
    {{-- @php  $currency = !empty($company->currency) ? optional($company->getCurrency)->dropvalue : 'Rs.'; @endphp --}}
    <div class="salary-sheet">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="salary-sheet-header">
                        <div class="company-name">
                            {{ optional(optional($payrollEmployee->employee)->organizationModel)->name }}<br>
                            {{-- <span>{{ !empty($payroll->getEmployee->organization->address) ? $payroll->getEmployee->organization->address : 'Kamaladi, Ganeshthan, 6th floor, Amir Bhawan, Kathmandu' }}</span> --}}
                        </div>
                        <div class="company-logo">
                            @if (!empty($setting->company_logo) && file_exists(public_path('uploads/setting/' . $setting->company_logo)))
                                <img src="{{ asset('uploads/setting/' . $setting->company_logo) }}" />
                            @else
                                <img src="{{ asset('admin/assets/images/salary-image/logo.png') }}" alt="" />
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if (optional($payrollEmployee->payroll)->calendar_type == 'nep')
                @php $month = date_converter()->_get_nepali_month(optional($payrollEmployee->payroll)->month) @endphp
            @else
                @php $month = date_converter()->_get_english_month(optional($payrollEmployee->payroll)->month) @endphp
            @endif
            <div class="row">
                <div class="col-12">
                    <div class="salary-sheet-body">
                        <div class="salary-paid-block">
                            <h4>Salary Paid Slip</h4>
                            <div class="salary-paid-row">
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Employee Name</div>
                                    <div class="block-right-side">
                                        {{ optional($payrollEmployee->employee)->getFullName() }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Designation</div>
                                    <div class="block-right-side">
                                        {{ optional(optional($payrollEmployee->employee)->designation)->dropvalue }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Date of Join</div>
                                    <div class="block-right-side">
                                        {{ optional($payrollEmployee->payroll)->calendar_type == 'nep' ? optional($payrollEmployee->employee)->nepali_join_date : optional($payrollEmployee->employee)->join_date }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Branch</div>
                                    <div class="block-right-side">
                                        {{ optional(optional($payrollEmployee->employee)->branchModel)->name }}</div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Month</div>
                                    <div class="block-right-side">{{ $month . '-' . $payrollEmployee->payroll->year }}
                                    </div>
                                </div>
                                {{-- <div class="salary-paid-column">
                                    <div class="block-left-side">Month Nepali</div>
                                    <div class="block-right-side">{{$month_name.', '.$nep_year}}</div>
                                </div> --}}

                                {{-- <div class="salary-paid-column">
                                    <div class="block-left-side">Contract Deadline</div>
                                    <div class="block-right-side">{{ $contractInfo!== null ? $contractInfo->contract_end_date : '' }}</div>
                                </div> --}}


                            </div>
                        </div>
                        <div class="salary-details-block">
                            <h5>Salary Details</h5>
                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Standard Salary For The Month</div>
                                @foreach ($payrollEmployee->incomes as $income)
                                    @if ($income->value != 0)
                                        <div class="salary-details-column">
                                            <div class="block-left-side">{{ optional($income->incomeSetup)->title }}
                                            </div>
                                            <div class="block-right-side">Rs. {{ round($income->value, 2) }}</div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="salary-details-column">
                                    <div class="block-left-side">Gross Income(+)</div>
                                    <div class="block-right-side">Rs. {{ priceFormat($payrollEmployee->total_income) }}
                                    </div>
                                </div>
                            </div>
                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Deduction from Gross Income</div>
                                @if ($payrollEmployee->leave_amount != 0)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">Leave Amount:</div>
                                        <div class="block-right-side">Rs.
                                            {{ priceFormat($payrollEmployee->leave_amount) }}</div>
                                    </div>
                                @endif
                                @foreach ($payrollEmployee->deductions as $deduction)
                                    @if ($deduction->value != 0)
                                        <div class="salary-details-column">
                                            <div class="block-left-side">{{ $deduction->title }}</div>
                                            <div class="block-right-side">Rs. {{ round($deduction->value, 2) }}</div>
                                        </div>
                                    @endif
                                @endforeach
                                @if ($payrollEmployee->fine_penalty != 0)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">Fine and Penalty:</div>
                                        <div class="block-right-side">Rs.
                                            {{ priceFormat($payrollEmployee->fine_penalty) }}</div>
                                    </div>
                                @endif
                                <div class="salary-details-column">
                                    <div class="block-left-side">Total Monthly Deduction(-)</div>
                                    <div class="block-right-side total">
                                        Rs.
                                        {{ priceFormat($payrollEmployee->total_deduction) }}
                                    </div>
                                </div>

                                {{-- @if ($tax_hide_show == 1) --}}
                                <div class="salary-details-subTitle">Total Taxable Amount</div>
                                @foreach ($payrollEmployee->taxExcludeValues as $taxExcludeValue)
                                    @if ($taxExcludeValue->value != 0)
                                        <div class="salary-details-column">
                                            <div class="block-left-side">{{ @$taxExcludeValue->taxExcludeSetup->title }}:
                                            </div>
                                            <div class="block-right-side">Rs.
                                                {{ priceFormat($taxExcludeValue->value ?? 0) }}</div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="salary-details-column">
                                    <div class="block-left-side">Yearly Taxable Amount</div>
                                    <div class="block-right-side total">
                                        Rs.{{ priceFormat($payrollEmployee->yearly_taxable_salary) }}
                                    </div>


                                </div>

                                <div class="salary-details-column">
                                    <div class="block-left-side">Total Tax:</div>
                                    <div class="block-right-side">Rs.
                                        {{ priceFormat($payrollEmployee->sst + $payrollEmployee->tds - $payrollEmployee->single_women_tax_credit) }}
                                    </div>
                                </div>

                                {{-- @php
                                        $rt = 0;

                                        if ($payroll->getEmployee->gender == 0 && $payroll->getEmployee->martial_status == 0) {
                                            $rt = ($payroll->second_sst + $payroll->third_sst + $payroll->fourth_sst + $payroll->fifth_sst) * 0.1;
                                        }
                                    @endphp
                                    @if ($payroll->getEmployee->gender == 0 && $payroll->getEmployee->martial_status == 0)
                                        <div class="salary-details-column">
                                            <div class="block-left-side">Single WomenTax Credit(+):</div>
                                            <div class="block-right-side">{{ $currency }} {{ priceFormat($rt) }}
                                            </div>
                                        </div>
                                    @endif --}}
                                {{-- @endif --}}

                                <div class="salary-details-subTitle">After Taxable Amount</div>
                                <div class="salary-details-column">
                                    <div class="block-left-side">Net Salary To Be Paid:</div>
                                    <div class="block-right-side total">Rs.
                                        {{ priceFormat($payrollEmployee->net_salary) }}
                                    </div>

                                </div>
                                {{-- <div class="salary-details-column">
                                    <div class="block-left-side">Single Women Tax Credit:</div>
                                    <div class="block-right-side total">Rs.
                                        {{ priceFormat($payrollEmployee->single_women_tax_credit) }}
                                    </div>
                                </div> --}}
                                @if ($payrollEmployee->adjustment != 0)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">Adjustment:</div>
                                        <div class="block-right-side total">Rs.
                                            {{ priceFormat($payrollEmployee->adjustment) }}
                                        </div>
                                    </div>
                                @endif
                                @if ($payrollEmployee->advance_amount != 0)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">Advance:</div>
                                        <div class="block-right-side total">Rs.
                                            {{ priceFormat($payrollEmployee->advance_amount) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="salary-details-column">
                                    <div class="block-left-side">Payable Salary:</div>
                                    <div class="block-right-side total">Rs.
                                        {{ priceFormat($payrollEmployee->payable_salary) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="salary-footer-column">
                                    <div class="footer-title">Received By</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="salary-footer-column">
                                    <div class="footer-title">Accountant</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="salary-footer-column">
                                    <div class="footer-title">Approved By</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="text-center">
                <button type="button" class="btn btn-primary" id="print-salary-slip">
                    <i class="icon-printer"></i>&nbsp&nbsp&nbspPrint
                </button>
            </div>
        </div>
    </div>
</body>

<script>
    $(document).ready(function() {
        $('#print-salary-slip').click(function() {
            $(this).hide();
            window.print();
            $(this).show();
        });
    });
</script>

</html>
