@extends('admin::layout')
@section('title') Asset Allocation @endSection
@section('breadcrum')
<a href="{{route('assetAllocate.index')}}" class="breadcrumb-item">Asset Allocations</a>
<a class="breadcrumb-item active">Edit</a>
@stop

@section('content')
    {!! Form::model($assetAllocateModel,['method'=>'PUT','route'=>['assetAllocate.update',$assetAllocateModel->id],'class'=>'form-horizontal','id'=>'assetAllocateFormSubmit','role'=>'form','files'=>true]) !!}

        @include('asset::asset-allocate.partial.action',['btnType'=>'Update Record'])

    {!! Form::close() !!}
@endSection
