@extends('admin::layout')
@section('title') Arrear Adjustment @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('incomeSetup.index')}}" class="breadcrumb-item">Arrear Adjustment</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($arrearAdjustmentModel,['method'=>'PUT','route'=>['arrearAdjustment.update', $arrearAdjustmentModel->id],'class'=>'form-horizontal','id'=>'incomeSetupFormSubmit','role'=>'form','files'=>true]) !!}

        @include('payroll::arrear-adjustment.partial.actionEdit',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
