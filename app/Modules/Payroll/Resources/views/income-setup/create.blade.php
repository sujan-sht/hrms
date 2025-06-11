@extends('admin::layout')
@section('title') Income Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('incomeSetup.index')}}" class="breadcrumb-item">Income Setup</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'incomeSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'incomeSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('payroll::income-setup.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
