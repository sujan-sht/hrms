@extends('admin::layout')
@section('title') Hold Payment @endSection
@section('breadcrum')
    <a class="breadcrumb-item">Payroll</a>
    <a href="{{route('incomeSetup.index')}}" class="breadcrumb-item">Hold Payment</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($holdPayment,['method'=>'PUT','route'=>['holdPayment.update', $holdPayment->id],'class'=>'form-horizontal','id'=>'holdPaymentFormSubmit','role'=>'form','files'=>true]) !!}

        @include('payroll::hold-payment.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
