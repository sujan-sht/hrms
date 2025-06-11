@extends('admin::layout')
@section('title')Hierarchy @stop
@section('breadcrum')
    <a href="{{ route('hierarchySetup.index') }}" class="breadcrumb-item">Hierarchies</a>
    <a class="breadcrumb-item active"> Add </a>
@endsection

@section('content')

    {!! Form::open([
        'route' => 'hierarchySetup.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'hierarchySetupSubmit',
    ]) !!}

        @include('setting::hierarchy-setup.partial.action', ['btnType' => 'Save Record'])

    {!! Form::close() !!}

@endsection
