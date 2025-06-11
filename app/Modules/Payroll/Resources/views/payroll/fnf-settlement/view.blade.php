
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FNF Settlement Report</title>

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
    <style type="text/css">
        .table-responsive {
            height: 500px;
            overflow: scroll;
            background-image: url({{ asset('admin/hrms_background.png') }});
            background-position: center;
            background-size: cover;
        }

        thead tr:nth-child(1) th {
            background: #546e7a;
            /* position: sticky; */
            top: 0px;
            z-index: 1;
        }

        thead tr:nth-child(2) th {
            background: #546e7a;
            position: sticky;
            top: 60px;
            /* z-index: 2; */
        }

        thead tr:nth-child(3) th {
            background: #546e7a;
            position: sticky;
            top: 88px;
            z-index: 3;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 9999;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner:before {
            content: '';
            box-sizing: border-box;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin-top: -10px;
            margin-left: -10px;
            border-radius: 50%;
            border: 2px solid #ccc;
            border-top-color: #000;
            animation: spinner .6s linear infinite;
        }

        .error {
            color: red;
        }

        #loading {
            position: fixed;
            display: flex;
            display: none;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.7;
            background-color: #fff;
            z-index: 99;
        }

        #loading-image {
            z-index: 100;
            margin-left: 44%;
            margin-top: 17%;
            width: 20%;
        }

        .hold-payment-block {
            display: flex;
            justify-content: space-around;
            width: 540px;
            padding: 7px 11px;
        }
    </style>
    <style type="text/css" media="print">
        @page {
        size: auto;
        /* auto is the initial value */
        margin: 0 50px 0 50px;
        /* this affects the margin in the printer settings */
    }
        .hold-payment-block{
            display: flex;
            justify-content: space-around;
            width: 540px;
            padding: 7px 11px;
        }
    </style>
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
                    style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="salary-sheet-body">
                        <div class="salary-paid-block">
                            <h4>Employee Details</h4>
                            <div class="salary-paid-row">
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Employee Name</div>
                                    <div class="block-right-side">{{ @$finalData->employeeData->getFullName() }}</div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Designation</div>
                                    <div class="block-right-side">{{ @$finalData->employeeData->designation->dropvalue }}</div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Date of Join</div>
                                    <div class="block-right-side">
                                        {{ setting('calendar_type') == 'BS' ? $finalData->employeeData->nepali_join_date : date('M d, Y', strtotime($finalData->employeeData->join_date)) }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Branch</div>
                                    <div class="block-right-side">{{ @$finalData->employeeData->branchModel->name }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="salary-details-block">
                            <h5></h5>
                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Leave Details</div>
                                {{-- <div class="salary-details-subTitle">Total Balance</div>
                                    <div class="salary-details-subTitle">Eligible Encashment</div> --}}
                                <div class="salary-details-column">
                                    <div class="block-left-side">Leave Type</div>
                                    <div class="block-left-side">Total Balance</div>
                                    <div class="block-left-side">Eligible Encashment</div>
                                    <div class="block-right-side"></div>
                                </div>
                                @php
                                    $totalLeaveAmount = 0;
                                @endphp
                                @foreach ($finalData->leaveDetails as $detail)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">{{ @$detail->title }}</div>
                                        <div class="block-left-side">{{ @$detail->total_balance }}</div>
                                        <div class="block-left-side">{{ @$detail->eligible_encashment }}</div>
                                        <div class="block-right-side">Rs {{ @$detail->amount }}</div>
                                        @php
                                            $totalLeaveAmount += @$detail->amount ?? 0;
                                        @endphp
                                    </div>
                                @endforeach
                            </div>

                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Retirement Details</div>
                                @foreach ($finalData->organizationDeduction as $key)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">{{ @$key }}</div>
                                        <div class="block-right-side">Rs. {{ @$finalData->retirenmentData->$key ?? 0 }}</div>
                                    </div>
                                @endforeach
                                <div class="salary-details-column">
                                    <div class="block-left-side">PF</div>
                                    <div class="block-right-side">Rs. 0</div>
                                </div>

                            </div>
                            @if (isset($finalData->holdMergedData) && !empty($finalData->holdMergedData->incomes))
                                <div class="salary-details-row">
                                    <div class="salary-details-subTitle">Hold Payments</div>
                                    <div class="salary-details-column">
                                        <div class="salary-details-row">
                                            @foreach ($finalData->holdMergedData as $key => $data)
                                                <div class="salary-details-subTitle">{{ strtoupper($key) }}</div>
                                                @foreach ($data as $index => $value)
                                                    @if ($value > 0)
                                                        <div class="hold-payment-block">
                                                            <div class="block-left-side">{{ @$finalData->incomesData->$index }}
                                                            </div>
                                                            <div class="block-right-side ">Rs. {{ @$value }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Full & Final Settlement</div>
                                <div class="salary-details-column">
                                    <div class="salary-details-row">
                                        <div class="salary-details-subTitle">INCOME</div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Leave Encashment Amount</div>
                                            <div class="block-right-side totalLeaveAmount"
                                                data-totalLeaveAmount="{{ @$totalLeaveAmount }}">Rs.
                                                {{ @$totalLeaveAmount }}</div>
                                        </div>
                                        @php
                                            $retirementPlan = 0;
                                        @endphp
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Retirement plan Amount</div>
                                            @foreach ($finalData->organizationDeduction as $key)
                                                @php
                                                    $retirementPlan += @$finalData->retirenmentData->$key ?? 0;
                                                @endphp
                                            @endforeach
                                            <div class="block-right-side ">Rs. {{ @$retirementPlan ?? 0 }}</div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Payment On Hold</div>
                                            <div class="block-right-side ">Rs. {{ @$finalData->paymentOnHold }}</div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Total</div>
                                            <div class="block-right-side totalIncome"
                                                data-totalValue="{{ @$retirementPlan + @$finalData->paymentOnHold }}">Rs.
                                                {{ @$retirementPlan + @$finalData->paymentOnHold }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="salary-details-column">
                                    <div class="salary-details-row">
                                        <div class="salary-details-subTitle">DEDUCTION</div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Advance</div>
                                            <div class="block-right-side advance"
                                                data-advancevalue="{{ @$finalData->advancePayment }}">
                                                Rs. {{ @$finalData->advancePayment }}</div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Fine & Penalty</div>
                                            <div class="block-right-side ">
                                                <input type="text"
                                                    class="form-control form-control-sm numeric fine calculateFinalDeductionAmount"
                                                    value="{{ @$finalData->fine_penalty }}" placeholder="Enter fine/penalty" name="fine_penalty" readonly>
                                            </div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Adjustment</div>
                                            <div class="block-right-side ">
                                                <input type="text"
                                                    class="form-control form-control-sm numeric adjustment calculateFinalDeductionAmount"
                                                    value="{{ @$finalData->adjustment }}" placeholder="Enter adjustment" name="adjustment" readonly>
                                            </div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Total</div>
                                            <div class="block-right-side ">Rs.<span class="deductionTotal"
                                                    data-totalDeductionAmount="">0</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Tax Details</div>
                                @foreach ($finalData->taxData as $index => $key)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">{{ strtoupper(@$index) }}</div>
                                        <div class="block-right-side">Rs. {{ @$key ?? 0 }}</div>
                                    </div>
                                @endforeach
                                <div class="salary-details-column">
                                    <div class="block-left-side">Tax Arrear Amount</div>
                                    <div class="block-right-side">Rs. 0</div>
                                </div>
                            </div>
                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Total</div>
                                <div class="salary-details-column">
                                    <div class="block-left-side">Remarks</div>
                                    <div class="block-right-side">
                                        <input type="text" class="form-control form-control-sm font-weight-bold bg-light" id="remarks"
                                            placeholder="Enter remarks" name="remarks" readonly value="{{@$finalData->remarks}}">
                                    </div>
                                </div>
                                <div class="salary-details-column">
                                    <div class="block-left-side">Total</div>
                                    <div class="block-right-side">
                                        <input type="text"
                                            class="form-control form-control-sm font-weight-bold bg-light"
                                            id="totalAmount" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
            <br>
            <div class="text-center">
                <button type="button" class="btn btn-primary" id="print-salary-slip">
                    <i class="icon-printer"></i>&nbsp&nbsp&nbspPrint
                </button>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
   $(document).ready(function(){
    $(document).ready(function() {
        $('#print-salary-slip').click(function() {
            $(this).hide();
            window.print();
            $(this).show();
        });
    });
        var selectedEmployee = null;
        const calculateFinalAmount = () => {
            var totalIncome = parseFloat($('.totalIncome').attr('data-totalValue')) || 0;
            var totaldeduction = parseFloat($('.deductionTotal').attr('data-totalDeductionAmount')) || 0;
            var totalLeaveAmount = parseFloat($('.totalLeaveAmount').attr('data-totalLeaveAmount')) || 0;
            var amount = totalIncome + totalLeaveAmount - totaldeduction;
            $('#totalAmount').val(amount);
        }
        const calculateTotalDeduction = () => {
            var advanceValue = parseFloat($('.advance').attr('data-advancevalue')) || 0;
            var fineValue = parseFloat($('.fine').val()) || 0;
            var adjustmentValue = parseFloat($('.adjustment').val()) || 0;
            var finalAmount = advanceValue + fineValue + adjustmentValue;
            $('.deductionTotal').text(finalAmount).attr('data-totalDeductionAmount', finalAmount);
            calculateFinalAmount();
        }
        calculateTotalDeduction();
   });
    
</script>
</html>
