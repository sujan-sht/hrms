@extends('admin::layout')
@section('title') Interviews @endSection  
@section('breadcrum')
<a href="{{route('interview.index')}}" class="breadcrumb-item">Interviews</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'interview.store','method'=>'POST','class'=>'form-horizontal','id'=>'interviewFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('onboarding::interview.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection