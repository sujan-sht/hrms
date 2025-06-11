@extends('admin::layout')
@section('title')Organizational Structure @stop
@section('breadcrum')
    <a href="{{ route('organizationalStructure.index') }}" class="breadcrumb-item">Organizational Structure</a>
    <a class="breadcrumb-item active">Create</a>
@stop
@section('content')
    {!! Form::open([
        'route' => 'organizationalStructure.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'orgStructureFormSubmit',
        'files' => true,
    ]) !!}
        @include('organizationalstructure::organizational-structure.partial.action', ['btnType' => 'Save Record'])
    {!! Form::close() !!}
@stop
