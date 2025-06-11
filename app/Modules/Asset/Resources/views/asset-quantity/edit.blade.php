@extends('admin::layout')
@section('title') Stocks @endSection
@section('breadcrum')
<a href="{{route('assetQuantity.index')}}" class="breadcrumb-item">Stocks</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')
    {!! Form::model($assetQuantityModel,['method'=>'PUT','route'=>['assetQuantity.update',$assetQuantityModel->id],'class'=>'form-horizontal','id'=>'assetQuantityFormSubmit','role'=>'form','files'=>true]) !!}

        @include('asset::asset-quantity.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}
@endSection
