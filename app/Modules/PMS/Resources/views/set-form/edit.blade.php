@extends('admin::layout')
@section('title') Form Setup @endSection
@section('breadcrum')
    {{-- <a href="{{route('target.index')}}" class="breadcrumb-item">Targets</a> --}}
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')
{{--
<div class="card">
    <div class="card-body"> --}}

        {{-- {!! Form::model($targetModel,['method'=>'PUT','route'=>['target.update',$targetModel->id],'class'=>'form-horizontal','id'=>'targetFormSubmit','role'=>'form','files'=>true]) !!} --}}

            @include('pms::set-form.partial.action',['btnType'=>'Update Record'])

        {{-- {!! Form::close() !!} --}}

    {{-- </div>
</div> --}}

@endSection
