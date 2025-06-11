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

@include('payroll::reports.partial.annual-projection-filter')

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
                        <th rowspan="2" class="freeze-head freeze1 hd">S.N</th>
                        <th rowspan="2" class="freeze-head freeze1 hd">Employee Name</th>
                        <th rowspan="2" class="freeze-head">Branch Name</th>
                        <th colspan="{{ count($months) + 1 }}" class="text-center freeze-head">Incomes</th>
                        
                    </tr>
                    <tr class="text-white">
                        @foreach ($months as $month)
                            <th>{{ $month }}</th>
                        @endforeach
                        <th style="padding: 0px 60px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($employees) > 0)
                        @php $count = 1; @endphp
                        {{-- @dd($employees); --}}
                        @foreach($employees as $key => $employeeModel)
                            @php
                                $totalIncome = 0;
                            @endphp
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ optional($employeeModel->employee)->full_name }}</td>
                                <td>{{ optional($employeeModel->employee)->branchModel->name }}</td>
                                {{-- @dd($employeeModel['months']); --}}
                                @foreach ($employeeModel['incomes'] as $income)
                                @php
                                    $totalIncome += $income
                                @endphp
                                    <th>{{ $income }}</th>
                                @endforeach
                                <td>{{ number_format($totalIncome, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr colspan="14">
                            <td>No record found.</td>
                        </tr>
                    @endif
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

        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                var clone = $(table).clone(); // Clone the table for exporting
                
                // Remove hidden input elements from the clone
                clone.find('input[type="hidden"]').remove();
                // Optionally remove specific elements
                clone.find('td span.email').remove();

                clone.find('td').each(function() {
                    var mediaTitle = $(this).find('.media-title').text().trim();
                    var adjustmentInput = $(this).find('input.adjustment').val();
                    if (mediaTitle) {
                        $(this).text(mediaTitle); // Replace <td> content with only the employee's name
                    } 
                    else if(adjustmentInput){
                        $(this).text(adjustmentInput); // Replace <td> content with only the adjustment value
                    }else {
                        var divContent = $(this).find('div').text().trim();
                        if (divContent) {
                            $(this).text(divContent); // Use the text inside the <div> if available
                        } else {
                            var inputValue = $(this).find('input').val();
                            if (inputValue) {
                                $(this).text(inputValue); // If <input> is present, use its value
                            }
                        }
                    }
                });

                clone.find('tr').each(function() {
                    $(this).find('.noExl').remove();
                });

                clone.table2excel({
                    exclude: ".noExl",
                    name: "Payroll Report",
                    filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });
            }
        });
    });
</script>
@endsection