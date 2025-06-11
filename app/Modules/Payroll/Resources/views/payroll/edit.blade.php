@extends('admin::layout')
@section('title') Payroll @endSection  
@section('breadcrum')
<a href="{{ route('payroll.index') }}" class="breadcrumb-item">Payroll</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($payrollModel,['method'=>'PUT','route'=>['payroll.update',$payrollModel->id],'class'=>'form-horizontal','id'=>'payrollFormSubmit','role'=>'form','files'=>true]) !!} 
            
            @include('payroll::payroll.partial.action',['btnType'=>'Update Record'])
            
        {!! Form::close() !!}

    </div>
</div>

@endSection