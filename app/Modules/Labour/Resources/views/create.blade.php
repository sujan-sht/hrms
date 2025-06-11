@extends('admin::layout')
@section('title')Skill Setup @stop
@section('breadcrum')
    <a href="{{ route('skillSetup.index') }}" class="breadcrumb-item">Skill Setups</a>
    <a class="breadcrumb-item active"> Add Skill Setup </a>
@endsection

@section('content')
    <!-- Form inputs -->

    {!! Form::open([
        'route' => 'skillSetup.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'skill_submit',
        'files' => true,
    ]) !!}
    @include('labour::partial.action', ['btnType' => 'Save'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection

