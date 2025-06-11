@extends('admin::layout')
@section('title') Target @endSection
@section('breadcrum')
    <a href="{{route('target.index')}}" class="breadcrumb-item">Targets</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'target.store','method'=>'POST','class'=>'form-horizontal','id'=>'targetFormSubmit','role'=>'form','files' => true]) !!}

        @include('pms::target.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
