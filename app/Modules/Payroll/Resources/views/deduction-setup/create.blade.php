@extends('admin::layout')
@section('title') Deduction Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('deductionSetup.index')}}" class="breadcrumb-item">Deduction Setup</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'deductionSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'deductionSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('payroll::deduction-setup.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
