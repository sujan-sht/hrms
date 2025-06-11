@extends('admin::layout')
@section('title') Training @endSection
@section('breadcrum')
    <a href="{{route('training.index')}}" class="breadcrumb-item">Trainings</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'training.store','method'=>'POST','class'=>'form-horizontal','id'=>'trainingFormSubmit','role'=>'form','files' => true]) !!}

        @include('training::training.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
