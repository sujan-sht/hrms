@extends('admin::layout')

@section('title')
    {{ $title }}
@endsection

@section('breadcrum')
    <a href="" class="breadcrumb-item">Payroll</a>
    <a class="breadcrumb-item">Threshold Benefit </a>
    <a class="breadcrumb-item active">Setup</a>

@endsection

{{-- @inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles') --}}

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>
    @include('payroll::threshold-benefit-setup.partial.filter')
    @if (request()->get('organizationId'))
        @if (count($deductionList) > 0)
            {!! Form::open([
                'route' => 'thresholdBenefitSetup.store',
                'method' => 'POST',
                'id' => '',
                'class' => 'form-horizontal',
                'role' => 'form',
            ]) !!}
            @include('payroll::threshold-benefit-setup.partial.action', ['btnType' => 'Save Record'])
            {!! Form::close() !!}
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
