@extends('admin::layout')
@section('title') FNF Settlement @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">FNF Settlement</a>
@stop

@section('css')
<link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<style type="text/css" media="print">
    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0 50px 0 50px;
        /* this affects the margin in the printer settings */
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>
@include('payroll::payroll.fnf-settlement.partial.filter')
@if (request()->get('organization_id'))
    <div class="salary-sheet">
        {!! Form::open([
            'route' => 'save.fullandfinal',
            'method' => 'POST',
            'class' => 'form-horizontal',
            'role' => 'form',
            'files' => true,
        ]) !!}
        @csrf
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="salary-sheet-body">
                        <div class="salary-paid-block">
                            <h4>Employee Details</h4>
                            <div class="salary-paid-row">
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Employee Name</div>
                                    <div class="block-right-side">{{ @$employee->getFullName() }}</div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Designation</div>
                                    <div class="block-right-side">{{ @$employee->designation->dropvalue }}</div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Date of Join</div>
                                    <div class="block-right-side">
                                        {{ setting('calendar_type') == 'BS' ? $employee->nepali_join_date : date('M d, Y', strtotime($employee->join_date)) }}
                                    </div>
                                </div>
                                <div class="salary-paid-column">
                                    <div class="block-left-side">Branch</div>
                                    <div class="block-right-side">{{ @$employee->branchModel->name }}</div>
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
                                @foreach ($leaveDetails as $detail)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">{{ @$detail['title'] }}</div>
                                        <div class="block-left-side">{{ @$detail['total_balance'] }}</div>
                                        <div class="block-left-side">{{ @$detail['eligible_encashment'] }}</div>
                                        <div class="block-right-side">Rs {{ @$detail['amount'] }}</div>
                                        @php
                                            $totalLeaveAmount += @$detail['amount'] ?? 0;
                                        @endphp
                                    </div>
                                @endforeach
                            </div>

                            <div class="salary-details-row">
                                <div class="salary-details-subTitle">Retirement Details</div>
                                @foreach ($organizationDeduction as $key)
                                    <div class="salary-details-column">
                                        <div class="block-left-side">{{ @$key }}</div>
                                        <div class="block-right-side">Rs. {{ @$retirenmentData[$key] ?? 0 }}</div>
                                    </div>
                                @endforeach
                                <div class="salary-details-column">
                                    <div class="block-left-side">PF</div>
                                    <div class="block-right-side">Rs. 0</div>
                                </div>

                            </div>
                            @if (isset($holdMergedData) && count($holdMergedData['incomes']) > 0)
                                <div class="salary-details-row">
                                    <div class="salary-details-subTitle">Hold Payments</div>
                                    <div class="salary-details-column">
                                        <div class="salary-details-row">
                                            @foreach ($holdMergedData as $key => $data)
                                                <div class="salary-details-subTitle">{{ strtoupper($key) }}</div>
                                                @foreach ($data as $index => $value)
                                                    @if ($value > 0)
                                                        <div class="hold-payment-block">
                                                            <div class="block-left-side">{{ @$incomesData[$index] }}
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
                                            @foreach ($organizationDeduction as $key)
                                                @php
                                                    $retirementPlan += @$retirenmentData[$key] ?? 0;
                                                @endphp
                                            @endforeach
                                            <div class="block-right-side ">Rs. {{ @$retirementPlan ?? 0 }}</div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Payment On Hold</div>
                                            <div class="block-right-side ">Rs. {{ @$paymentOnHold }}</div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Total</div>
                                            <div class="block-right-side totalIncome"
                                                data-totalValue="{{ @$retirementPlan + @$paymentOnHold }}">Rs.
                                                {{ @$retirementPlan + @$paymentOnHold }}
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
                                                data-advancevalue="{{ @$advancePayment }}">
                                                Rs. {{ @$advancePayment }}</div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Fine & Penalty</div>
                                            <div class="block-right-side ">
                                                <input type="text"
                                                    class="form-control form-control-sm numeric fine calculateFinalDeductionAmount"
                                                    value="0" placeholder="Enter fine/penalty" name="fine_penalty">
                                            </div>
                                        </div>
                                        <div class="hold-payment-block">
                                            <div class="block-left-side">Adjustment</div>
                                            <div class="block-right-side ">
                                                <input type="text"
                                                    class="form-control form-control-sm numeric adjustment calculateFinalDeductionAmount"
                                                    value="{{ @$adjustmentPayment }}" placeholder="Enter adjustment" name="adjustment">
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
                                @foreach ($taxData as $index => $key)
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
                                        <input type="text" class="form-control form-control-sm" id="remarks"
                                            placeholder="Enter remarks" name="remarks">
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
            <input type="text" hidden value="{{@$formData}}" name="form_data">
            <div class="text-center">
                <button type="submit" class="btn btn-success">
                    <i class="icon-printer"></i>&nbsp&nbsp&nbspUpdate
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endif
@endsection
@section('script')
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
    $(document).ready(function(){
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
        $(document).on('keyup', '.calculateFinalDeductionAmount', function(event) {
            calculateTotalDeduction();
        });
    });
</script>
@endsection
