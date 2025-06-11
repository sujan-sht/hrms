@extends('admin::layout')
@section('title') Cheat Sheet @endSection  

@section('breadcrum')
    <a href="{{ route('cheatSheet.index') }}" class="breadcrumb-item">Cheat Sheet</a>
    <a class="breadcrumb-item active">Create</a>
@endsection

@section('content')

    {!! Form::open(['route'=>'cheatSheet.store','method'=>'POST','class'=>'form-horizontal','id'=>'cheatSheetFormSubmit','role'=>'form','files' => false]) !!}
    
        @include('template::cheat-sheet.partial.action',['btnType'=>'Save Record']) 
    
    {!! Form::close() !!}

@endSection