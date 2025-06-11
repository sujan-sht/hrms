@extends('admin::layout')
@section('title') Income Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('incomeSetup.index')}}" class="breadcrumb-item">Income Setup</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($incomeSetupModel,['method'=>'PUT','route'=>['incomeSetup.update', $incomeSetupModel->id],'class'=>'form-horizontal','id'=>'incomeSetupFormSubmit','role'=>'form','files'=>true]) !!}

        @include('payroll::income-setup.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
