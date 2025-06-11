@extends('admin::layout')

@section('title')
    Create Function
@endsection

@section('breadcrum')
    <a href="{{ route('function.index') }}" class="breadcrumb-item" Function </a>
        <a class="breadcrumb-item active"> Create </a>
    @endsection

    @section('content')
        {!! Form::open([
            'route' => 'function.store',
            'method' => 'POST',
            'class' => 'form-horizontal',
            'id' => 'functionFormSubmit',
            'role' => 'form',
            'files' => true,
        ]) !!}

        @include('setting::function.partial.action', ['btnType' => 'Save Record'])

        {!! Form::close() !!}
    @endsection
