@extends('admin::layout')
@section('title') Arrear Adjustment @endSection
@section('breadcrum')
<a class="breadcrumb-item active">Payroll</a>
<a class="breadcrumb-item active">Arrear Adjustment</a>
@stop

@inject('menuRoles', '\App\Modules\User\Services\CheckUserRoles')

@section('content')
<div class="row">
        <div class="col-lg-12">
            <a href="{{ asset('manuals/bidhee_hrms_manual.pdf') }}" target="_blank" class="float-right" style="margin-top: -15px"><i class="icon-help" style="font-size:2em"></i></a>
        </div>
    </div>

@include('payroll::mass-increment.partial.advance-filter')

@if (request()->get('organization_id'))
    {!! Form::open([
        'route' => 'arrearAdjustment.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => '',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('payroll::arrear-adjustment.partial.action', ['btnType' => 'Save Record'])
    {{-- {!! Form::hidden('organization_id', request()->get('organization_id')) !!} --}}

    {!! Form::close() !!}
@endif
@endsection


@section('script')

@endSection
