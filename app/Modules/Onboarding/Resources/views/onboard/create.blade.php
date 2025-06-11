@extends('admin::layout')
@section('title') Onboard @endSection  
@section('breadcrum')
<a href="{{route('onboard.index')}}" class="breadcrumb-item">Onboards</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'onboard.store','method'=>'POST','class'=>'form-horizontal','id'=>'onboardFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('onboarding::onboard.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection