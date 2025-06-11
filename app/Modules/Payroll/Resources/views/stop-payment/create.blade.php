@extends('admin::layout')
@section('title') Stop Payment @endSection  
@section('breadcrum')
<a href="{{ route('stopPayment.index') }}" class="breadcrumb-item">Stop Payment</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'stopPayment.store','method'=>'POST','class'=>'form-horizontal','id'=>'holdPaymentFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('payroll::stop-payment.partial.action',['btnType'=>'Generate']) 
    
    {!! Form::close() !!}

@endSection