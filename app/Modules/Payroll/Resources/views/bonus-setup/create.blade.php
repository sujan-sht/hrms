@extends('admin::layout')
@section('title') Bonus Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('bonusSetup.index')}}" class="breadcrumb-item">Bonus Setup</a>
    <a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'bonusSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'bonusSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('payroll::bonus-setup.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
