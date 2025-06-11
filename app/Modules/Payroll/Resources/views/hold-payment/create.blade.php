@extends('admin::layout')
@section('title') Hold Payment @endSection  
@section('breadcrum')
<a href="{{ route('holdPayment.index') }}" class="breadcrumb-item">Hold Payment</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'holdPayment.store','method'=>'POST','class'=>'form-horizontal','id'=>'holdPaymentFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('payroll::hold-payment.partial.action',['btnType'=>'Generate']) 
    
    {!! Form::close() !!}

@endSection