@extends('admin::layout')
@section('title') Tax Exclude Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('taxExcludeSetup.index')}}" class="breadcrumb-item">Tax Exclude Setup</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'taxExcludeSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'taxExcludeSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('payroll::tax-exclude-setup.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
