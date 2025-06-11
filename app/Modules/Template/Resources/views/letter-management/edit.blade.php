@extends('admin::layout')
@section('title') Letter Management @endSection  
@section('breadcrum')
    <a href="{{ route('templateType.index') }}" class="breadcrumb-item">Letter Management</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($letter,['route'=>['letterManagement.update',$letter->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'letterMgmtFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('template::letter-management.partial.action',['btnType'=>'Update Record']) 
    
    {!! Form::close() !!}

@endSection