@extends('admin::layout')
@section('title') Applicants @endSection  
@section('breadcrum')
<a href="{{route('applicant.index')}}" class="breadcrumb-item">Applicants</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'applicant.store','method'=>'POST','class'=>'form-horizontal','id'=>'applicantFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('onboarding::applicant.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection