@extends('admin::layout')
@section('title') Leave Amount Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('leaveAmountSetup.index')}}" class="breadcrumb-item">Leave Amount Setup</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($leaveAmountSetupModel,['method'=>'PUT','route'=>['leaveAmountSetup.update', $leaveAmountSetupModel->id],'class'=>'form-horizontal','id'=>'leaveAmountSetupFormSubmit','role'=>'form','files'=>true]) !!}

        @include('payroll::leave-amount-setup.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
