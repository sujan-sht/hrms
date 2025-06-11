@extends('admin::layout')
@section('title') KPI @endSection
@section('breadcrum')
    <a href="{{route('kpi.index')}}" class="breadcrumb-item">KPIs</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'kpi.store','method'=>'POST','class'=>'form-horizontal','id'=>'kpiFormSubmit','role'=>'form','files' => true]) !!}

        @include('pms::kpi.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
