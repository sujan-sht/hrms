@extends('admin::layout')
@section('title') Payroll Employees @endSection
@section('breadcrum')
<a href="{{route('payroll.index')}}" class="breadcrumb-item">Payroll </a>
<a class="breadcrumb-item active">Payroll Employees</a>
@stop

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
@include('payroll::payroll.partial.employee_filter')
<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">Payroll Employees for the month of {{ $payrollModel->month_title }}, {{ $payrollModel->year }}</h6>
            <b>Organization :</b> {{ optional($payrollModel->organization)->name }}
        </div>
    </div>
</div>

<div class="card card-body">
    <table class="table table-striped">
        <thead>
            <tr class="text-white">
                <th>S.N</th>
                <th>Employee Name</th>
                <th>Action</th>
            </tr>

        </thead>
        <tbody>
            @if(count($payrollEmployeeModels) > 0)
            {{-- {{dd($payrollModel)}} --}}
                @foreach($payrollEmployeeModels as $key => $payrollEmployee)
                {{-- {{dd($payrollEmployee)}} --}}
                @if(!in_array($payrollEmployee->employee_id,$holdPayment))
                    <tr class="myLine">
                        <td>#{{ ++$key }}</td>
                        <td>
                            <div class="media">
                                <div class="mr-3">
                                    <img src="{{ optional($payrollEmployee->employee)->getImage() }}" class="rounded-circle" width="40" height="40" alt="">
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-semibold">{{ optional($payrollEmployee->employee)->getFullName() }}</div>
                                    {{-- <span class="text-muted">{{ optional($payrollEmployee->employee)->official_email }}</span> --}}
                                </div>
                            </div>
                        </td>

                        <td>
                            <a href="{{ route('payroll.employee.history', $payrollEmployee->id) }}" class="btn btn-sm btn-outline-secondary btn-icon updateStatus mx-1" data-popup="tooltip" data-placement="top" data-original-title="History">
                                <i class="icon-history"></i>
                            </a>

                            <a href="{{ route('payroll.employee.salary.slip', $payrollEmployee->id) }}" class="btn btn-sm btn-outline-secondary btn-icon updateStatus mx-1" data-popup="tooltip" data-placement="top" data-original-title="Print">
                                <i class="icon-printer"></i>
                            </a>
                            <a href="{{ route('payroll.taxCalculation', $payrollEmployee->id) }}" class="btn btn-sm btn-outline-secondary btn-icon updateStatus mx-1" data-popup="tooltip" data-placement="top" data-original-title="View Tax Calculation">
                                <i class="icon-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endif

                @endforeach
            @endif
        </tbody>
    </table>

</div>
@endsection
