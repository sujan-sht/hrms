@extends('admin::layout')
@section('title') Interview Levels @endSection  
@section('breadcrum')
<a href="{{route('interviewLevel.index')}}" class="breadcrumb-item">Interview Levels</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'interviewLevel.store','method'=>'POST','class'=>'form-horizontal','id'=>'interviewLevelFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('onboarding::interview-level.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection