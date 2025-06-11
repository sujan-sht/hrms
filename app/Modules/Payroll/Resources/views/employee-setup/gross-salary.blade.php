@extends('admin::layout')

@section('title')
    {{ $title }}
@endsection

@section('breadcrum')
    <a href="" class="breadcrumb-item">Payroll</a>
    <a class="breadcrumb-item">Employee Setup</a>
    <a class="breadcrumb-item active">Gross Salary</a>

@endsection

{{-- @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles') --}}

@section('content')
    @include('payroll::employee-setup.partial.advance_search')
    @if (request()->get('organization_id'))
    <div class="text-right mb-4 mr-5">
        <a href="{{ route('payroll.exportGrossSalary', request()->all()) }}"
            class="btn btn-success rounded-pill"><i class="icon-file-excel"></i> Export</a>
        <a href="#" data-toggle="modal" data-target="#modal_default_import" class="btn btn-primary rounded-pill"> <i class="icon-file-excel text-success"></i>Import</a>
    </div>
    @include('payroll::employee-setup.partial.upload')
        {{-- @if (count($incomeList) > 0) --}}
            {!! Form::open([
                'route' => 'employeeSetup.store.grossSalary',
                'method' => 'POST',
                'id' => '',
                'class' => 'form-horizontal',
                'role' => 'form',
            ]) !!}
            @include('payroll::employee-setup.partial.gross-salary-create', ['btnType' => 'Save Record'])
            {{-- {!! Form::hidden('organization_id', $_GET['organization_id'], ['class' => 'form-control']) !!} --}}

            {!! Form::close() !!}
        {{-- @endif --}}

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
