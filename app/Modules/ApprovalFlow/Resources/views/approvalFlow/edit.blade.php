@extends('admin::layout')
@section('title') Approval Flows @endSection
@section('breadcrum')
    <a href="{{route('approvalFlow.index')}}" class="breadcrumb-item">Approval Flow</a>
    <a class="breadcrumb-item active">Edit</a>
@endSection

@section('content')

<div class="card">
    <div class="card-body">

        {!! Form::model($approvalFlowModel,['method'=>'PUT','route'=>['approvalFlow.update',$approvalFlowModel->id],'class'=>'form-horizontal','id'=>'approvalFlowFormSubmit','role'=>'form','files'=>true]) !!}

            @include('approvalflow::approvalFlow.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}

    </div>
</div>

@endSection
