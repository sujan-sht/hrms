@extends('admin::layout')
@section('title') Leave Years @endSection
@section('breadcrum')
    <a href="{{route('leaveYearSetup.index')}}" class="breadcrumb-item">Leave Years</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($leaveYearSetupModel,['method'=>'PUT','route'=>['leaveYearSetup.update',$leaveYearSetupModel->id],'class'=>'form-horizontal','id'=>'leaveYearSetupFormSubmit','role'=>'form','files'=>true]) !!}

            @include('leaveyearsetup::leaveYearSetup.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
