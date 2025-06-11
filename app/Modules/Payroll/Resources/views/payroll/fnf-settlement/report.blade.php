@extends('admin::layout')
@section('title') FNF Settlement @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">FNF Settlement</a>
@stop

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
@include('payroll::payroll.fnf-settlement.partial.filter')
@if (request()->get('organization_id'))
    <div class="card card-body">
        <div class="media align-items-center align-items-md-start flex-column flex-md-row">
            <div class="media-body text-center text-md-left">
            </div>
            <div class="ml-1">
                {{-- <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a> --}}
            </div>
        </div>
    </div>
    <div class="card card-body">
        <table id="table2excel" class="table table-responsive table-striped table-bordered">
            <thead>
                <tr class="text-white">
                    <th rowspan="2">Employee Name</th>
                    <th rowspan="2">Employee Code</th>
                    @if (isset($deduction['deduction']))
                        @php
                            $deductionCount = count($deduction['deduction']);
                            $incomeCount = count($income['income']);
                        @endphp
                    @else
                        @php
                            $deductionCount = 0;
                            $incomeCount = 0;
                        @endphp
                    @endif
                    <th colspan="{{ $incomeCount + 1 }}" class="text-center">Income</th>
                    <th colspan="{{ $deductionCount + 3 }}" class="text-center">Deduction</th>
                    <th rowspan="2">SST</th>
                    <th rowspan="2">TDS</th>
                    <th rowspan="2">Net Salary</th>
                </tr>
                <tr class="text-white">
                    @if (isset($income['income']))

                        @foreach ($income['income'] as $key => $value)
                            <th>{{ $key }}</th>
                        @endforeach
                    @endif
                    <th style="padding: 0px 60px;">Total</th>
                    <th>Leave Amount</th>
                    @if (isset($deduction['deduction']))
                        @foreach ($deduction['deduction'] as $key => $value)
                            <th>{{ $key }}</th>
                        @endforeach
                    @endif
                    <th>Fine & Penalty</th>
                    <th style="padding: 0px 60px;">Total</th>
                </tr>

            </thead>
            <tbody>
                @if (isset($finalData))
                    @foreach ($finalData as $key => $value)
                        <tr>
                            <td>{{ $value['name'] }}</td>
                            <td>{{ $value['code'] }}</td>
                            @foreach ($value['incomeSum'] as $income => $inc)
                                <td>{{ $inc }}</td>
                            @endforeach
                            <td>{{ $value['totalIncome'] }}</td>
                            <td>{{ $value['totalLeaveAmount'] }}</td>
                            @foreach ($value['deductionSum'] as $deduction => $ded)
                                <td>{{ $ded }}</td>
                            @endforeach
                            <td></td>
                            <td>{{ $value['totalDeduction'] }}</td>
                            <td>{{ $value['totalSst'] }}</td>
                            <td>{{ $value['totalTds'] }}</td>
                            <td>{{ $value['netSalary'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

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
                    name: "FNF Settlement",
                    filename: "fnf_settlement" + new Date().toISOString().replace(/[\-\:\.]/g,
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
