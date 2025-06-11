@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('reports.payrollReport') }}" class="breadcrumb-item">Payroll Report</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('css')
<style>
    .table-scroll {
        position: relative;
        max-width: 100%;
        margin: auto;
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

@include('payroll::reports.partial.advance-filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">Payroll for the month of {{ optional($payrollModel)->month_title }},
                {{ optional($payrollModel)->year }}</h6>
            <b>Organization :</b> {{ optional(optional($payrollModel)->organization)->name }}
        </div>
        <div class="ml-1">
            <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
        </div>
    </div>
</div>

{{-- @include('payroll::payroll.partial.upload') --}}
<form id="myForm" action="#" method="POST">
    @csrf

    <div class="card card-body">
        <div id="table-scroll" class="table-scroll">
            <div class="table-wrap">
                @php
                    if(is_null($incomes)){$incomes = [];}
                    if(is_null($deductions)){$deductions = [];}
                    if(is_null($taxExcludeValues)){$taxExcludeValues = [];}
                @endphp
                @if (isset($_GET['incomes_id']) || isset($_GET['deduction_id']) || isset($_GET['column_id']))
                    @include('payroll::reports.partial.filterTable')
                @else
                    @include('payroll::reports.partial.table')
                @endif
            </div>
        </div>

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
    // jQuery(document).ready(function() {
    //     jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
    // });
    $(document).ready(function() {
        // $("#exportToExcel").click(function(e) {
        //     var table = $('#table2excel');
        //     if (table && table.length) {
        //         // var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        //         // $(table).table2excel({
        //         //     exclude: ".noExl",
        //         //     name: "Payroll Report",
        //         //     filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
        //         //         "") + ".xls",
        //         //     fileext: ".xls",
        //         //     exclude_img: true,
        //         //     exclude_links: true,
        //         //     exclude_inputs: true
        //         // });
        //         var clone = $(table).clone(); // Create a clone of the table for exporting
        //         // Remove hidden input elements from the clone
        //         clone.find('input[type="hidden"]').remove();
        //         clone.find('td span.email').remove();
        //         clone.table2excel({
        //             exclude: ".noExl",
        //             name: "Payroll Report",
        //             filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
        //                 "") + ".xls",
        //             fileext: ".xls",
        //             exclude_img: true,
        //             exclude_links: true,
        //             exclude_inputs: true
        //         });
        //     }
        // });

        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                var clone = $(table).clone(); // Create a clone of the table for exporting
                // Remove hidden input elements from the clone
                clone.find('input[type="hidden"]').remove();
                clone.find('td span.email').remove();
                clone.find('td, th').each(function() {
                    var cleanHtml = $(this).html().replace(/<[^>]*>/g, ''); // Remove HTML tags
                    $(this).html(cleanHtml.trim()); // Trim any extra spaces
                });
                clone.table2excel({
                    exclude: ".noExl",
                    name: "Payroll Report",
                    filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
                        "") + ".xls",
                    fileext: ".xls",
                    exclude_img: true,
                    exclude_links: true,
                    exclude_inputs: true
                });
            }
        });



        $('#organization_id').change(function() {
            var organizationId = $(this).val();
            if (organizationId) {
                $.ajax({
                    url: '{{ route("payroll.getOrganizationYearMonth") }}',
                    type: 'GET',
                    data: { organization_id: organizationId },
                    success: function(response) {
                        // Update year dropdown
                        var yearSelect = $('#year_id');
                        yearSelect.empty();
                        yearSelect.append('<option value="">Select Year</option>');
                        $.each(response.years, function(key, value) {
                            yearSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Update month dropdown
                        var monthSelect = $('#month_id');
                        monthSelect.empty();
                        monthSelect.append('<option value="">Select Month</option>');
                        $.each(response.months, function(key, value) {
                            monthSelect.append('<option value="' + key + '">' + value + '</option>');
                        });


                        // Update Income dropdown
                        // var incomeSelect = $('#income_id');
                        // incomeSelect.empty();
                        // incomeSelect.append('<option value="">Select Income</option>');
                        // $.each(response.incomes, function(key, value) {
                        //     incomeSelect.append('<option value="' + key + '">' + value + '</option>');
                        // });


                        // Update Deduction dropdown
                        var deductionSelect = $('#deduction_id');
                        deductionSelect.empty();
                        $.each(response.deductions, function(key, value) {
                            deductionSelect.append('<option value="' + key + '">' + value + '</option>');
                        });


                         // Update Income dropdown
                        var incomeSelect = $('#income_id');
                        incomeSelect.empty();
                        $.each(response.incomes, function(key, value) {
                            incomeSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Destroy the existing multiselect instance if it's initialized
                        incomeSelect.multiselect('destroy');
                        deductionSelect.multiselect('destroy');

                        // Reinitialize the multiselect with filtering enabled
                        incomeSelect.multiselect({
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true
                        });

                         // Reinitialize select2
                         deductionSelect.multiselect({
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true
                        });

                        yearSelect.select2();
                        monthSelect.select2();
                    }
                });
            }
        });

        $('#year_id').change(function() {
            var organizationId = $('#organization_id').val();
            var year = $(this).val();
            if (organizationId && year) {
                $.ajax({
                    url: '{{ route("payroll.getOrganizationMonth") }}',
                    type: 'GET',
                    data: {
                        organization_id: organizationId,
                        year: year
                    },
                    success: function(response) {
                        var monthSelect = $('#month_id');
                        monthSelect.empty();
                        monthSelect.append('<option value="">Select Month</option>');
                        $.each(response.months, function(key, value) {
                            monthSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        monthSelect.select2();
                    }
                });
            }
        });

    });
</script>
<script>
</script>
@endSection

