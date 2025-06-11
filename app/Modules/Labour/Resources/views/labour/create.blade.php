@extends('admin::layout')
@section('title')Labour @stop
@section('breadcrum')
    <a href="{{ route('labour.index') }}" class="breadcrumb-item">Labour</a>
    <a class="breadcrumb-item active"> Add Labour </a>
@endsection

@section('content')
    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'labour.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'skill_submit',
        'files' => true,
    ]) !!}
    @include('labour::labour.partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection

