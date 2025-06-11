@extends('admin::layout')
@section('title') Deduction Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('deductionSetup.index')}}" class="breadcrumb-item">Deduction Setup</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($deductionSetupModel,['method'=>'PUT','route'=>['deductionSetup.update', $deductionSetupModel->id],'class'=>'form-horizontal','id'=>'deductionSetupFormSubmit','role'=>'form','files'=>true]) !!}

        @include('payroll::deduction-setup.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
