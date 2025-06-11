@extends('admin::layout')
@section('title') Evaluations @endSection  
@section('breadcrum')
<a href="{{route('evaluation.index')}}" class="breadcrumb-item">Evaluations</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'evaluation.store','method'=>'POST','class'=>'form-horizontal','id'=>'evaluationFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('onboarding::evaluation.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection