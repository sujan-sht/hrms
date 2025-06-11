@extends('admin::layout')
@section('title') Resignations @endSection  
@section('breadcrum')
<a href="{{route('resignation.index')}}" class="breadcrumb-item">Resignations</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'resignation.store','method'=>'POST','class'=>'form-horizontal','id'=>'resignationFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('offboarding::resignation.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection