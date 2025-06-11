@extends('admin::layout')
@section('title') Template Type @endSection  
@section('breadcrum')
    <a href="{{ route('templateType.index') }}" class="breadcrumb-item">Template Type</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($templateType,['route'=>['templateType.update',$templateType->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'templateTypeFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('template::template-type.partial.action',['btnType'=>'Updates Record']) 
    
    {!! Form::close() !!}

@endSection