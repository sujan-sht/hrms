@extends('admin::layout')
@section('title') Allowance Report @endSection
@section('breadcrum')
<a class="breadcrumb-item active"> {{$type ?? 'Food' }} Allowance Report</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right"
            style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
    </div>
</div>

@include('payroll::allowance-report.all-report-filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Allowance Report</h6>
            All The Allowance Report Information will listed below.
        </div>
        <div class="mt-1">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="btn-slate text-light">
                    <th>S.N</th>
                    <th>Employee Code</th>
                    <th>Employee Name</th>
                    <th>Food Unit</th>
                    <th>Food Rate</th>
                    <th>Food Amount</th>
                    <th>Food Tax</th>
                    <th>Food Total</th>
                    <th>Shift Unit</th>
                    <th>Shift Rate</th>
                    <th>Shift Amount</th>
                    <th>Shift Tax</th>
                    <th>Shift Total</th>
                    <th>Holidays Unit</th>
                    <th>Holiday Rate</th>
                    <th>Holiday Amount</th>
                    <th>Night Unit</th>
                    <th>Night Rate</th>
                    <th>Night Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employeeData as $index => $employee)
                @php
                $employee = (object) $employee;
                $foodReport = $employee->reports['food'] ?? null;
                $shiftReport = $employee->reports['shift'] ?? null;
                $holidayReport = $employee->reports['holiday'] ?? null;
                $nightReport = $employee->reports['night'] ?? null;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $employee->employee_code }}</td>
                    <td>{{ $employee->employee_name }}</td>

                    <!-- Food Columns -->
                    <td>{{ $foodReport['total_units'] ?? 0 }}</td>
                    <td>{{ $foodReport['rate'] ?? 0 }}</td>
                    <td>{{ $foodReport['amount'] ?? 0 }}</td>
                    <td>{{ $foodReport['tax'] ?? 0 }}</td>
                    <td>{{ $foodReport['total'] ?? 0 }}</td>

                    <!-- Shift Columns -->
                    <td>{{ $shiftReport['total_units'] ?? 0 }}</td>
                    <td>{{ $shiftReport['rate'] ?? 0 }}</td>
                    <td>{{ $shiftReport['amount'] ?? 0 }}</td>
                    <td>{{ $shiftReport['tax'] ?? 0 }}</td>
                    <td>{{ $shiftReport['total'] ?? 0 }}</td>

                    <!-- Holiday Columns -->
                    <td>{{ $holidayReport['total_units'] ?? 0 }}</td>
                    <td>{{ $holidayReport['rate'] ?? 0 }}</td>
                    <td>{{ $holidayReport['amount'] ?? 0 }}</td>

                    <!-- Night Columns -->
                    <td>{{ $nightReport['total_units'] ?? 0 }}</td>
                    <td>{{ $nightReport['rate'] ?? 0 }}</td>
                    <td>{{ $nightReport['amount'] ?? 0 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="19" class="text-center">No data found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot class="mt-5">
                <tr class="btn-slate">
                    <th colspan="3">Total</th>

                    <!-- Food Totals -->
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['food']['total_units'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['food']['rate'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['food']['amount'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['food']['tax'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['food']['total'] ?? 0; }), 2) }}</th>

                    <!-- Shift Totals -->
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['shift']['total_units'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['shift']['rate'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['shift']['amount'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['shift']['tax'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['shift']['total'] ?? 0; }), 2) }}</th>

                    <!-- Holiday Totals -->
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['holiday']['total_units'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['holiday']['rate'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['holiday']['amount'] ?? 0; }), 2) }}</th>

                    <!-- Night Totals -->
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['night']['total_units'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['night']['rate'] ?? 0; }), 2) }}</th>
                    <th>{{ number_format(collect($employeeData)->sum(function($emp) { return
                        $emp['reports']['night']['amount'] ?? 0; }), 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    @endsection
