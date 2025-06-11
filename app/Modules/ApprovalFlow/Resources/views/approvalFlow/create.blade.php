@extends('admin::layout')
@section('title') Approval Flows @endSection
@section('breadcrum')
    <a href="{{route('approvalFlow.index')}}" class="breadcrumb-item">Approval Flow</a>
    <a class="breadcrumb-item active">Create</a>
@endSection

@section('content')

    {!! Form::open(['route'=>'approvalFlow.store','method'=>'POST','class'=>'form-horizontal','id'=>'approvalFlowFormSubmit','role'=>'form','files' => true]) !!}

        @include('approvalflow::approvalFlow.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
