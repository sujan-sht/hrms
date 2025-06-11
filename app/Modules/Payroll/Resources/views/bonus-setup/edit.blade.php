@extends('admin::layout')
@section('title') Bonus Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('bonusSetup.index')}}" class="breadcrumb-item">Bonus Setup</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($bonusSetupModel,['method'=>'PUT','route'=>['bonusSetup.update', $bonusSetupModel->id],'class'=>'form-horizontal','id'=>'bonusSetupFormSubmit','role'=>'form','files'=>true]) !!}

        @include('payroll::bonus-setup.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
