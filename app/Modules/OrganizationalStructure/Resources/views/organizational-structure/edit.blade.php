@extends('admin::layout')
@section('title')Organizational Structure @stop
@section('breadcrum')
    <a href="{{ route('organizationalStructure.index') }}" class="breadcrumb-item">Organizational Structure</a>
    <a class="breadcrumb-item active">Edit</a>
@stop

@section('content')
    {!! Form::model($orgStructure, [
        'method' => 'PUT',
        'route' => ['organizationalStructure.update', $orgStructure->id],
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'orgStructureFormSubmit',
        'files' => true,
    ]) !!}
        @include('organizationalstructure::organizational-structure.partial.action', ['btnType' => 'Update Record'])
    {!! Form::close() !!}
@stop
