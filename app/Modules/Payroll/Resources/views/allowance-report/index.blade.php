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

@include('payroll::allowance-report.filter')

<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of {{$type ?? 'Food' }} Allowance Report</h6>
            All The {{$type ?? 'Food' }} Allowance Report Information will listed below.
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
                <th>Unit</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employeeData as $index => $employee)
                @php
                    $employee = (object) $employee;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $employee->employee_code }}</td>
                    <td>{{ $employee->employee_name }}</td>
                    <td>{{ $employee->total_units ?? '' }}</td>
                    <td>{{ number_format($employee->rate, 2) }}</td>
                    <td>{{ number_format($employee->amount, 2) }}</td>
                    <td>{{ number_format($employee->tax, 2) }}</td>
                    <td>{{ number_format($employee->total, 2) }}</td>
                </tr>
            @empty
            @endforelse
        </tbody>
        <tfoot class="mt-5">

            <tr class="btn-slate ">
                <th colspan="3">Total</th>
                <th><strong> {{ number_format(collect($employeeData)->sum('total_units'), 2) }} </strong></th>
                <th><strong> {{ number_format(collect($employeeData)->sum('rate'), 2) }} </strong></th>
                <th><strong> {{ number_format(collect($employeeData)->sum('amount'), 2) }} </strong></th>
                <th><strong>{{ number_format(collect($employeeData)->sum('tax'), 2) }} </strong></th>
                <th><strong>{{ number_format(collect($employeeData)->sum('total'), 2) }} </strong></th>
            </tr>

        </tfoot>
    </table>
</div>

    @endsection
