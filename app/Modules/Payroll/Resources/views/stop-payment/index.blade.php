@extends('admin::layout')
@section('title') Stop Payment @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Payroll</a>
<a class="breadcrumb-item active">Stop Payment</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
<div class="card card-body">
    <div class="media align-items-center align-items-md-start flex-column flex-md-row">
        <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
            <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
        </a>
        <div class="media-body text-center text-md-left">
            <h6 class="media-title font-weight-semibold">List of Stop Payment</h6>
            All the stop payment Information will listed below.
        </div>
        <div class="ml-2">
            <a href="{{ route('stopPayment.create') }}" class="btn btn-danger rounded-pill"><i
                    class="icon-plus3"></i>Create New</a>
        </div>

    </div>
</div>
<div class="card card-body">

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr class="text-light btn-slate">
                    <th>#</th>
                    <th>Organization Name</th>
                    <th>Employee Name</th>
                    <th>From date</th>
                    <th>To Date</th>
                    <th>Nep From date</th>
                    <th>Nep To Date</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @if ($stopPayments->total() != 0)
                    @foreach ($stopPayments as $key => $value)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ optional($value->organizationModel)->name }}</td>
                            <td>{{ optional($value->employeeModel)->getFullName() }}</td>
                            <td>{{ $value->from_date }}</td>
                            <td>{{ $value->to_date }}</td>
                            <td>{{ $value->nep_from_date }}</td>
                            <td>{{ $value->nep_to_date }}</td>
                            <td>{{ $value->notes }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7">No Fiscal Year Found !!!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <span class="float-right pagination align-self-end mt-3">
            {{ $stopPayments->appends(request()->all())->links() }}
        </span>
    </div>
</div>
@endsection