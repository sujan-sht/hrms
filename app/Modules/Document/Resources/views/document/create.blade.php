@extends('admin::layout')
@section('title') Document @endSection  
@section('breadcrum')
<a href="{{route('document.index')}}" class="breadcrumb-item">Documents</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'document.store','method'=>'POST','class'=>'form-horizontal','id'=>'documentFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('document::document.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection