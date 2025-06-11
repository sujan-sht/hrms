@extends('admin::layout')
@section('title') Letter Management @endSection  

@section('breadcrum')
    <a href="{{ route('letterManagement.index') }}" class="breadcrumb-item">Letter Management</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')
    {!! Form::open(['route'=>'letterManagement.store','method'=>'POST','class'=>'form-horizontal','id'=>'letterMgmtFormSubmit','role'=>'form','files' => false]) !!}
    
        @include('template::letter-management.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection