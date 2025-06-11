@extends('admin::layout')

@section('title')
    Edit Function
@endsection

@section('breadcrum')
    <a href="{{ route('function.index') }}" class="breadcrumb-item">Function</a>
    <a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')
    {!! Form::model($function, [
        'route' => ['function.update', $function->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'id' => 'functionFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('setting::function.partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}
@endsection
