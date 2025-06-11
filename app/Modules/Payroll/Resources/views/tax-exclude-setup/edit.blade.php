@extends('admin::layout')
@section('title') Tax Exclude Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('taxExcludeSetup.index')}}" class="breadcrumb-item"> Setup</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($taxExcludeSetupModel,['method'=>'PUT','route'=>['taxExcludeSetup.update', $taxExcludeSetupModel->id],'class'=>'form-horizontal','id'=>'taxExcludeSetupFormSubmit','role'=>'form','files'=>true]) !!}

        @include('payroll::tax-exclude-setup.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
