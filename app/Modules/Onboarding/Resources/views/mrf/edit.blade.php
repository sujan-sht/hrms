@extends('admin::layout')
@section('title') Manpower Requisition Forms @endSection  
@section('breadcrum')
<a href="{{route('mrf.index')}}" class="breadcrumb-item">Manpower Requisition Forms</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')

    {!! Form::model($mrfModel,['method'=>'PUT','route'=>['mrf.update',$mrfModel->id],'class'=>'form-horizontal','id'=>'mrfFormSubmit','role'=>'form','files'=>true]) !!} 
        
        @include('onboarding::mrf.partial.action',['btnType'=>'Update Record'])
        
    {!! Form::close() !!}

@endSection