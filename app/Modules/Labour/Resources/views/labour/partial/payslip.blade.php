<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salary Slip</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
    type="text/css">
    <link href="{{asset('admin/assets/css/icons/icomoon/styles.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">

    <link href="{{asset('admin/assets/css/style.css')}}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <link href="{{ asset('admin/global/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('admin/assets/css/style.css')}}" rel="stylesheet" type="text/css">
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
    <script src="{{asset('admin/assets/js/plugins/prism.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/plugins/sticky.min.js')}}"></script>

    <script src="{{asset('admin/assets/js/main/app.js')}}"></script>
    <script src="{{asset('admin/assets/js/pages/components_scrollspy.js')}}"></script>

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
                            {{ optional($payrollEmployee->organizationModel)->name }}<br>
                        </div>
                        <div class="company-logo">
                            @if(!empty($setting->company_logo) && file_exists(public_path('uploads/setting/'.$setting->company_logo)))
                                <img src="{{asset('uploads/setting/'.$setting->company_logo)}}" />
                            @else
                                <img src="{{asset('admin/assets/images/salary-image/logo.png')}}" alt=""/>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="salary-sheet-body">
                        <div class="salary-paid-block">
                            <h4>Salary Paid Slip</h4>
                            <div class="salary-paid-row">
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Name</div>
                                    <div class="block-right-side">
                                        {{ $payrollEmployee->full_name }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Skill Level</div>
                                    <div class="block-right-side">
                                        {{ optional($payrollEmployee->skillType)->category }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Date of Join</div>
                                    <div class="block-right-side">{{ date_converter()->eng_to_nep_convert($payrollEmployee->join_date) }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Month</div>
                                    <div class="block-right-side">{{ date_converter()->_get_nepali_month($paySlip->nep_month) . '-'. $paySlip->nep_year}}</div>
                                </div>
                               
                            </div>
                        </div>
                        <div class="salary-details-block">
                            <h5>Wages Details</h5>
                            <div class="salary-details-row">
                                @php
                                    $presentDays = $payrollEmployee->countPresentDays($paySlip->employee_id,$startDate,$endDate);
                                    $payable_amount = round(($presentDays*(optional($payrollEmployee->skillType)->daily_wage)),2);
                                    $paid_amount = round($paySlip->paid_amount,2);
                                    $tax = round((1/100)*$paid_amount,2);
                                    $total_amount=$paid_amount-$tax;
                                @endphp
                                
                                <div class="salary-details-column">
                                    <div class="block-left-side">Total Working Days</div>
                                    <div class="block-right-side">{{ $days }}
                                    </div>
                                </div>
                                <div class="salary-details-column">
                                    <div class="block-left-side">Total Worked Days</div>
                                    <div class="block-right-side">{{ $presentDays }}
                                    </div>
                                </div>
                                <div class="salary-details-column">
                                    <div class="block-left-side">Rate</div>
                                    <div class="block-right-side">Rs. {{ round(optional($payrollEmployee->skillType)->daily_wage,2)}}
                                    </div>
                                </div>
                                
                            </div>
                            <div class="salary-details-row">
                                <div class="salary-details-column">
                                    <div class="block-left-side">Total Payable Amount</div>
                                    <div class="block-right-side">Rs. {{ $payable_amount }}
                                    </div>
                                </div>
                                <div class="salary-details-column">
                                    <div class="block-left-side">Total Paid Amount</div>
                                    <div class="block-right-side">Rs. {{ $paid_amount }}
                                    </div>
                                </div>
                                <div class="salary-details-column">
                                    <div class="block-left-side">Tax Deduction</div>
                                    <div class="block-right-side">Rs. {{ $tax}}
                                    </div>
                                </div>
                                <div class="salary-details-column" >
                                    <div class="block-left-side" style="font-weight: bold">Payable Wages</div>
                                    <div class="block-right-side" style="font-weight: bold">Rs. {{ $total_amount}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            @if (Route::is('labour.printPaySlip'))
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
            @endif
            
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
