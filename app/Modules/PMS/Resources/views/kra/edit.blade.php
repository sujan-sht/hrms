@extends('admin::layout')
@section('title') KRA @endSection
@section('breadcrum')
    <a href="{{route('kra.index')}}" class="breadcrumb-item">KRAs</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($kraModel,['method'=>'PUT','route'=>['kra.update',$kraModel->id],'class'=>'form-horizontal','id'=>'kraFormSubmit','role'=>'form','files'=>true]) !!}

            @include('pms::kra.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
