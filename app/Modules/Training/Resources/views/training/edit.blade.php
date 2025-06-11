@extends('admin::layout')
@section('title') Training @endSection
@section('breadcrum')
    <a href="{{route('training.index')}}" class="breadcrumb-item">Trainings</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($trainingModel,['method'=>'PUT','route'=>['training.update',$trainingModel->id],'class'=>'form-horizontal','id'=>'trainingFormSubmit','role'=>'form','files'=>true]) !!}

            @include('training::training.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
