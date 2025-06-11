@extends('admin::layout')
@section('title') KRA @endSection
@section('breadcrum')
    <a href="{{route('kra.index')}}" class="breadcrumb-item">KRAs</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'kra.store','method'=>'POST','class'=>'form-horizontal','id'=>'kraFormSubmit','role'=>'form','files' => true]) !!}

        @include('pms::kra.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
