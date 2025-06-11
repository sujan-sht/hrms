@extends('admin::layout')

@section('title')
    Edit Grade
@endsection

@section('breadcrum')
    <a href="{{ route('level.index') }}" class="breadcrumb-item">Grade</a>
    <a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')
    {!! Form::model($level, [
        'route' => ['level.update', $level->id],
        'method' => 'PUT',
        'class' => 'form-horizontal',
        'id' => 'levelFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('setting::level.partial.action', ['btnType' => 'Update Record'])

    {!! Form::close() !!}
@endsection
