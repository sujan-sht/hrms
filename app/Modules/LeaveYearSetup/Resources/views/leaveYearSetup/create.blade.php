@extends('admin::layout')
@section('title') Leave Years @endSection
@section('breadcrum')
    <a href="{{route('leaveYearSetup.index')}}" class="breadcrumb-item">Leave Years</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'leaveYearSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'leaveYearSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('leaveyearsetup::leaveYearSetup.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
