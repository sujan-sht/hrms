@extends('admin::layout')
@section('title') Template Type @endSection  

@section('breadcrum')
    <a href="{{ route('templateType.index') }}" class="breadcrumb-item">Template Type</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::open(['route'=>'templateType.store','method'=>'POST','class'=>'form-horizontal','id'=>'templateTypeFormSubmit','role'=>'form','files' => false]) !!}
    
        @include('template::template-type.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection