@extends('admin::layout')
@section('title') Document @endSection
@section('breadcrum')
<a href="{{route('document.index')}}" class="breadcrumb-item">Documents</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

<div class="card">
    <div class="card-body">
        {!! Form::model($documentModel,['method'=>'PUT','route'=>['document.update',$documentModel->id],'class'=>'form-horizontal','id'=>'documentFormSubmit','role'=>'form','files'=>true]) !!}

            @include('document::document.partial.action',['btnType'=>'Update Record'])

        {!! Form::close() !!}
    </div>
</div>

@endSection
