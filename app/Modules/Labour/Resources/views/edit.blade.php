@extends('admin::layout')
@section('title')Skill Setup @stop
@section('breadcrum')
    <a href="{{ route('skillSetup.index') }}" class="breadcrumb-item">Skill Setup </a>
    <a class="breadcrumb-item active"> Edit</a>
@endsection

@section('content')
    <!-- Form inputs -->

    {!! Form::model($skill, [
        'route' => ['skillSetup.update', $skill->id],
        'method' => 'PATCH',
        'class' => 'form-horizontal',
        'role' => 'form',
        'id' => 'skill_submit',
        'files' => true,
    ]) !!}

    @include('labour::partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}

    <!-- /form inputs -->

@endsection
