@extends('admin::layout')
@section('title') Stocks @endSection
@section('breadcrum')
<a href="{{route('assetQuantity.index')}}" class="breadcrumb-item">Stocks</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')
    {!! Form::open(['route'=>'assetQuantity.store','method'=>'POST','class'=>'form-horizontal','id'=>'assetQuantityFormSubmit','role'=>'form','files' => true]) !!}

        @include('asset::asset-quantity.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}
@endSection
