@extends('admin::layout')
@section('title') Fiscal Years @endSection
@section('breadcrum')
    <a href="{{route('fiscalYearSetup.index')}}" class="breadcrumb-item">Fiscal Years</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'fiscalYearSetup.store','method'=>'POST','class'=>'form-horizontal','id'=>'fiscalYearSetupFormSubmit','role'=>'form','files' => true]) !!}

        @include('fiscalyearsetup::fiscalYearSetup.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
