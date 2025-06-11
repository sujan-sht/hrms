@extends('admin::layout')
@section('title') Fiscal Years @endSection
@section('breadcrum')
    <a href="{{route('fiscalYearSetup.index')}}" class="breadcrumb-item">Fiscal Years</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($fiscalYearSetupModel,['method'=>'PUT','route'=>['fiscalYearSetup.update',$fiscalYearSetupModel->id],'class'=>'form-horizontal','id'=>'fiscalYearSetupFormSubmit','role'=>'form','files'=>true]) !!}

            @include('fiscalyearsetup::fiscalYearSetup.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
