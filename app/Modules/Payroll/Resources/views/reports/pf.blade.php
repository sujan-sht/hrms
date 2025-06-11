@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')
@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('reports.pfReport') }}" class="breadcrumb-item">PF Report</a>
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

@include('payroll::reports.partial.advance-cit-filter')

{{-- @include('payroll::payroll.partial.upload') --}}
<form id="myForm" action="#" method="POST">
    @csrf

    <div class="card card-body">
        {{-- <div id="table-scroll" class="table-scroll"> --}}
            <div class="table-wrap">
                <table class="table table-responsive table-bordered" id="table2excel">
                    <thead>
                        <tr class="text-light btn-slate">
                            <th rowspan="2" class="freeze-head freeze1 hd">S.N</th>
                            <th rowspan="2" class="freeze-head freeze2 hd">Employee Name</th>
                            <th rowspan="2" class="freeze-head">Join Date</th>
                            <th rowspan="2" class="freeze-head">PF Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if(count($payrollEmployees))
                        @php
                            $pfGrandValue = 0;

                        @endphp
                            @foreach ($payrollEmployees as $key => $payrollEmployee)

                                @php
                                $totalDeduction = 0;
                                $totalIncome = 0;
                                $total_days = 0;
                                if ($calenderType == 'nep') {
                                    $joinDate = optional($payrollEmployee->employee)->nepali_join_date;
                                    $terminatedDate = optional($payrollEmployee->employee)->nep_archived_date;
                                } else {
                                    $joinDate = optional($payrollEmployee->employee)->join_date;
                                    $terminatedDate = optional($payrollEmployee->employee)->archived_date;
                                }
                                if (null !== $joinDate) {
                                    $explodeJoinDate = explode('-', $joinDate);
                                    $joinMonth = (int) $explodeJoinDate[1];
                                    $joinDay = (int) $explodeJoinDate[2];
                                    $joinYear = $explodeJoinDate[0];
                                    if ($terminatedDate) {
                                        $explodeTerminatedDate = explode('-', $terminatedDate);
                                        $terminatedMonth = (int) $explodeTerminatedDate[1];
                                        $terminatedDay = (int) $explodeTerminatedDate[2];
                                        $terminatedYear = $explodeTerminatedDate[0];
                                    }
                                }
                                @endphp

                                <tr class="myLine">
                                    <td class="freeze1 table-bg-white">
                                        #{{ ++$key }}
                                        <input type="hidden" name="payrollEmployeeId" value="{{ $payrollEmployee->id }}"
                                            class="payrollEmployeeId">
                                    </td>
                                    <td class="freeze2 table-bg-white">
                                        <div class="media">
                                            <div class="mr-3">
                                                <img src="{{ optional($payrollEmployee->employee)->getImage() }}"
                                                    class="rounded-circle" width="40" height="40" alt="">
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ optional($payrollEmployee->employee)->getFullName() }}</div>
                                                <span
                                                    class="email text-muted">{{ optional($payrollEmployee->employee)->official_email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $joinDate }}
                                    </td>
                                    <td>
                                        @php
                                            $pfValue = 0;
                                            foreach($payrollEmployee->incomes as $k => $incomes){
                                                if($incomes->incomeSetup->short_name == 'PF'){
                                                    $pfValue = $incomes->value;
                                                    $pfGrandValue += $pfValue;
                                                }
                                            }
                                        @endphp
                                        {{ number_format($pfValue, 2)  }}
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr class="myLine">
                                <td class="freeze1 table-bg-white" colspan="24">
                                    No SFF Record Found!!!
                                </td>
                            </tr>
                        @endif

                    </tbody>
                    <footer>
                        @if(count($payrollEmployees))
                        <tr>
                            <td colspan="3" class="text-center">Total</td>
                            <td class="text-center">{{ number_format($pfGrandValue, 2)  }}</td>
                        </tr>
                    @endif
                    </footer>
                </table>
            </div>
        {{-- </div> --}}

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
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                // var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
                // $(table).table2excel({
                //     exclude: ".noExl",
                //     name: "Payroll Report",
                //     filename: "payroll_report_" + new Date().toISOString().replace(/[\-\:\.]/g,
                //         "") + ".xls",
                //     fileext: ".xls",
                //     exclude_img: true,
                //     exclude_links: true,
                //     exclude_inputs: true
                // });
                var clone = $(table).clone(); // Create a clone of the table for exporting
                // Remove hidden input elements from the clone
                clone.find('input[type="hidden"]').remove();
                clone.find('td span.email').remove();
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

                        // Update employee dropdown
                        var EmployeeSelect = $('#employee_id');
                        EmployeeSelect.empty();
                        EmployeeSelect.append('<option value="">Select Employee</option>');
                        $.each(response.employee, function(key, value) {
                            EmployeeSelect.append('<option value="' + key + '">' + value + '</option>');
                        });

                        // Reinitialize select2
                        yearSelect.select2();
                        monthSelect.select2();
                        EmployeeSelect.select2();

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

