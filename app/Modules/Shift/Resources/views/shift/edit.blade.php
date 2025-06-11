@extends('admin::layout')

@section('title')
    Edit {{ $title }}
@endsection

@section('breadcrum')
    <a href="{{ route('shift.index') }}" class="breadcrumb-item">Shift </a>
    <a class="breadcrumb-item active"> Edit </a>
@endsection

@section('content')

    {!! Form::model($shiftModel, [
        'method' => 'PUT',
        'route' => ['shift.update', $shiftModel->id],
        'class' => 'form-horizontal',
        'id' => 'shiftFormSubmit',
        'role' => 'form',
        'files' => true,
    ]) !!}

    @include('shift::shift.partial.action', ['btnType' => 'Update'])

    {!! Form::close() !!}

@endsection
