@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('reports.citReport') }}" class="breadcrumb-item">Annual Projection Report</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('css')
<style>
     thead tr:nth-child(2) th {
            background: #546e7a;
            position: sticky;
            top: 45px;
            /* z-index: 2; */
        } 
    .table-scroll {
        position: relative;
        max-width: 100%;
        /* margin: auto; */
        overflow: hidden;
        /* border: 0.5px; */
    }

    .table-wrap {
        width: 100%;
        overflow: auto;
    }

    .table-scroll table {
        width: 100%;
        margin: auto;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* .table-scroll th, */
    .table-scroll td {
        /* background: #fff; */
        /* white-space: nowrap; */
        vertical-align: top;
        text-align: center;
    }

    .table-scroll th {
        background: #fff;
        /* white-space: nowrap; */
        /* vertical-align: top; */
        text-align: center;
    }

    .table-bg-white {
        background: #fff;
    }

    .table-scroll thead,
    .table-scroll tfoot {
        background: #f9f9f9;
    }

    .freeze1 {
        position: sticky;
        left: 0;
    }

    .freeze2 {
        position: sticky;
        left: 60px;
    }

    .freeze3 {
        position: sticky;
        left: 396px;
    }

    .freeze4 {
        position: sticky;
        left: 428px;
    }

    .freeze5 {
        position: sticky;
        left: 528px;
    }


    .freeze-head {
        background: #546e7a;
        position: sticky;
        top: 32px;
    }

    .hd {
        z-index: 2 !important;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>

@include('payroll::payroll.fnf-settlement.partial.projection-filter')

@if(isset(request()->organization_id))

<div class="card card-body">
    <div class="row">
        <div class="col-md-4 ml-auto text-right">
            <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
        </div>
    </div>
    <div id="table-scroll" class="table-scroll mt-2">
        <div class="table-wrap">
            <table class="table table-responsive table-bordered" id="table2excel">
                {{-- <table id="table2excel" class="table table-responsive table-striped table-bordered"> --}}
                <thead>
                    <tr class="text-white">
                        <th rowspan="2" class="freeze-head freeze1 hd">Employee Name</th>
                        <th rowspan="2" class="freeze-head">Branch Name</th>
                        <th colspan="{{ count($leaveDetails) }}" class="text-center freeze-head">Leave Details</th>
                        <th colspan="{{ count($organizationDeduction)+1 }}" class="text-center freeze-head">Retirement Details</th>
                        <th colspan="2" class="text-center freeze-head">Hold Payments</th>
                        <th colspan="8" class="text-center freeze-head">Full & Final Settlement</th>
                        <th colspan="{{ count($taxData)+1 }}" class="text-center freeze-head">Tax Details</th>
                        
                    </tr>
                    <tr class="text-white">
                        @foreach ($leaveDetails as $detail)
                            <th>{{ @$detail['title']}}</th>
                        @endforeach
                        @foreach ($organizationDeduction as $deduction)
                            <th>{{ $deduction }}</th>
                        @endforeach
                        <th>PF</th>
                        <th>Total Hold Income</th>
                        <th>Total Hold Deduction</th>

                        <th>Leave Encashment Amount</th>
                        <th>Retirement plan Amount</th>
                        <th>Payment On Hold</th>
                        <th>Total</th>
                        <th>Advance</th>
                        <th>Fine & Penalty</th>
                        <th>Adjustment</th>
                        <th>Total</th>
                        @foreach ($taxData as $index => $key)
                            <th>{{ strtoupper(@$index) }}</th>
                        @endforeach
                        <th>Tax Arrear Amount</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $totalHoldIncomes=0;
                    $totalHoldDeduction=0;
                    $retirementPlan = 0;
                    $totalLeaveAmount = 0;
                    @endphp
                    @foreach ($holdMergedData['incomes'] as $key => $data)
                        @php
                            $totalHoldIncomes+=$data;
                        @endphp
                    @endforeach
                    @foreach ($holdMergedData['deductions'] as $key => $data)
                        @php
                            $totalHoldDeduction+=$data;
                        @endphp
                    @endforeach
                    <tr>
                        <td>{{ @$employee->getFullName() }}</td>
                        <td>{{ @$employee->branchModel->name }}</td>
                        @foreach ($leaveDetails as $detail)
                            <td>{{@$detail['amount'] }}</td>
                            @php
                            $totalLeaveAmount += @$detail['amount'] ?? 0;
                        @endphp
                        @endforeach
                        @foreach ($organizationDeduction as $key)
                            <td>{{ @$retirenmentData[$key] ?? 0 }}</td>
                        @endforeach
                        <td>0</td>
                        <td>{{$totalHoldIncomes}}</td>
                        <td>{{$totalHoldDeduction}}</td>
                        <td>{{ @$totalLeaveAmount }}</td>
                        @foreach ($organizationDeduction as $key)
                            @php
                                $retirementPlan += @$retirenmentData[$key] ?? 0;
                            @endphp
                        @endforeach
                        <td>{{ @$retirementPlan ?? 0 }}</td>
                        <td>{{@$paymentOnHold}}</td>
                        <td> {{ @$retirementPlan + @$paymentOnHold }}</td>
                        <td>{{ @$advancePayment }}</td>
                        <td>0</td>
                        <td>{{@$adjustmentPayment}}</td>
                        <td>{{@$advancePayment+$adjustmentPayment}}</td>
                        @foreach ($taxData as $index => $key)
                           <td>{{ @$key ?? 0 }}</td>
                        @endforeach
                        <td>0</td>
                        <td class="" id="totalAmount">{{ @$retirementPlan + @$paymentOnHold -($advancePayment+$adjustmentPayment)+@$totalLeaveAmount }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif


@endsection
@section('script')
<!-- select2 js -->

<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
    
    $(document).ready(function() {

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