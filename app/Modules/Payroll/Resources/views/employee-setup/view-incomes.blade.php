@extends('admin::layout')

@section('title')
    {{ $title }}
@endsection

@section('breadcrum')
    <a href="" class="breadcrumb-item">Payroll</a>
    <a class="breadcrumb-item">Employee Setup</a>
    <a class="breadcrumb-item active">Income</a>

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
        @if (count($incomeList) > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-light btn-slate">
                        <th>S.N</th>
                        <th>Employee Name</th>
                        {{-- <th>Gross Salary</th> --}}
                        @foreach ($incomeList as $k => $income)
                            <th>{{ $income }}</th>
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
                                            <img src="{{ $item->getImage() }}"
                                                class="rounded-circle" width="40" height="40" alt="">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="media-title font-weight-semibold">
                                            {{ $item->getFullName() }}</div>
                                        <span
                                            class="text-muted">{{ $item->official_email }}</span>
                                    </div>
                                </div>
                            </td>


                                @foreach ($item->employeeIncomeSetup as $i)
                                    <td>{{$i->amount}}</td>
                                @endforeach

                        </tr>
                    @endforeach
                </tbody>
            </table>
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
