@extends('admin::layout')
@section('title') {{ $title }} @endSection
@section('breadcrum')
<a href="{{ route('advance.index') }}" class="breadcrumb-item">{{ $title }}</a>
<a class="breadcrumb-item active">Create</a>
@stop

@section('content')

    {!! Form::open(['route'=>'advance.store','method'=>'POST','class'=>'form-horizontal','id'=>'advanceFormSubmit','role'=>'form','files' => true]) !!}

        @include('advance::advance.partial.action',['btnType'=>'Save Record'])

    {!! Form::close() !!}

@endSection
