@extends('admin::layout')
@section('title') btn-outline-secondary @endSection  
@section('breadcrum')
<a href="{{ route('bonus.index') }}" class="breadcrumb-item">Bonus</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'bonus.store','method'=>'POST','class'=>'form-horizontal','id'=>'payrollFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('payroll::bonus.partial.action',['btnType'=>'Generate']) 
    
    {!! Form::close() !!}

@endSection