@extends('admin::layout')
@section('title') Organization @endSection
@section('breadcrum')
    <a href="{{ route('organization.index') }}" class="breadcrumb-item">Organizations</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">
        {!! Form::model($organizationModel,['method'=>'PUT','route'=>['organization.update',$organizationModel->id],'class'=>'form-horizontal','id'=>'organizationFormSubmit','role'=>'form','files'=>true]) !!}

            @include('organization::organization.partial.action',['btnType'=>'Update'])

        {!! Form::close() !!}
    </div>
</div>

@endSection
