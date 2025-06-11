@extends('admin::layout')
@section('title') Evaluations @endSection  
@section('breadcrum')
<a href="{{route('evaluation.index')}}" class="breadcrumb-item">Evaluations</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($evaluationModel,['method'=>'PUT','route'=>['evaluation.update',$evaluationModel->id],'class'=>'form-horizontal','id'=>'evaluationFormSubmit','role'=>'form','files'=>true]) !!} 
        
        @include('onboarding::evaluation.partial.action',['btnType'=>'Update Record'])
        
    {!! Form::close() !!}

@endSection