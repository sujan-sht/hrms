@extends('admin::layout')
@section('title') Arrear Adjustment @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('incomeSetup.index')}}" class="breadcrumb-item">Arrear Adjustment</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'arrearAdjustment.store','method'=>'POST','class'=>'form-horizontal','id'=>'incomeSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('payroll::arrear-adjustment.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
