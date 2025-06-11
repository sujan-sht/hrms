@extends('admin::layout')
@section('title') Leave @endSection  

@section('breadcrum')
    <a href="{{ route('cheatSheet.index') }}" class="breadcrumb-item">Cheat Sheet</a>
    <a class="breadcrumb-item active">Edit</a>
@endsection

@section('content')

    {!! Form::model($cheatSheet,['route'=>['cheatSheet.update',$cheatSheet->id],'method'=>'PUT','class'=>'form-horizontal','id'=>'cheatSheetFormSubmit','role'=>'form','files' => true]) !!}
    
        @include('template::cheat-sheet.partial.action',['btnType'=>'Update Record']) 
    
    {!! Form::close() !!}

@endSection