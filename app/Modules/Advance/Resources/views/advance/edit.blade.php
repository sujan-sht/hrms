
@extends('admin::layout')
@section('title') {{ $title }} @endSection
@section('breadcrum')
<a href="{{route('advance.index')}}" class="breadcrumb-item">{{ $title }}</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($advanceModel,['method'=>'PUT','route'=>['advance.update',$advanceModel->id],'class'=>'form-horizontal','id'=>'advanceFormSubmit','role'=>'form','files'=>true]) !!}

        @include('advance::advance.partial.edit_action', ['btnType'=>'Update Record'])

    {!! Form::close() !!}

@endSection
