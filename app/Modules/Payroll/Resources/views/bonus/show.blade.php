@extends('admin::layout')
@section('title') Payroll @endSection
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">View</a>
@stop

@section('css')
<style>
    thead tr th {
            background: #546e7a;
            position: sticky;
            top: 0px;
            z-index: 1;
        }
        thead tr:nth-child(2) th {
            background: #546e7a;
            position: sticky;
            top: 44px;
            /* z-index: 2; */
        } 

</style>
@endsection
@section('content')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">Bonus for the month of {{ $bonusModel->month_title }},
                {{ $bonusModel->year }}</h6>
            <b>Organization :</b> {{ optional($bonusModel->organization)->name }}
        </div>
        <div class="ml-1">
            <a id="exportToExcel" class="btn btn-success rounded-pill">Export Report</a>
        </div>
    </div>
</div>

<div class="card card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="table2excel">
            <thead>
                <tr class="text-white">
                    <th rowspan="2">S.N</th>
                    <th rowspan="2">Employee Name</th>
                    <th class="text-center" colspan="{{ count($incomes) }}">Bonus</th>
                    <th rowspan="2">TDS</th>
                    <th rowspan="2">Payable Salary</th>
                </tr>
                <tr class="text-white">
                    @foreach ($incomes as $income)
                        <th>{{ $income }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($bonusModel->bonusEmployees as $key => $bonusEmployee)
                    <tr>
                        <td>
                            #{{ ++$key }}
                        </td>
                        <td>
                            <div class="media">
                                <div class="mr-3">
                                    <img src="{{ optional($bonusEmployee->employee)->getImage() }}"
                                        class="rounded-circle" width="40" height="40" alt="">
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-semibold">
                                        {{ optional($bonusEmployee->employee)->getFullName() }}</div>
                                    <span
                                        class="email text-muted">{{ optional($bonusEmployee->employee)->official_email }}</span>
                                </div>
                            </div>
                        </td>
                        @foreach ($bonusEmployee->incomes as $income)
                            <td>{{ $income->value }}</td>
                        @endforeach
                        <td>{{ $bonusEmployee->tds }}</td>
                        <td>{{ $bonusEmployee->payable_salary }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="text-center">
    <a href="{{ route('payroll.index') }}" class="btn btn-secondary btn-labeled btn-labeled-left mr-1"><b><i
                class="icon-backward2"></i></b>Go Back</a>
</div>
@endsection
@section('script')
<script src="{{ asset('admin/js/jquery.table2excel.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#exportToExcel").click(function(e) {
            var table = $('#table2excel');
            if (table && table.length) {
                var clone = $(table).clone();
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
    });
</script>
@endsection
