@extends('admin::layout')
@section('title')Hierarchy @stop
@section('breadcrum')
    <a href="{{ route('hierarchySetup.index') }}" class="breadcrumb-item">Hierarchies </a>
    <a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($hierarchySetupModel, [
        'route' => ['hierarchySetup.update', $hierarchySetupModel->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'hierarchySetupSubmit',
    ]) !!}

        @include('setting::hierarchy-setup.partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}

@endsection
