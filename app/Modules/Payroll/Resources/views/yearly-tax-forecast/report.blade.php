@extends('admin::layout')
@section('title') TDS Report @endSection
@section('breadcrum')
<a href="{{route('payroll.index')}}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">Yearly ForeCast Report</a>
@stop

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
{{-- @include('payroll::tds-report.partial.advance_filter') --}}

    @if (request()->get('organization_id'))
        <div class="card card-body">
            <table class="table table-striped">
                <thead>
                    <tr class="text-white">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        <th>Employee Code</th>
                        <th>Yearly Tax</th>
                    </tr>

                </thead>
                {{-- <tbody>
                    @if(isset($payrollModel))
                        @foreach($payrollModel->payrollEmployees as $key => $payrollEmployee)
                            <tr>
                                <td>{{ '#' . ++$key }}</td>

                                <td>
                                    <div class="media">
                                        <div class="media-body">
                                            <div class="media-title font-weight-semibold">{{ optional($payrollEmployee->employee)->getFullName() }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ optional($payrollEmployee->employee)->employee_code }}</td>
                                <td>{{ $payrollEmployee->tds }}</td>


                            </tr>
                        @endforeach
                    @endif

                </tbody> --}}
            </table>

        </div>
    @endif


@endsection
