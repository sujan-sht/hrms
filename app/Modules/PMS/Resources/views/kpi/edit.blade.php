@extends('admin::layout')
@section('title') KPI @endSection
@section('breadcrum')
    <a href="{{route('kpi.index')}}" class="breadcrumb-item">KPIs</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($kpiModel,['method'=>'PUT','route'=>['kpi.update',$kpiModel->id],'class'=>'form-horizontal','id'=>'kpiFormSubmit','role'=>'form','files'=>true]) !!}

            @include('pms::kpi.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
