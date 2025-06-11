@extends('admin::layout')

@section('title')
    Create Grade
@endsection

@section('breadcrum')
    <a href="{{ route('level.index') }}" class="breadcrumb-item">Grade </a>
    <a class="breadcrumb-item active"> Create </a>
@endsection

@section('content')
    {!! Form::open([
        'route' => 'level.store',
        'method' => 'POST',
        'class' => 'form-horizontal',
        'id' => 'levelFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('setting::level.partial.action', ['btnType' => 'Save Record'])

    {!! Form::close() !!}
@endsection
