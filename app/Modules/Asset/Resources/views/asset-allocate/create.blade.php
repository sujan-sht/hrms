@extends('admin::layout')
@section('title') Asset Allocation @endSection
@section('breadcrum')
<a href="{{route('assetAllocate.index')}}" class="breadcrumb-item">Asset Allocations</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'assetAllocate.store','method'=>'POST','class'=>'form-horizontal','id'=>'assetAllocateFormSubmit','role'=>'form','files' => true]) !!}

        @include('asset::asset-allocate.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
