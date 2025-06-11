@extends('admin::layout')

@section('title')
    {{ $title }}
@endsection

@section('breadcrum')
    <a href="" class="breadcrumb-item">Payroll</a>
    <a class="breadcrumb-item">Employee Setup</a>
    <a class="breadcrumb-item active">Deduction</a>
@endsection

{{-- @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles') --}}

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
    {{-- @include('payroll::employee-setup.partial.advance_search') --}}
    @if (request()->get('organization_id'))
        @if (count($deductionList) > 0)
            <div class="card card-body">
                <div class="media align-items-center align-items-md-start flex-column flex-md-row">
                    <a href="#" class="text-teal mr-md-3 align-self-md-center mb-3 mb-md-0">
                        <i class="icon-gallery text-success-400 border-success-400 border-3 rounded-round p-2"></i>
                    </a>
                    <div class="media-body text-center text-md-left">
                        <h6 class="media-title font-weight-semibold">List of Employee Deduction</h6>
                        All the Deduction Setup Information will listed below. You can Create and Modify the data.
                    </div>
                    <div class="mt-1">
                        <a href="{{ route('deductionSetup.create') }}" class="btn btn-success rounded-pill">Create New</a>
                    </div>
                </div>
            </div>
            <div class="card card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-light btn-slate">
                                <th>S.N</th>
                                <th>Employee Name</th>
                                {{-- <th>Gross Salary</th> --}}
                                @foreach ($deductionList as $k => $deduction)
                                    <th>{{ $deduction }}</th>
                                @endforeach

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employeeList as $key => $item)
                                <tr>
                                    <td>{{ '#' . ++$key }}</td>
                                    <td>
                                        <div class="media">
                                            <div class="mr-3">
                                                <a href="#">
                                                    <img src="{{ $item->getImage() }}" class="rounded-circle" width="40"
                                                        height="40" alt="">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <div class="media-title font-weight-semibold">
                                                    {{ $item->getFullName() }}</div>
                                                <span class="text-muted">{{ $item->official_email }}</span>
                                            </div>
                                        </div>
                                    </td>


                                    @foreach ($item->employeeDeductionSetup as $i)
                                        <td>{{ $i->amount }}</td>
                                    @endforeach

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <ul class="pagination pagination-rounded justify-content-end mb-3">
                    {{-- @if ($attendanceModels->total() != 0)
                {{ $attendanceModels->links() }}
            @endif --}}
                </ul>
            </div>
        </div>
    @endif
@endsection
