@extends('admin::layout')
@section('title') Form Setup @endSection
@section('breadcrum')
    <a class="breadcrumb-item" href="{{route('set-form.index')}}">Forms</a>
    <a class="breadcrumb-item active">Form Setup</a>
@endSection

@section('content')
        @include('pms::set-form.partial.action',['btnType'=>'Proceed'])
@endSection
