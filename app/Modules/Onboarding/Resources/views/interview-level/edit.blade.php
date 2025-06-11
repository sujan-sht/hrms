@extends('admin::layout')
@section('title') Interview Levels @endSection  
@section('breadcrum')
<a href="{{route('interviewLevel.index')}}" class="breadcrumb-item">Interview Levels</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($interviewLevelModel,['method'=>'PUT','route'=>['interviewLevel.update',$interviewLevelModel->id],'class'=>'form-horizontal','id'=>'interviewLevelFormSubmit','role'=>'form','files'=>true]) !!} 
        
        @include('onboarding::interview-level.partial.action',['btnType'=>'Update Record'])
        
    {!! Form::close() !!}

@endSection